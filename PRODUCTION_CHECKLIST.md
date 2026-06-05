# Production Deployment Checklist

Before announcing the PUMA IT platform as live, verify the following items:

## 1. Security & Configuration
- [ ] `APP_ENV` is set to `production`.
- [ ] `APP_DEBUG` is set to `false`.
- [ ] `APP_KEY` has been generated and is securely stored.
- [ ] `DB_PASSWORD` and all third-party API keys are correct for the production environment.
- [ ] SSL certificate is installed and active (HTTPS enforced).

## 2. File Storage
- [ ] Storage symlink exists (`php artisan storage:link`).
- [ ] `storage` and `bootstrap/cache` directories are writable by the web server.

## 3. Optimizations (Run after deployment)
- [ ] Config cached: `php artisan config:cache`
- [ ] Routes cached: `php artisan route:cache`
- [ ] Views cached: `php artisan view:cache`
- [ ] Events cached: `php artisan event:cache`

## 4. Background Services
- [ ] Scheduler is running via cron (`* * * * * php artisan schedule:run`).
- [ ] Redis is active and accessible.
- [ ] Queue workers (or Horizon) are active and managed by Supervisor.

## 5. SEO & Indexing
- [ ] `sitemap.xml` is accessible at `/sitemap.xml`.
- [ ] `robots.txt` is accessible and properly configured.
- [ ] Meta tags, Canonical tags, and JSON-LD schema exist on public pages.

## 6. Access & Testing
- [ ] Admin dashboard is accessible only to authorized users.
- [ ] Voting system accepts votes properly and rate limits are enforced.
- [ ] Uploads (Media Library) save correctly and URLs resolve successfully.
- [ ] Error pages (404, 500, etc.) display the custom cinematic views.

## 7. Backup Strategy
- [ ] Database backup routine is established (e.g., daily cron job via spatie/laravel-backup).
- [ ] Media uploads (`storage/app/public`) are included in backup routines.
