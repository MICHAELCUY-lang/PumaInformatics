# PUMA IT Platform - Deployment Guide

This document outlines the steps required to deploy the PUMA IT platform to a production environment.

## 1. Server Requirements

- PHP 8.2 or higher
- Composer
- MySQL 8.0+ or PostgreSQL
- Redis (for caching and queues)
- Node.js & NPM (for asset compilation if not pre-built)
- Supervisor (for Horizon/Queue workers)

## 2. Environment Setup

1. Clone the repository to the production server.
2. Run `composer install --optimize-autoloader --no-dev`.
3. Copy `.env.example` to `.env` and configure the environment variables:
   ```env
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://your-production-url.com

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=puma_it
   DB_USERNAME=your_db_user
   DB_PASSWORD=your_db_password

   CACHE_STORE=redis
   QUEUE_CONNECTION=redis
   SESSION_DRIVER=redis

   REDIS_CLIENT=phpredis
   REDIS_HOST=127.0.0.1
   REDIS_PASSWORD=null
   REDIS_PORT=6379
   ```
4. Generate the application key:
   ```bash
   php artisan key:generate
   ```

## 3. Storage and Permissions

Ensure the `storage` and `bootstrap/cache` directories are writable by the web server (e.g., `www-data`).
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

Link the storage directory to public:
```bash
php artisan storage:link
```

## 4. Database Migration

Run the database migrations (this will also seed roles/permissions if configured in DatabaseSeeder):
```bash
php artisan migrate --force
```

## 5. Asset Compilation

If you are not deploying pre-built assets, build them on the server:
```bash
npm install
npm run build
```

## 6. Performance Optimization

Run Laravel's optimization commands to cache routes, views, configs, and events:
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

## 7. Queue and Scheduler Setup

### Scheduler

Add the following Cron entry to your server to run the Laravel scheduler every minute:
```cron
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

### Queue Worker (Horizon / Supervisor)

If using Laravel Horizon, install and configure it, then set up a Supervisor process to keep it running.

**Example Supervisor Config (`/etc/supervisor/conf.d/horizon.conf`):**
```ini
[program:horizon]
process_name=%(program_name)s
command=php /path-to-your-project/artisan horizon
autostart=true
autorestart=true
user=www-data
redirect_stderr=true
stdout_logfile=/path-to-your-project/storage/logs/horizon.log
stopwaitsecs=3600
```
Update Supervisor:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start horizon
```

## 8. Rollback Process

If a deployment fails:
1. Revert to the previous code version (via Git or symlink).
2. Run `php artisan migrate:rollback --step=1` if migrations were part of the failure.
3. Run `composer install --optimize-autoloader --no-dev`.
4. Re-run optimization commands: `php artisan optimize`.
5. Restart the queue workers: `php artisan queue:restart`.
