<?php

use App\Jobs\CleanupOldActivityLogs;
use App\Jobs\CleanupOrphanedMedia;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| Scheduled Tasks
|--------------------------------------------------------------------------
| Driven by the single cPanel cron entry:
|   php /home/<user>/informatics/artisan schedule:run
| running every minute. Without that entry none of this fires.
*/

// Turn "Scheduled" articles and events into published ones once their date
// arrives. Without this the Scheduled option in the editors never takes effect.
Schedule::command('content:publish-scheduled')
    ->everyFiveMinutes()
    ->withoutOverlapping();

// Temporary Tiptap uploads expire after 24h; sweep them hourly so abandoned
// editor images don't accumulate on disk forever.
Schedule::job(new CleanupOrphanedMedia)
    ->hourly()
    ->withoutOverlapping()
    ->name('media:cleanup-orphaned');

// Prune non-critical activity logs older than a year (auth/security/governance
// logs and user/role subjects are retained by the job itself).
Schedule::job(new CleanupOldActivityLogs)
    ->weeklyOn(1, '03:00')
    ->withoutOverlapping()
    ->name('activity-log:prune');
