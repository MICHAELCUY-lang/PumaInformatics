<?php

namespace App\Services;

use Spatie\Activitylog\Models\Activity;
use Illuminate\Database\Eloquent\Builder;

class ActivityLogService
{
    /**
     * Get paginated and filtered activity logs based on the user's permissions.
     */
    public function getFilteredLogs(array $filters, \App\Models\User $user, int $perPage = 25)
    {
        $query = Activity::query()
            ->with(['causer', 'subject'])
            ->latest('created_at');

        // Apply Permission Boundaries
        if (!$user->can('view.security_logs') && !$user->hasRole('Super Admin')) {
            // Standard Admins/Editors cannot see authentication, users, roles, or sensitive logs
            $query->whereNotIn('log_name', ['auth', 'security', 'governance'])
                  ->whereNotIn('subject_type', [
                      \App\Models\User::class,
                      \Spatie\Permission\Models\Role::class,
                      \App\Models\UserInvitation::class,
                  ]);
        }

        // Search by Log Name / Module
        if (!empty($filters['log_name'])) {
            $query->where('log_name', $filters['log_name']);
        }

        // Search by Causer (User ID)
        if (!empty($filters['causer_id'])) {
            $query->where('causer_type', \App\Models\User::class)
                  ->where('causer_id', $filters['causer_id']);
        }

        // Search by Event Type
        if (!empty($filters['event'])) {
            $query->where('event', $filters['event']);
        }

        return $query->paginate($perPage);
    }

    /**
     * Redact sensitive information from the log properties before displaying to UI.
     */
    public function redactPayload(Activity $activity): array
    {
        $properties = $activity->properties ? $activity->properties->toArray() : [];
        $redactedKeys = ['password', 'token', 'remember_token', 'two_factor_secret', 'two_factor_recovery_codes'];

        $mask = function (&$array) use (&$mask, $redactedKeys) {
            foreach ($array as $key => &$value) {
                if (is_array($value)) {
                    $mask($value);
                } elseif (in_array(strtolower($key), $redactedKeys, true)) {
                    $value = '******** (REDACTED)';
                }
            }
        };

        $mask($properties);

        return $properties;
    }
}
