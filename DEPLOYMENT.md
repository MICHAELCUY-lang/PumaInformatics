# Deployment — cPanel · informatics.president.ac.id

Target environment: shared cPanel hosting, Apache, MySQL, no root access, no
Supervisor. Every step below is doable from the cPanel UI plus **Terminal**.

If your plan has no Terminal, ask the hosting admin to enable it — Composer and
`php artisan` are required. Everything else can be done through File Manager.

---

## 1. Requirements checklist

Set in **cPanel → Select PHP Version**:

| Item | Value |
|---|---|
| PHP | 8.2 or 8.3 |
| Extensions | `bcmath` `ctype` `curl` `dom` `exif` `fileinfo` `gd` `intl` `mbstring` `openssl` `pdo_mysql` `tokenizer` `xml` `zip` |
| `memory_limit` | 256M or higher (Composer needs it) |
| `upload_max_filesize` / `post_max_size` | at least 12M — the media uploader accepts 10MB files |

`gd` is enough; `imagick` is optional. Redis is usually absent on shared plans —
the shipped config uses the database driver, which needs nothing extra.

---

## 2. Directory layout

> **Already deployed. For routine updates just run:**
>
> ```bash
> bash ~/informatics/bin/deploy.sh
> ```
>
> That script encodes every host quirk described below. Read the rest of this
> document when something breaks or when rebuilding the account from scratch.

Account facts this guide is written against:

| | |
|---|---|
| cPanel user | `infm2327` |
| Home directory | `/home/infm2327` |
| Primary domain | `informatics.president.ac.id` (**not** a subdomain) |
| Shared IP | `202.10.43.181` |
| SSL | already active |

Because the site is the account's **primary domain**, its document root is fixed
at `/home/infm2327/public_html`. Symlinking `public_html` to the app's `public/`
is not an option either — this host disables PHP's `symlink()`. So the layout in
use is the classic shared-hosting split: the application lives above the web
root, and `public_html` holds a small bridge plus the static assets.

```
/home/infm2327/
├── informatics/            ← the repository, above the web root
│   ├── app/ config/ routes/ vendor/
│   ├── .env                ← unreachable from the web (mode 600)
│   ├── bin/deploy.sh
│   └── public/build/       ← build output, COPIED to the docroot on deploy
└── public_html/            ← DOCUMENT ROOT
    ├── index.php           ← bridge: requires /home/infm2327/informatics
    ├── .htaccess           ← repo version + cPanel's PHP handler block
    ├── build/              ← copy of informatics/public/build
    ├── robots.txt logo.png ← copies of informatics/public/*
    ├── storage/            ← PUBLIC_DISK_ROOT, where uploads are written
    └── .well-known/        ← leave alone, AutoSSL uses it
```

Two consequences that cause real, confusing breakage if forgotten:

**The docroot is not the app's `public/`.** Blade's `@vite` emits `/build/...`
URLs, which Apache resolves inside `public_html`. Rebuilding assets in
`informatics/public/build` without copying them across leaves the site serving
the *previous* build. The symptom is subtle: unchanged files keep working
because Vite's content hashes are unchanged, so typically only the CSS 404s
while the JS still loads.

**The docroot `.htaccess` must keep cPanel's handler block.** That block is what
selects PHP 8.2; without it the site falls back to PHP 7.4 and the app will not
boot. `bin/deploy.sh` re-appends it automatically after replacing the file.

`index.php` is also the rollback switch — see §13.

DNS and SSL are already done: the domain resolves to this account and the
certificate is active.

---

## 3. Get the code onto the server

**Option A — cPanel Git Version Control** (recommended, makes updates one click):

1. cPanel → Git™ Version Control → Create
2. Clone URL: `https://github.com/MICHAELCUY-lang/PumaInformatics.git`
3. Repository Path: `/home/infm2327/informatics`

**Option B — upload a zip** via File Manager and extract to `/home/infm2327/informatics`.

Either way, `vendor/` and `node_modules/` are not in the repo; step 4 handles
`vendor/`, and step 5 explains the front-end assets.

---

## 4. Install PHP dependencies

In Terminal:

