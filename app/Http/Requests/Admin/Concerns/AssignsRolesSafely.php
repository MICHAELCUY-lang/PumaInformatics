<?php

namespace App\Http\Requests\Admin\Concerns;

use Illuminate\Validation\Rule;

/**
 * Shared guard for the "roles" input on user forms.
 *
 * Without it, any account holding manage.users could grant itself — or a
 * confederate — the Super Admin role, which Gate::before treats as unrestricted.
 */
trait AssignsRolesSafely
{
    /**
     * Roles that only a Super Admin may hand out.
     */
    protected function privilegedRoles(): array
    {
        return ['Super Admin'];
    }

    /**
     * @return array<int, mixed>
     */
    protected function roleAssignmentRules(): array
    {
        $rules = ['exists:roles,name'];

        if (! $this->user()->hasRole('Super Admin')) {
            $rules[] = Rule::notIn($this->privilegedRoles());
        }

        return $rules;
    }

    /**
     * @return array<string, string>
     */
    protected function roleAssignmentMessages(): array
    {
        return [
            'roles.*.not_in' => 'Only a Super Admin may assign the :input role.',
        ];
    }
}
