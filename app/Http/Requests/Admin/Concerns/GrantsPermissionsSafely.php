<?php

namespace App\Http\Requests\Admin\Concerns;

use Closure;
use Spatie\Permission\Models\Role;

/**
 * Shared guard for the "permissions" input on role forms.
 *
 * Two escalation paths are closed here:
 *  1. An Admin granting its own role a permission it does not already hold
 *     (e.g. view.security_logs, which is meant to be Super Admin only).
 *  2. Anyone but a Super Admin editing the Super Admin role itself.
 */
trait GrantsPermissionsSafely
{
    protected function protectedRoleNames(): array
    {
        return ['Super Admin'];
    }

    /**
     * @return array<int, mixed>
     */
    protected function permissionGrantRules(): array
    {
        $rules = ['exists:permissions,name'];

        if (! $this->user()->hasRole('Super Admin')) {
            $rules[] = function (string $attribute, mixed $value, Closure $fail) {
                if (! $this->user()->can($value)) {
                    $fail("You cannot grant the \"{$value}\" permission because you do not hold it yourself.");
                }
            };
        }

        return $rules;
    }

    /**
     * Call from a request that targets an existing role.
     */
    protected function assertRoleIsEditable(?Role $role): ?string
    {
        if (! $role || $this->user()->hasRole('Super Admin')) {
            return null;
        }

        if (in_array($role->name, $this->protectedRoleNames(), true)) {
            return "The \"{$role->name}\" role can only be modified by a Super Admin.";
        }

        return null;
    }
}
