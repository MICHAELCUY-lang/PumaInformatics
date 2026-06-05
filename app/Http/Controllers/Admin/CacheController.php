<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SystemCacheService;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CacheController extends Controller
{
    use AuthorizesRequests;

    protected $cacheService;

    public function __construct(SystemCacheService $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    public function index()
    {
        $this->authorize('manage.settings');

        $tags = [
            'navigation', 'homepage', 'news', 'events', 'cabinet', 
            'projects', 'partners', 'aspirations', 'voting', 'media', 
            'permissions', 'settings'
        ];

        return view('admin.cache.index', compact('tags'));
    }

    public function clearTag(Request $request)
    {
        $this->authorize('manage.settings');
        
        $request->validate([
            'tag' => 'required|string'
        ]);

        $success = $this->cacheService->clearTag($request->tag);

        if (!$success) {
            return back()->with('error', "Your current cache driver does not support tagged clearing.");
        }

        return back()->with('success', "Cache group '{$request->tag}' cleared successfully.");
    }

    public function clearSystem(Request $request)
    {
        $this->authorize('manage.settings');
        
        $request->validate([
            'type' => 'required|in:views,config,routes,optimize,global',
            'reason' => 'required_if:type,global|nullable|string|max:255'
        ]);

        switch ($request->type) {
            case 'views':
                $this->cacheService->clearViews();
                $message = "Compiled views cleared.";
                break;
            case 'config':
                $this->cacheService->clearConfig();
                $message = "Configuration cache cleared.";
                break;
            case 'routes':
                $this->cacheService->clearRoutes();
                $message = "Routing cache cleared.";
                break;
            case 'optimize':
                $this->cacheService->optimizeApplication();
                $message = "Application optimized (config, routes, views cached).";
                break;
            case 'global':
                $this->cacheService->clearGlobalApplicationCache($request->reason ?? 'Manual override');
                $message = "Global application cache flushed.";
                break;
            default:
                $message = "Unknown operation.";
                break;
        }

        return back()->with('success', $message);
    }
}
