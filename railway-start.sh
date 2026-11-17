#!/usr/bin/env bash
set -euo pipefail

mkdir -p storage/framework/{cache,sessions,views} storage/logs bootstrap/cache
chmod -R ug+rwx storage bootstrap/cache || true

# Map Railway MySQL plugin variables when present
if [[ -n "${MYSQLHOST:-}" ]]; then
  export DB_CONNECTION="${DB_CONNECTION:-mysql}"
  export DB_HOST="${DB_HOST:-$MYSQLHOST}"
  export DB_PORT="${DB_PORT:-${MYSQLPORT:-3306}}"
  export DB_DATABASE="${DB_DATABASE:-${MYSQLDATABASE:-railway}}"
  export DB_USERNAME="${DB_USERNAME:-${MYSQLUSER:-root}}"
  export DB_PASSWORD="${DB_PASSWORD:-${MYSQLPASSWORD:-}}"
fi

php artisan storage:link --force >/dev/null 2>&1 || true
php artisan migrate --force --no-interaction

# Permission catalog must exist BEFORE merchant access is granted.
# RolesSeeder is intentionally NOT re-run here: it unconditionally
# overwrites every role's permissions on every call, which would wipe
# out any edits made through the Roles & Permissions UI on each deploy.
# merchant:grant-full-access below only seeds roles the first time none
# exist yet (see GrantMerchantFullAccessCommand::ensurePermissionCatalogExists).
php artisan db:seed --class=PermissionsModulesSeeder --force --no-interaction || true
php artisan db:seed --class=PermissionsSeeder --force --no-interaction || true

MERCHANT_COUNT="$(php artisan tinker --execute="echo \\App\\Models\\Merchant::query()->count();" 2>/dev/null | tr -d '[:space:]' || echo 0)"
CHAMBER_COUNT="$(php artisan tinker --execute="echo \\App\\Models\\ColdStorageChamber::query()->count();" 2>/dev/null | tr -d '[:space:]' || echo 0)"

if [[ "${MERCHANT_COUNT}" == "0" ]]; then
  php artisan db:seed --class=CountriesSeeder --force --no-interaction || true
  php artisan db:seed --class=CitiesSeeder --force --no-interaction || true
  php artisan db:seed --class=MerchantsSeeder --force --no-interaction || true
fi

# Re-grant every boot so missing modules/permissions are healed after catalog seeds
php artisan merchant:grant-full-access info@evergreen.com --no-interaction || true

if [[ "${CHAMBER_COUNT}" == "0" ]]; then
  php artisan db:seed --class=ColdStorageDemoProductsSeeder --force --no-interaction || true
  php artisan db:seed --class=ColdStorageDemoFlowSeeder --force --no-interaction || true
fi

php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
# Filament: cache component discovery + Blade icons (otherwise rescanned on every request).
php artisan filament:optimize

# PHP's built-in server handles ONE request at a time by default, so every page,
# Livewire update, script, stylesheet and image queued behind each other (and
# behind other users). Run several workers instead; --no-reload is required for
# Laravel to honour PHP_CLI_SERVER_WORKERS.
export PHP_CLI_SERVER_WORKERS="${PHP_CLI_SERVER_WORKERS:-4}"
exec php artisan serve --host=0.0.0.0 --port="${PORT:-8080}" --no-reload
