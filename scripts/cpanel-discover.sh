#!/usr/bin/env bash
# Run in cPanel → Terminal (or SSH). Paste the full output back to the deploy chat.
set -euo pipefail

echo "=== WHO / HOME ==="
whoami
echo "HOME=$HOME"
pwd

echo "=== PHP ==="
command -v php || true
php -v 2>/dev/null | head -1 || true
ls /opt/cpanel/ea-php*/root/usr/bin/php 2>/dev/null || true
for b in /opt/cpanel/ea-php84/root/usr/bin/php /opt/cpanel/ea-php83/root/usr/bin/php /opt/cpanel/ea-php82/root/usr/bin/php; do
  if [ -x "$b" ]; then
    echo "OK $b -> $($b -v 2>&1 | head -1)"
  fi
done

echo "=== COMPOSER ==="
command -v composer || true
composer -V 2>/dev/null || true

echo "=== HOME TOP ==="
ls -la "$HOME" | head -60

echo "=== PUBLIC_HTML ==="
ls -la "$HOME/public_html" | head -50

echo "=== EVERGREEN PATHS ==="
ls -la "$HOME/public_html/evergreencold.store" 2>/dev/null | head -20 || echo "missing: public_html/evergreencold.store"
ls -la "$HOME/public_html/www.evergreencold.store" 2>/dev/null | head -20 || echo "missing: public_html/www.evergreencold.store"
ls -la "$HOME/evergreencold.store" 2>/dev/null | head -20 || echo "missing: ~/evergreencold.store"
ls -la "$HOME/www.evergreencold.store" 2>/dev/null | head -20 || echo "missing: ~/www.evergreencold.store"

echo "=== ARTISAN / GIT CLONES ==="
find "$HOME" -maxdepth 4 -name 'artisan' 2>/dev/null || true
find "$HOME" -maxdepth 4 -type d -name 'EverGreen-Cold-Storage' 2>/dev/null || true
find "$HOME" -maxdepth 4 -type d -name '.git' 2>/dev/null | head -20 || true

echo "=== WP CHECK (do not touch) ==="
if [ -f "$HOME/public_html/wp-config.php" ]; then
  echo "WordPress detected in public_html"
else
  echo "No wp-config in public_html root"
fi

echo "=== MYSQL ==="
php -r 'echo "pdo_mysql=".(extension_loaded("pdo_mysql")?"yes":"no").PHP_EOL;' 2>/dev/null || true
mysql --version 2>/dev/null || true

echo "=== NODE (optional) ==="
command -v node || true
node -v 2>/dev/null || true
command -v npm || true
npm -v 2>/dev/null || true

echo "=== DONE — paste everything above back into the chat ==="
