# cPanel deploy — EverGreen Cold Storage

## Host map

| Host | Role |
|------|------|
| `https://evergreencold.store` | Landing / marketing only |
| `https://app.evergreencold.store` | Full Laravel CRM |
| Existing primary domain `public_html` | WordPress (`techshaker.com`) — **do not touch** |

Do **not** create a separate `www.evergreencold.store` domain on this host (cPanel rejects `www.` and it can break the main domain).

GitHub repo: `https://github.com/hamzahanifqae-cell/EverGreen-Cold-Storage.git`  
Branch: `main`

### Discovered cPanel account (`techshakerco99`)

| Item | Value |
|------|--------|
| Home | `/home/techshakerco99` |
| Primary / WordPress | `/home/techshakerco99/public_html` (`wp-config.php`, `techshaker.com`) |
| Existing Laravel (pattern to copy) | `/home/techshakerco99/public_html/flowdeskpos.com` |
| Other addon folders under `public_html` | `toolmetric.online`, `time-atlas.online`, `texthelper.online`, `gamenews.online`, `api.toolmetric.online` |
| PHP for EverGreen | `/opt/cpanel/ea-php84/root/usr/bin/php` (8.4) — also have 8.2/8.3 |
| Composer | `/home/techshakerco99/composer.phar` (not on PATH as `composer`) |
| EverGreen CRM path | `/home/techshakerco99/public_html/app.evergreencold.store` |
| EverGreen landing path | `/home/techshakerco99/public_html/evergreencold.store` |

---

## 1. Connect GitHub to cPanel

### A. Domains (already done on this account)

- `evergreencold.store` → `/home/techshakerco99/public_html/evergreencold.store` (landing)
- `app.evergreencold.store` → `/home/techshakerco99/public_html/app.evergreencold.store` (CRM — Git clone here)

Leave the main hosting `public_html` WordPress site alone.

### B. GitHub auth

**Option 1 — HTTPS + Personal Access Token (simplest)**

1. GitHub → Settings → Developer settings → Personal access tokens.
2. Create a classic token with `repo` scope.
3. Copy the token once (you will paste it as the Git password in cPanel).

**Option 2 — Deploy key**

1. cPanel → **SSH Access** / **Manage SSH Keys** → generate key.
2. GitHub repo → Settings → Deploy keys → add the public key (read-only is enough).
3. Use the SSH clone URL in Git Version Control.

### C. Clone with Git Version Control

1. cPanel → **Git Version Control** → **Create**.
2. Clone URL: `https://github.com/hamzahanifqae-cell/EverGreen-Cold-Storage.git`
3. Repository Path: the **empty** CRM folder `public_html/app.evergreencold.store`.
4. Repository Name: `EverGreen-Cold-Storage` (any label).
5. Branch: `main`.
6. When asked for credentials: GitHub username + **PAT** as password (Option 1).

Do **not** Deploy yet until `.env` and paths are confirmed.

### D. Apex domain

After the CRM clone works, point `evergreencold.store` document root at the same app’s `public` directory via symlink (exact commands after discovery), so one Laravel install serves both hosts.

---

## 2. Discovery commands (run in cPanel Terminal)

Paste this whole block, then paste the output back into chat:

```bash
echo "=== WHO / HOME ==="
whoami
echo "HOME=$HOME"
pwd

echo "=== PHP ==="
command -v php; php -v 2>/dev/null | head -1
ls /opt/cpanel/ea-php*/root/usr/bin/php 2>/dev/null
for b in /opt/cpanel/ea-php84/root/usr/bin/php /opt/cpanel/ea-php83/root/usr/bin/php /opt/cpanel/ea-php82/root/usr/bin/php; do
  [ -x "$b" ] && echo "OK $b -> $($b -v 2>&1 | head -1)"
done

echo "=== COMPOSER ==="
command -v composer; composer -V 2>/dev/null || true

echo "=== HOME / PUBLIC_HTML ==="
ls -la "$HOME" | head -50
ls -la "$HOME/public_html" | head -40

echo "=== EVERGREEN PATHS ==="
ls -la "$HOME/public_html/evergreencold.store" 2>/dev/null | head -20
ls -la "$HOME/public_html/app.evergreencold.store" 2>/dev/null | head -20
ls -la "$HOME/evergreencold.store" 2>/dev/null | head -20
ls -la "$HOME/app.evergreencold.store" 2>/dev/null | head -20

echo "=== ARTISAN / GIT ==="
find "$HOME" -maxdepth 4 -name 'artisan' 2>/dev/null
find "$HOME" -maxdepth 4 -type d -name '.git' 2>/dev/null | head -20

echo "=== WP CHECK ==="
[ -f "$HOME/public_html/wp-config.php" ] && echo "WordPress detected in public_html" || echo "No wp-config in public_html root"

echo "=== MYSQL ==="
php -r 'echo "pdo_mysql=".(extension_loaded("pdo_mysql")?"yes":"no").PHP_EOL;'
mysql --version 2>/dev/null || true
```

Or upload/run [`scripts/cpanel-discover.sh`](../scripts/cpanel-discover.sh).

---

## 3. Production `.env` (on server only — never commit)

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://app.evergreencold.store
LANDING_DOMAIN=evergreencold.store
APP_DOMAIN=app.evergreencold.store
LANDING_URL=https://evergreencold.store
SESSION_DOMAIN=.evergreencold.store
SESSION_SECURE_COOKIE=true
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=...
DB_USERNAME=...
DB_PASSWORD=...
```

Then (with the PHP binary from discovery):

```bash
cd "$HOME/public_html/app.evergreencold.store"
/opt/cpanel/ea-php84/root/usr/bin/php "$HOME/composer.phar" install --no-dev --optimize-autoloader
/opt/cpanel/ea-php84/root/usr/bin/php artisan key:generate
/opt/cpanel/ea-php84/root/usr/bin/php artisan migrate --force
/opt/cpanel/ea-php84/root/usr/bin/php artisan storage:link
/opt/cpanel/ea-php84/root/usr/bin/php artisan config:cache
/opt/cpanel/ea-php84/root/usr/bin/php artisan route:cache
/opt/cpanel/ea-php84/root/usr/bin/php artisan view:cache
```

Enable AutoSSL for both hosts.
