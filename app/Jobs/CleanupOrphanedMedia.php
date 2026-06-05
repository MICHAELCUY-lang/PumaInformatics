<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\GlobalMedia;

class CleanupOrphanedMedia implements ShouldQueue
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
        // Find all temporary media that have expired
        $orphanedMedia = GlobalMedia::where('status', 'temporary')
            ->whereNotNull('expires_at')
            ->where('expires_at', '<', now())
            ->get();

        foreach ($orphanedMedia as $media) {
            // Delete the Eloquent model. 
            // Because GlobalMedia has cascadeOnDelete and InteractsWithMedia,
            // Spatie will automatically wipe the physical files from the disk.
            $media->delete();
        }
    }
}
