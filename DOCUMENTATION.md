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
- **One vocabulary per state machine**: `App\Models\VotingSession::STATUSES` and
  `::VISIBILITIES` are the only accepted values for a voting session's state.
  Admin forms validate against those constants, and `isOpenForVoting()`,
  `isUpcoming()`, `hasFinished()` and `resultsVisibleTo()` are the only places
  that interpret them. Do not compare `status` to a bare string anywhere else —
  three competing vocabularies previously made it impossible to cast a vote.
- **Permissions are seeded, not assumed**: `RolesAndPermissionsSeeder` defines
  the whole matrix and is idempotent. Every `authorize()` / `can()` string in
  `app/` must exist in its `PERMISSIONS` array, otherwise the check silently
  fails for everyone except Super Admin (who bypasses via `Gate::before`).
- **Three-layer authorization**: role gate on the admin route group →
  `permission:` middleware per module in `routes/web.php` → `authorize()` in the
  FormRequest. The middleware layer exists because several controllers have no
  in-method authorization on `destroy`.
- **Privilege escalation guards**: `AssignsRolesSafely` and
  `GrantsPermissionsSafely` (in `app/Http/Requests/Admin/Concerns`) stop an
  account holding `manage.users` / `manage.roles` from granting itself Super
  Admin or a permission it does not already hold.
- **Proxy trust is opt-in**: `TRUSTED_PROXIES` defaults to empty. Trusting
  forwarded headers when no proxy is present would let any client spoof
  `X-Forwarded-For`, defeating the vote and aspiration IP hashing and every rate
  limiter.

## Known Environment Constraints

- **No Redis on shared cPanel**: cache, session and queue all default to the
  database driver. `CacheService` detects the missing tag support and switches to
  versioned cache keys, so tag invalidation still works correctly.
- **Node on cPanel is build-time only**: the account has "Setup Node.js App", so
  Vite assets are built on the server inside its Node virtualenv and
  `public/build/` stays gitignored. Node is not needed to serve the site. See
  DEPLOYMENT.md §5.
- **No Supervisor on cPanel**: the queue runs as a per-minute cron invoking
  `queue:work --stop-when-empty`. Media conversions depend on it.
- **Email verification gates voting**: `User` implements `MustVerifyEmail`, and
  `VotingController::store` requires a verified address. Working SMTP is
  therefore a hard prerequisite for an election, not a nice-to-have.

## Environment Verification

To verify that your local environment is correctly configured to run this project:

1. **Start Services**: Ensure MySQL (port 3306) and Redis (port 6379) are running.
2. **Database Verification**: Run `php artisan migrate:status` to ensure database connectivity.
3. **Health Check**: Access `http://localhost/dev/health` (or your local domain equivalent) to see a JSON payload of your current service connectivity status.
4. **Queue Validation**: Ensure `QUEUE_CONNECTION` handles your background tasks locally (currently set to `database` fallback due to missing Redis PHP extension on Windows). Run `php artisan queue:work` — media conversions are queued by default and will not appear without a worker.
5. **Permissions**: After pulling changes, run `php artisan db:seed --force` so any newly added permission exists in your database. A missing permission does not error; it just denies everyone but Super Admin.