```bash
cd ~/informatics && php -d memory_limit=-1 /usr/local/bin/composer install --no-dev --optimize-autoloader
```

If `composer` is not on the path, download it once:

```bash
cd ~/informatics && curl -sS https://getcomposer.org/installer | php && php -d memory_limit=-1 composer.phar install --no-dev --optimize-autoloader
```

---

## 5. Front-end assets — built on the server

Blade calls `@vite`, which needs `public/build/manifest.json`. Without it every
page throws a Vite manifest exception, so this step is not optional.

`public/build/` is gitignored and generated on the server.

### 5a. Make Node available

cPanel → **Setup Node.js App** → Create Application:

- Node.js version: 20.x (or the highest offered)
- Application root: `informatics`
- Application URL: leave as is — this app is never actually served by Passenger,
  we only want the Node toolchain and its virtualenv
- Application startup file: leave blank

Save. cPanel prints an "Enter to the virtual environment" command near the top
of the page that looks like this:

```
source /home/infm2327/nodevenv/informatics/20/bin/activate && cd /home/infm2327/informatics
```

Copy that exact line — the numbers differ per account.

### 5b. Build

In Terminal, paste the activation line, then:

```bash
npm ci && npm run build
```

Confirm the output exists:

```bash
ls -la ~/informatics/public/build/manifest.json
```

`npm ci` needs `package-lock.json`, which is in the repo. If it complains about
being out of sync with `package.json`, use `npm install` instead.

Node is only needed at build time. Once `public/build/` exists, the running site
is pure PHP — you can even stop the Node.js application afterwards.

### 5c. Repeat when the front end changes

Any deploy that touches `resources/css`, `resources/js`, `tailwind.config.js` or
adds Blade classes needs 5b re-run. Everything else does not.

> **Building on your laptop instead?** That works too — run `npm ci && npm run
> build` locally and upload `public/build/` via File Manager. You do not need
> PHP or Composer locally for this; Node alone is enough.

---

## 6. Database

1. cPanel → MySQL® Databases → create a database and a user, then grant the user
   **ALL PRIVILEGES** on it. cPanel prefixes both with the account name, so you
   end up with something like `infm2327_informatics` / `infm2327_app`.
   The account already shows one database — check whether it is already the right
   one before creating a second, and do not point `.env` at a database that
   belongs to something else.
2. cPanel → File Manager → copy `.env.production.example` to `.env` (enable
   "Show Hidden Files" first) and fill in every blank.

Then:

```bash
cd ~/informatics && php artisan key:generate --force && php artisan migrate --force && php artisan db:seed --force
```

`db:seed` runs `RolesAndPermissionsSeeder` (creates every permission the admin
panel checks) and `AdminUserSeeder` (creates the Super Admin from `ADMIN_EMAIL`
/ `ADMIN_PASSWORD`). It refuses to run in production if `ADMIN_PASSWORD` is
blank. Demo content is skipped automatically in production.

Both seeders are idempotent — safe to re-run after adding a new permission.

---

## 7. Storage — `storage:link` does not work on this host

This account's PHP has `symlink` in `disable_functions`, so
`php artisan storage:link` can never succeed. Verify for yourself with:

```bash
php -r 'echo ini_get("disable_functions"), PHP_EOL;'
```

Instead, point the public disk directly at a directory inside the web root.
`config/filesystems.php` reads `PUBLIC_DISK_ROOT` for exactly this:

```env
PUBLIC_DISK_ROOT=/home/infm2327/public_html/storage
```

Create it and lock down execution — anything in there is web-served, so a file
that somehow lands with a `.php` extension must not be runnable:

```bash
mkdir -p ~/public_html/storage
printf 'php_flag engine off\nOptions -Indexes -ExecCGI\nRemoveHandler .php .phtml .php3 .php4 .php5 .php7 .php8\nAddType text/plain .php .phtml\n' > ~/public_html/storage/.htaccess
chmod -R 755 ~/informatics/storage ~/informatics/bootstrap/cache
```

Migrating uploads from an older deployment that copied the directory by hand:

```bash
cp -rn ~/puma-informatics/storage/app/public/. ~/public_html/storage/ 2>/dev/null; ls ~/public_html/storage | head
```

