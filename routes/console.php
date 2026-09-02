<?php

use App\Jobs\CleanupOldActivityLogs;
use App\Jobs\CleanupOrphanedMedia;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| Scheduled Tasks
|--------------------------------------------------------------------------
| Driven by a single cPanel cron entry, every minute:
|   * * * * * /usr/local/bin/ea-php82 /home/infm2327/informatics/artisan schedule:run >/dev/null 2>&1
| Note ea-php82 rather than php — /usr/local/bin/php is PHP 7.4 on this host.
|
| IMPORTANT: do not use Schedule::command() here. Laravel runs command events
| as sub-processes through Symfony Process, and the host has proc_open in
| disable_functions, so every such task dies with "The Process class relies on
| proc_open". schedule:run itself still fires, so the failure is silent.
|
| Schedule::call() and Schedule::job() both execute in-process and are safe.
| For the same reason ->appendOutputTo() is unavailable (it belongs to command
| events), so the heartbeat below is written through the logger instead.
*/

// Promote "Scheduled" articles and events once their date arrives. Doubles as
// the heartbeat proving the cron entry is alive.
Schedule::call(function () {
    Artisan::call('content:publish-scheduled');

    Log::channel('single')->info('[schedule] '.trim(Artisan::output()));
})
    ->everyFiveMinutes()
    ->name('content:publish-scheduled')
    ->withoutOverlapping();

// Temporary Tiptap uploads expire after 24h; sweep them hourly so abandoned
// editor images don't accumulate on disk forever.
Schedule::job(new CleanupOrphanedMedia)
    ->hourly()
    ->name('media:cleanup-orphaned')
    ->withoutOverlapping();

// Prune non-critical activity logs older than a year (auth/security/governance
// logs and user/role subjects are retained by the job itself).
Schedule::job(new CleanupOldActivityLogs)
    ->weeklyOn(1, '03:00')
    ->name('activity-log:prune')
    ->withoutOverlapping();
