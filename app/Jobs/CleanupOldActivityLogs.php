<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\Activitylog\Models\Activity;

class CleanupOldActivityLogs implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Immutable / Critical Logs that are NEVER pruned
        $criticalLogNames = ['auth', 'security', 'governance'];
        $criticalSubjectTypes = [
            \App\Models\User::class,
            \Spatie\Permission\Models\Role::class,
            \App\Models\UserInvitation::class,
        ];

        // Delete logs older than 1 year, EXCEPT critical logs
        Activity::where('created_at', '<', now()->subYear())
            ->whereNotIn('log_name', $criticalLogNames)
            ->where(function ($query) use ($criticalSubjectTypes) {
                $query->whereNotIn('subject_type', $criticalSubjectTypes)
                      ->orWhereNull('subject_type');
            })
            ->delete();
    }
}
