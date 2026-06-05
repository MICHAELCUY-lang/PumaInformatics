<?php

namespace App\Services;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

class SystemCacheService
{
    protected \App\Services\CacheService $cacheService;

    public function __construct(\App\Services\CacheService $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    /**
     * Clear a specific cache tag group.
     */
    public function clearTag(string $tag, ?\App\Models\User $causer = null): bool
    {
        try {
            $this->cacheService->flush($tag);
            $this->logOperation('tag_cleared', "Cleared cache tag: {$tag}", $causer, ['tag' => $tag]);
            return true;
        } catch (\Exception $e) {
            $this->logOperation('tag_clear_failed', "Failed to clear cache tag: {$tag}.", $causer, ['tag' => $tag, 'error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Clear application data cache completely (Global).
     */
    public function clearGlobalApplicationCache(string $reason, ?\App\Models\User $causer = null): void
    {
        Artisan::call('cache:clear');
        $this->logOperation('global_cleared', "Global Application Cache Cleared", $causer, ['reason' => $reason]);
    }

    /**
     * Clear compiled views.
     */
    public function clearViews(?\App\Models\User $causer = null): void
    {
        Artisan::call('view:clear');
        $this->logOperation('views_cleared', "Compiled Views Cache Cleared", $causer);
    }

    /**
     * Clear configuration cache.
     */
    public function clearConfig(?\App\Models\User $causer = null): void
    {
        Artisan::call('config:clear');
        $this->logOperation('config_cleared', "Configuration Cache Cleared", $causer);
    }

    /**
     * Clear routing cache.
     */
    public function clearRoutes(?\App\Models\User $causer = null): void
    {
        Artisan::call('route:clear');
        $this->logOperation('routes_cleared', "Routing Cache Cleared", $causer);
    }

    /**
     * Optimize application (route:cache, config:cache, view:cache).
     */
    public function optimizeApplication(?\App\Models\User $causer = null): void
    {
        Artisan::call('optimize');
        $this->logOperation('application_optimized', "Application Optimized (Config/Routes/Views Cached)", $causer);
    }

    protected function logOperation(string $action, string $description, ?\App\Models\User $causer, array $properties = []): void
    {
        $logger = activity()->useLog('governance')->event($action);
        
        $user = $causer ?? auth()->user();
        if ($user) {
            $logger->causedBy($user);
        }

        $logger->withProperties($properties)->log($description);
    }
}
