<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Admin\Concerns\GrantsPermissionsSafely;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class UpdateRoleRequest extends FormRequest
{
    use GrantsPermissionsSafely;

    public function authorize(): bool
    {
        return $this->user()->can('manage.roles');
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('roles', 'name')->ignore($this->role)],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => $this->permissionGrantRules(),
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            if ($message = $this->assertRoleIsEditable($this->route('role'))) {
                $validator->errors()->add('name', $message);
            }
        });
    }
}
