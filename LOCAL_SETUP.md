# Local Development Setup (Windows)

You do **not** need any of this to deploy — the server handles Composer, Node and
Artisan on its own (see [DEPLOYMENT.md](DEPLOYMENT.md)). Follow this only to
develop and run the test suite on your own machine.

---

## 1. Install Laragon

Laragon bundles PHP, MySQL, Composer and Node in one installer, which is why it
is the shortest path on Windows.

1. Download **Laragon Full** from https://laragon.org/download/
2. Install to the default `C:\laragon`
3. Start Laragon → **Start All** (Apache/Nginx + MySQL come up)

### Set PHP to 8.2 or newer

The project requires PHP `^8.2`. Laragon may ship an older or newer build:

- Right-click the Laragon window → **PHP → Version** → pick 8.2 or 8.3
- If the version you need is absent: **Tools → Quick add → PHP**

Verify in Laragon's **Terminal** (right-click → Terminal, not plain CMD — it puts
Laragon's binaries on the PATH):

```bash
php -v && composer --version && node -v
```

All three must respond. If `php` is not found, Laragon's `bin` folders are not on
your PATH; reopen the terminal from inside Laragon.

### Required extensions

Laragon enables these by default, but confirm:

```bash
php -m
```

Look for `bcmath`, `curl`, `exif`, `fileinfo`, `gd`, `intl`, `mbstring`,
`openssl`, `pdo_mysql`, `zip`. Missing ones are enabled by uncommenting the
matching `extension=` line in `C:\laragon\bin\php\<version>\php.ini`, then
restarting Laragon.

---

## 2. Deal with the TLS-intercepting network first

On the network this project was last worked on, both npm and Composer fail with:

```
UNABLE_TO_VERIFY_LEAF_SIGNATURE
unable to verify the first certificate
```

That is a proxy re-signing HTTPS with a certificate authority your tools do not
trust. It is not a bug in npm or Composer, and it will break `composer install`
exactly the same way it broke `npm install`. Pick one:

### Option A — trust the proxy's CA (correct fix)

1. In Chrome/Edge, open https://registry.npmjs.org
2. Click the padlock → **Connection is secure** → **Certificate is valid**
3. **Details** tab → select the **top-most** entry in the certification path
   (the root, not the leaf) → **Export** → *Base-64 encoded X.509 (.CER)*
4. Save as `C:\certs\proxy-ca.pem`

Then point every tool at it:

```bash
npm config set cafile "C:/certs/proxy-ca.pem"
```

```bash
composer config -g cafile "C:/certs/proxy-ca.pem"
```

```bash
setx NODE_EXTRA_CA_CERTS "C:\certs\proxy-ca.pem"
```

Reopen the terminal after `setx`.

### Option B — use a different network

Tethering to a phone hotspot avoids the proxy entirely. For a one-off
`composer install` / `npm ci` this is often the fastest route.

### Option C — disable certificate verification

```bash
npm config set strict-ssl false
```

This makes your package downloads trivially tamperable, and packages execute
install scripts on your machine. If you use it, treat it as temporary: run the
install, then `npm config set strict-ssl true` again. Prefer A or B.

---

## 3. Project setup

From the project folder in Laragon's terminal:

```bash
composer install
```

```bash
cp .env.example .env && php artisan key:generate
```

Create the database — Laragon's MySQL root has an empty password by default:

```bash
mysql -u root -e "CREATE DATABASE IF NOT EXISTS puma_it CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

Edit `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=puma_it
DB_USERNAME=root
DB_PASSWORD=

ADMIN_EMAIL=admin@puma.it
ADMIN_PASSWORD=password
```

Then:

```bash
php artisan migrate --seed
```

```bash
npm install && npm run build
```

`migrate --seed` creates the permission matrix, the Super Admin, and demo
content. Demo content is skipped automatically when `APP_ENV=production`.

---

## 4. Run it

```bash
composer dev
```

That runs the dev server, queue worker, log tailer and Vite together. The site
comes up at http://localhost:8000.

Prefer separate windows? `php artisan serve`, `php artisan queue:listen`, and
`npm run dev`.

> Run the queue worker whenever you upload images — media conversions are
> queued, so without a worker thumbnails never appear.

Service connectivity check (local only): http://localhost:8000/dev/health

---

## 5. Tests

```bash
php artisan test
```

Tests run against an in-memory SQLite database (`phpunit.xml`), so they do not
touch your MySQL data and need no extra setup.

Run one file while working on it:

```bash
php artisan test tests/Feature/VotingTest.php
```

---

## 6. Before you push a front-end change

Nothing to do — `public/build/` is gitignored and rebuilt on the server. Just
make sure `npm run build` succeeds locally so you know the change compiles.
