<?php

namespace App\Console\Commands;

use App\Models\Article;
use App\Models\Event;
use App\Services\CacheService;
use Illuminate\Console\Command;

/**
 * The admin editors offer a "Scheduled" status for articles and events, but
 * nothing previously flipped those rows to "published" once their date arrived,
 * so scheduled content stayed invisible forever. This command closes that loop.
 */
class PublishScheduledContent extends Command
{
    protected $signature = 'content:publish-scheduled';

    protected $description = 'Promote scheduled articles and events to published once their date has arrived';

    public function handle(CacheService $cache): int
    {
        $articles = Article::where('status', 'scheduled')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->update(['status' => 'published']);

        // Events have no published_at, so their start date is the trigger.
        $events = Event::where('status', 'scheduled')
            ->whereNotNull('start_date')
            ->where('start_date', '<=', now())
            ->update(['status' => 'published']);

        if ($articles) {
            $cache->flush('news');
        }

        if ($events) {
            $cache->flush('events');
        }

        $this->info("Published {$articles} article(s) and {$events} event(s).");

        return self::SUCCESS;
    }
}
