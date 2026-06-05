<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Public Interface Routes
Route::name('public.')->namespace('App\Http\Controllers\Public')->group(function () {
    Route::get('/', [\App\Http\Controllers\Public\HomeController::class, 'index'])->name('home');
    
    // Newsroom
    Route::get('/news', [\App\Http\Controllers\Public\ArticleController::class, 'index'])->name('news.index');
    Route::get('/news/{slug}', [\App\Http\Controllers\Public\ArticleController::class, 'show'])->name('news.show');
    
    // Events
    Route::get('/events', [\App\Http\Controllers\Public\EventController::class, 'index'])->name('events.index');
    Route::get('/events/{slug}', [\App\Http\Controllers\Public\EventController::class, 'show'])->name('events.show');

    // Projects
    Route::get('/projects', [\App\Http\Controllers\Public\ProjectController::class, 'index'])->name('projects.index');
    Route::get('/projects/{slug}', [\App\Http\Controllers\Public\ProjectController::class, 'show'])->name('projects.show');

    // Partners
    Route::get('/partners', [\App\Http\Controllers\Public\PartnerController::class, 'index'])->name('partners.index');

    // Cabinet
    Route::get('/cabinet', [\App\Http\Controllers\Public\CabinetController::class, 'index'])->name('cabinet.index');
    Route::get('/cabinet/{slug}', [\App\Http\Controllers\Public\CabinetController::class, 'show'])->name('cabinet.show');

    // Aspirations
    Route::get('/aspirations', [\App\Http\Controllers\Public\AspirationController::class, 'create'])->name('aspirations.create');
    Route::post('/aspirations', [\App\Http\Controllers\Public\AspirationController::class, 'store'])
        ->middleware('throttle:aspirations')
        ->name('aspirations.store');

    // Voting (Public - Sessions listing and Booth display)
    Route::get('/voting', [\App\Http\Controllers\Public\VotingController::class, 'index'])->name('voting.index');
    Route::get('/voting/{slug}', [\App\Http\Controllers\Public\VotingController::class, 'show'])->name('voting.show');

    // Sitemap
    Route::get('/sitemap.xml', [\App\Http\Controllers\Public\SitemapController::class, 'index'])->name('sitemap');
});

use App\Http\Controllers\Admin\NavigationController;
use App\Http\Controllers\Admin\DashboardController;

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth.basic', 'role:Super Admin|Admin|Editor'])->name('dashboard');

Route::middleware(['auth.basic', 'role:Super Admin|Admin|Editor'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Navigation Manager
    Route::post('navigations/reorder', [NavigationController::class, 'reorder'])->name('navigations.reorder');
    Route::resource('navigations', NavigationController::class)->except(['show']);

    // Newsroom CRUD
    Route::resource('articles', \App\Http\Controllers\Admin\ArticleController::class);

    // Events System
    Route::resource('event-categories', \App\Http\Controllers\Admin\EventCategoryController::class)->except(['show']);
    Route::resource('event-tags', \App\Http\Controllers\Admin\EventTagController::class)->except(['show']);
    Route::resource('events', \App\Http\Controllers\Admin\EventController::class);

    // Cabinet System
    Route::resource('cabinets', \App\Http\Controllers\Admin\CabinetController::class)->except(['show']);
    Route::resource('cabinet-departments', \App\Http\Controllers\Admin\CabinetDepartmentController::class)->except(['show']);
    Route::resource('cabinet-members', \App\Http\Controllers\Admin\CabinetMemberController::class);

    // Projects System
    Route::resource('project-categories', \App\Http\Controllers\Admin\ProjectCategoryController::class)->except(['show']);
    Route::resource('technologies', \App\Http\Controllers\Admin\TechnologyController::class)->except(['show']);
    Route::resource('projects', \App\Http\Controllers\Admin\ProjectController::class);

    // Partners System
    Route::resource('partner-categories', \App\Http\Controllers\Admin\PartnerCategoryController::class)->except(['show']);
    Route::resource('partners', \App\Http\Controllers\Admin\PartnerController::class);

    // Aspirations System
    Route::resource('aspiration-categories', \App\Http\Controllers\Admin\AspirationCategoryController::class)->except(['show']);
    Route::resource('aspirations', \App\Http\Controllers\Admin\AspirationController::class)->only(['index', 'show', 'update']);

    // Voting System
    Route::resource('voting-sessions', \App\Http\Controllers\Admin\VotingSessionController::class);
    Route::resource('candidates', \App\Http\Controllers\Admin\CandidateController::class);

    // User & Role Management (Governance)
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    Route::post('users/{user}/status', [\App\Http\Controllers\Admin\UserController::class, 'updateStatus'])->name('users.status');
    
    Route::resource('invitations', \App\Http\Controllers\Admin\UserInvitationController::class)->only(['index', 'store', 'destroy']);
    Route::resource('roles', \App\Http\Controllers\Admin\RoleController::class);

    // Media Manager UI
    Route::resource('media', \App\Http\Controllers\Admin\MediaController::class)->only(['index', 'destroy']);

    // Activity Logs Viewer (Audit Trail)
    Route::resource('activity-logs', \App\Http\Controllers\Admin\ActivityLogController::class)->only(['index', 'show']);

    // Cache Management
    Route::get('cache', [\App\Http\Controllers\Admin\CacheController::class, 'index'])->name('cache.index');
    Route::post('cache/tag', [\App\Http\Controllers\Admin\CacheController::class, 'clearTag'])->name('cache.tag');
    Route::post('cache/system', [\App\Http\Controllers\Admin\CacheController::class, 'clearSystem'])->name('cache.system');

    // Module placeholders to be implemented
    // Route::resource('news', NewsController::class);
    // Route::resource('events', EventController::class);
    // ...
});

// Public Routes (Testing / Placeholder until full UI implementation)
// Route removed as it's now in the public group above

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [\App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Secure Voting Cast Route (auth + email verification required)
    Route::post('/voting/{slug}', [\App\Http\Controllers\Public\VotingController::class, 'store'])
        ->middleware(['verified', 'throttle:6,1'])
        ->name('voting.store');

    // Admin API endpoints (Cookie Session Authenticated)
    Route::prefix('api/admin')->group(function () {
        Route::post('/media/upload', [\App\Http\Controllers\Api\Admin\MediaUploadController::class, 'store'])
            ->middleware('throttle:60,1')
            ->name('api.admin.media.upload');
    });
});

// Public Invitation Redemption
Route::get('/join/{token}', [\App\Http\Controllers\Public\InvitationController::class, 'show'])->name('invitation.show');
Route::post('/join/{token}', [\App\Http\Controllers\Public\InvitationController::class, 'store'])->name('invitation.store');

// Health check diagnostics for local development
if (app()->environment('local')) {
    Route::get('/dev/health', function () {
        $db = 'OK';
        try {
            \DB::connection()->getPdo();
        } catch (\Exception $e) {
            $db = 'Failed: ' . $e->getMessage();
        }

        $redis = 'OK';
        try {
            \Illuminate\Support\Facades\Redis::ping();
        } catch (\Exception $e) {
            $redis = 'Failed: ' . $e->getMessage();
        }

        return [
            'Environment' => app()->environment(),
            'PHP Version' => PHP_VERSION,
            'Laravel Version' => app()->version(),
            'MySQL Connection' => $db,
            'Redis Connection' => $redis,
            'Session Driver' => config('session.driver'),
            'Cache Driver' => config('cache.default'),
            'Queue Connection' => config('queue.default'),
        ];
    });
}

require __DIR__.'/auth.php';
