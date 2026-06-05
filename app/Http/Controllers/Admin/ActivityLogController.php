<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ActivityLogService;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ActivityLogController extends Controller
{
    use AuthorizesRequests;

    protected ActivityLogService $service;

    public function __construct(ActivityLogService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        // Must have at least view.activity_logs permission
        $this->authorize('view.activity_logs');

        $logs = $this->service->getFilteredLogs($request->all(), $request->user());

        return view('admin.activity-logs.index', compact('logs'));
    }

    public function show(Activity $activity_log, Request $request)
    {
        $this->authorize('view.activity_logs');

        // Restrict access to sensitive logs if user doesn't have security clearance
        if (in_array($activity_log->log_name, ['auth', 'security', 'governance']) || 
            in_array($activity_log->subject_type, [\App\Models\User::class, \Spatie\Permission\Models\Role::class])) {
            $this->authorize('view.security_logs');
        }

        $redactedProperties = $this->service->redactPayload($activity_log);

        return view('admin.activity-logs.show', ['activity' => $activity_log, 'redactedProperties' => $redactedProperties]);
    }
}
