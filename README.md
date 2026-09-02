# PUMA Informatics

Institutional website and content platform for PUMA Informatics, President
University — public showcase plus a full admin panel for news, events, projects,
partners, the cabinet, student aspirations and elections.

Production: **https://informatics.president.ac.id**

## Stack

- Laravel 12 / PHP 8.2
- MySQL 8
- Tailwind CSS 3, Alpine.js, Tiptap (rich-text editor), Vite
- spatie/laravel-permission (RBAC), spatie/laravel-activitylog (audit trail),
  spatie/laravel-medialibrary (uploads and image conversions)

## Architecture

```
Controller → Service → Repository (interface + Eloquent) → Model
                 ↑
                DTO
```

Controllers stay thin. Business rules live in `app/Services`, data access in
`app/Repositories`, and `app/DTOs` carries validated input between them.
Read-heavy public queries are cached through `App\Services\CacheService`, which
uses cache tags on Redis and falls back to versioned keys on the database driver.

## Modules

| Public | Admin |
|---|---|
| Home, Newsroom, Events, Projects, Partners, Cabinet | CRUD for all of those |
| Aspirations (public submission form) | Aspiration triage |
| Voting booth | Voting sessions and candidates |
| `sitemap.xml` | Users, roles, invitations, media, activity log, cache |

## Access control

Roles are defined in `database/seeders/RolesAndPermissionsSeeder.php`, which is
the **single source of truth** for the permission matrix and is safe to re-run.

| Role | Scope |
|---|---|
| Super Admin | everything, via the `Gate::before` hook in `AppServiceProvider` |
| Admin | everything except `view.security_logs` and `manage.audit_retention` |
| Editor | news, events, projects, partners, media, activity log |
| Moderator | aspirations |
| Viewer / User | no admin access |

Enforcement happens in three layers: a role gate on the admin route group, a
`permission:` middleware per module in `routes/web.php`, and `authorize()` in
each FormRequest. Adding a new permission means adding it to the seeder and
re-running `php artisan db:seed --force`.

## Local setup

Requires PHP 8.2+, Composer, Node 20+, MySQL 8. On Windows, Laragon bundles all
four — step-by-step instructions, including the corporate-proxy TLS workaround
that breaks `composer install` and `npm install`, are in
**[LOCAL_SETUP.md](LOCAL_SETUP.md)**.

Short version:

```bash
composer install && cp .env.example .env && php artisan key:generate
```

Point `DB_*` at a local MySQL database, then:

```bash
php artisan migrate --seed && npm install && npm run build
```

The seeder creates a Super Admin. Locally it defaults to `admin@puma.it` /
`password`; override with `ADMIN_EMAIL` and `ADMIN_PASSWORD` in `.env`. In
production a blank `ADMIN_PASSWORD` aborts the seed.

Run everything (server, queue, logs, Vite) in one command:

```bash
composer dev
```

Local diagnostics for service connectivity: `http://localhost:8000/dev/health`.

## Tests

```bash
php artisan test
```

Tests use an in-memory SQLite database, so they need no local MySQL setup.

## Deployment

The site runs on cPanel. Two things differ from a normal Laravel deploy and will
break the site if missed:

1. **`npm run build` must be run on the server** (via cPanel's Node.js toolchain)
   after every front-end change. `public/build/` is gitignored, and Blade's
   `@vite` throws without the generated manifest.
2. **The queue runs from cron**, not Supervisor. Without that cron entry,
   uploaded images never get their conversions.

Full instructions: [DEPLOYMENT.md](DEPLOYMENT.md) ·
Go-live checks: [PRODUCTION_CHECKLIST.md](PRODUCTION_CHECKLIST.md) ·
Architecture notes: [DOCUMENTATION.md](DOCUMENTATION.md) ·
Local dev: [LOCAL_SETUP.md](LOCAL_SETUP.md)
