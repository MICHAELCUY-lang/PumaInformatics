# PUMA IT Website - Technical Documentation

This document maintains a living record of architectural decisions, package installations, and environment requirements.

## Core Packages

### Laravel Breeze
- **Purpose**: Provides lightweight authentication scaffolding (Login, Registration, Password Reset, Email Verification).
- **Why**: Keeps authentication simple, customizable, and avoids the heavy abstraction of Jetstream since we are building a custom admin panel.
- **Config Changes**: Default routes installed.

### Spatie Laravel Permission
- **Purpose**: Role and Permission management.
- **Why**: Standard for Laravel RBAC. Allows assigning permissions to roles and roles to users for fine-grained access control in the admin panel.
- **Config Changes**: Published `config/permission.php` and migration.

### Spatie Laravel Activitylog
- **Purpose**: Logs user activities and model changes.
- **Why**: Required for tracking who did what (e.g., in the voting system and CMS). Crucial for accountability.
- **Config Changes**: Published `config/activitylog.php` and migration.

### Spatie Laravel Medialibrary
- **Purpose**: Manages file uploads and associates them with models.
- **Why**: Handles responsive images, optimizes files, and links media strictly to Eloquent models without manual storage logic.
- **Config Changes**: Published `config/media-library.php` and migration.

### Larastan / PHPStan
- **Purpose**: Static analysis tool for finding errors without running the code.
- **Why**: Enforces strict typing and prevents architectural mistakes early.
- **Config Changes**: Run via `vendor/bin/phpstan analyse`.

### Laravel Debugbar
- **Purpose**: In-browser developer toolbar.
- **Why**: Essential for catching N+1 queries, monitoring memory, and debugging views.
- **Config Changes**: Dev environment only.

### Laravel IDE Helper
- **Purpose**: Generates helper files that enable IDEs to provide accurate autocompletion.
- **Why**: Improves developer experience and reduces typos when working with facades and models.

## Architectural Decisions

- **Strict Models**: Enabled in `AppServiceProvider` for non-production environments to prevent lazy loading and silent attribute discarding.
- **Database**: Using MySQL to ensure production parity (strict mode, indexing, JSON columns).
- **Service/Repository Pattern**: Established `BaseService` and `BaseRepository` to decouple business logic from controllers.

## Environment Verification

To verify that your local environment is correctly configured to run this project:

1. **Start Services**: Ensure MySQL (port 3306) and Redis (port 6379) are running.
2. **Database Verification**: Run `php artisan migrate:status` to ensure database connectivity.
3. **Health Check**: Access `http://localhost/dev/health` (or your local domain equivalent) to see a JSON payload of your current service connectivity status.
4. **Queue Validation**: Ensure `QUEUE_CONNECTION` handles your background tasks locally (currently set to `database` fallback due to missing Redis PHP extension on Windows).
