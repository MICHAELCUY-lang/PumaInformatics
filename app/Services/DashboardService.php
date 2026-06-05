<?php

namespace App\Services;

use App\Models\User;
// use App\Models\Event;
// use App\Models\News;

class DashboardService extends BaseService
{
    /**
     * Get aggregate statistics for the admin dashboard.
     */
    public function getAnalytics(): array
    {
        return [
            'total_users' => User::count(),
            'total_events' => 0, // Event::count()
            'total_news' => 0,   // News::count()
            'pending_aspirations' => 0,
        ];
    }
}
