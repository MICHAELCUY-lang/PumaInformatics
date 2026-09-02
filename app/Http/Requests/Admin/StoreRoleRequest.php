<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Admin\Concerns\GrantsPermissionsSafely;
use Illuminate\Foundation\Http\FormRequest;

class StoreRoleRequest extends FormRequest
{
    use GrantsPermissionsSafely;

    public function authorize(): bool
    {
        return $this->user()->can('manage.roles');
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:roles,name'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => $this->permissionGrantRules(),
        ];
    }
}
