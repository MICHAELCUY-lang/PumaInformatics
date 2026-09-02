# Production Checklist — informatics.president.ac.id

Work through this before announcing the site. Steps in brackets reference
[DEPLOYMENT.md](DEPLOYMENT.md).

## 1. Security & configuration
- [ ] `APP_ENV=production` and `APP_DEBUG=false`
- [ ] `APP_KEY` generated (`php artisan key:generate --force`)
- [ ] `APP_URL=https://informatics.president.ac.id`
- [ ] `.env` sits **outside** the document root, i.e. the subdomain points at `.../informatics/public` [§2]
- [ ] `ADMIN_PASSWORD` is a long random value, and changed after first login
- [ ] `DB_PASSWORD` set, database user is not the cPanel master user
- [ ] `SESSION_SECURE_COOKIE=true`, `SESSION_DOMAIN=informatics.president.ac.id`
- [ ] `TRUSTED_PROXIES` left **empty** unless a reverse proxy really is in front — trusting it blindly lets anyone spoof their IP and break vote/aspiration hashing
- [ ] AutoSSL certificate issued and HTTPS redirect active [§8]

## 2. Access control
- [ ] `php artisan db:seed --force` has run, so every permission exists [§6]
- [ ] Log in as Super Admin, confirm `/admin` loads
- [ ] Create one Editor and confirm they can reach Newsroom but **not** Users, Roles or Voting
- [ ] Confirm a non-Super-Admin cannot assign the Super Admin role on the user form
- [ ] Confirm a plain registered member hitting `/admin` gets a 403, not the panel

## 3. File storage
- [ ] `php artisan storage:link` succeeded
- [ ] `storage` and `bootstrap/cache` are writable (775)
- [ ] Upload an image in the article editor and confirm the URL resolves

## 4. Background work
- [ ] `schedule:run` cron installed, once per minute [§9]
- [ ] `queue:work --stop-when-empty` cron installed, once per minute [§9]
- [ ] An uploaded image gets its conversions within ~1 minute (proves the queue cron works)

## 5. Mail — voting depends on this
- [ ] SMTP credentials filled in and `MAIL_MAILER=smtp`
- [ ] Register a test account and confirm the verification email arrives
- [ ] Complete verification, then confirm that account can cast a vote

## 6. Voting dry run
- [ ] Create a session with status **Active** and a window covering today
- [ ] It appears at `/voting` marked LIVE
- [ ] A verified member can vote once; a second attempt is rejected
- [ ] Results stay hidden until the session is set to **Completed** with visibility **Public**
- [ ] A draft session is not reachable at its public URL

## 7. Front end
- [ ] `npm ci && npm run build` was run on the server and `public/build/manifest.json` exists [§5]
- [ ] Homepage renders with styling
- [ ] 404 / 403 / 419 / 429 / 500 pages render the custom views

## 8. Optimisation
- [ ] `php artisan config:cache route:cache view:cache event:cache` run **after** the final `.env` edit [§10]

## 9. SEO
- [ ] `/sitemap.xml` loads
- [ ] `/robots.txt` loads and its `Sitemap:` line points at the real domain
- [ ] Share a page on WhatsApp/LinkedIn and confirm the preview image appears

## 10. Backups
- [ ] cPanel scheduled backup covers the database and `storage/app/public` [§14]
- [ ] One backup has been downloaded off-server and test-restored