Do **not** ask support to enable `symlink` — it is disabled for good reason on
shared hosting, and the approach above needs no privilege at all.

---

## 8. SSL

Already done — the certificate for `informatics.president.ac.id` is active on
this account. Just confirm that `.env` has `APP_URL=https://...` and
`SESSION_SECURE_COOKIE=true`; the HTTPS redirect lives in `public/.htaccess`.

If the certificate ever lapses, cPanel → SSL/TLS Status → run **AutoSSL**.

---

## 9. Cron — scheduler and queue

cPanel → Cron Jobs. Add **both** entries at "Once Per Minute" (`* * * * *`):

```
/usr/local/bin/php /home/infm2327/informatics/artisan schedule:run >/dev/null 2>&1
```

```
/usr/local/bin/php /home/infm2327/informatics/artisan queue:work --stop-when-empty --max-time=55 --tries=3 >/dev/null 2>&1
```

The second one replaces Supervisor: shared hosting kills long-running daemons,
so instead a fresh worker starts each minute, drains the queue, and exits.
Without it, **image conversions never run** — `QUEUE_CONVERSIONS_BY_DEFAULT` is
true, so uploaded pictures would stay unprocessed.

Confirm your PHP CLI path first with `which php` — on some servers it is
`/usr/local/bin/ea-php82` instead.

---

## 10. Optimise

```bash
cd ~/informatics && php artisan config:cache && php artisan route:cache && php artisan view:cache && php artisan event:cache
```

Re-run these after **every** `.env` or route change — a cached config ignores
later edits to `.env`, which is the single most common "why isn't my change
showing" problem on cPanel.

---

## 11. Post-deploy verification

| Check | Expected |
|---|---|
| `https://informatics.president.ac.id` | homepage renders **with styling** (unstyled or a Vite manifest error means step 5 was skipped) |
| `/login` | Breeze login form, no browser popup |
| Log in as Super Admin | lands on `/admin` |
| `/admin/voting-sessions` → create, status **Active**, dates around today | session appears at `/voting` marked LIVE |
| Cast a vote as a verified member | "Your vote has been securely recorded." |
| Vote again | rejected as a double vote |
| Upload an image in the article editor | thumbnail appears within a minute (proves the queue cron works) |
| Register a new account | verification email arrives (proves SMTP works) |
| `/sitemap.xml`, `/robots.txt` | both load |
| `/up` | `200` health check |
| `https://informatics.president.ac.id/../.env` and `/storage/logs` | not reachable |

---

## 12. Updating later

With cPanel Git Version Control, click **Update from Remote**, then:

```bash
cd ~/informatics && php -d memory_limit=-1 composer install --no-dev --optimize-autoloader && php artisan migrate --force && php artisan db:seed --force && php artisan optimize
```

`db:seed` here is what rolls out newly added permissions.

---

## 13. Rollback

### Back to the pre-cutover deployment (fastest, one file)

The previous application still sits untouched in `~/puma-informatics`, and the
bridge that selects between them is a single file:

```bash
cp ~/public_html/index.php.bak-old-deployment ~/public_html/index.php
```

That is the whole rollback — the old tree has its own `.env` and `vendor/`.
Note the old build assets are gone from `public_html/build`, so the old site
would render unstyled; restore `~/public_html/.htaccess.bak-old-deployment` too
if you need it exactly as it was. Keep `~/puma-informatics` until you are
confident; delete it only afterwards.

### Back one commit on the current deployment

```bash
cd ~/informatics && git log --oneline -5
```

```bash
cd ~/informatics && git reset --hard <commit> && bash bin/deploy.sh
```

Only add `php artisan migrate:rollback --step=1` if a migration was the problem;
the migrations here are additive, so that is rarely what you want.

---

## 14. Backups

cPanel → Backup → schedule a full account backup, and separately verify that
these are included:

- the MySQL database
- `storage/app/public` (every uploaded image and aspiration attachment)
- `.env` (store it in a password manager, not in the backup archive alone)

Download a copy off-server at least monthly. A cPanel account backup that only
lives on the same cPanel account is not a backup.
