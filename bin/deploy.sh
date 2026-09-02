#!/bin/bash
#
# Deploy PUMA Informatics on the cPanel host.
#
#   bash ~/informatics/bin/deploy.sh
#
# Encodes the four things about this host that are easy to get wrong and that
# fail silently when missed. See DOCUMENTATION.md for why each one exists.
#
#   1. /usr/local/bin/php is PHP 7.4 here — everything must use ea-php82.
#   2. proc_open is disabled, so Composer's post-autoload-dump script fails and
#      `artisan package:discover` has to be run by hand or the app boots with no
#      package providers registered.
#   3. The document root is public_html, not the app's public/ directory. The
#      compiled assets and static files have to be copied across, and the
#      docroot .htaccess must keep cPanel's PHP handler block.
#   4. esbuild and Tailwind size their thread pools from the host's 80 CPUs and
#      hit the account's process limit; the build has to be pinned to 1 thread.

set -euo pipefail

APP=/home/infm2327/informatics
DOCROOT=/home/infm2327/public_html
PHP=/usr/local/bin/ea-php82
NODE_BIN=/opt/alt/alt-nodejs20/root/usr/bin

step() { printf '\n=== %s ===\n' "$1"; }

cd "$APP"

step "1/8 Pull"
git pull --ff-only origin master

step "2/8 Composer"
"$PHP" -d memory_limit=-1 "$HOME/bin/composer" install --no-dev --optimize-autoloader --no-interaction

step "3/8 Package discovery (Composer cannot do this without proc_open)"
"$PHP" artisan package:discover

step "4/8 Front-end build (single-threaded)"
export PATH="$NODE_BIN:$PATH"
npm ci --no-audit --no-fund
GOMAXPROCS=1 RAYON_NUM_THREADS=1 UV_THREADPOOL_SIZE=1 npm run build
test -f public/build/manifest.json || { echo "manifest missing, aborting"; exit 1; }

step "5/8 Publish public/ into the document root"
rm -rf "$DOCROOT/build"
cp -r public/build "$DOCROOT/build"
cp public/robots.txt public/logo.png "$DOCROOT/"
# Rebuild the docroot .htaccess from the repo, then re-append cPanel's handler
# block. Dropping that block silently drops the site back to PHP 7.4.
HANDLER=$(sed -n '/# php -- BEGIN cPanel-generated handler/,/# php -- END cPanel-generated handler/p' "$DOCROOT/.htaccess" || true)
cp public/.htaccess "$DOCROOT/.htaccess"
if [ -n "$HANDLER" ]; then
    printf '%s\n' "$HANDLER" >> "$DOCROOT/.htaccess"
else
    echo "WARNING: no cPanel PHP handler block found — check MultiPHP Manager"
fi
grep -q 'ea-php82' "$DOCROOT/.htaccess" || echo "WARNING: docroot .htaccess has no ea-php82 handler"

step "6/8 Migrations"
"$PHP" artisan migrate --force

step "7/8 Permission matrix (idempotent; rolls out new permissions)"
"$PHP" artisan db:seed --class=RolesAndPermissionsSeeder --force

step "8/8 Optimise"
"$PHP" artisan config:clear
"$PHP" artisan config:cache
"$PHP" artisan route:cache
"$PHP" artisan view:cache
"$PHP" artisan event:cache

printf '\nDeployed %s\n' "$(git log --oneline -1)"
printf 'Verify: https://informatics.president.ac.id and check that the homepage is styled.\n'
