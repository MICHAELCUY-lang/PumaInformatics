<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Admin\Concerns\AssignsRolesSafely;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
    use AssignsRolesSafely;

    public function authorize(): bool
    {
        return $this->user()->can('manage.users');
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'roles' => ['nullable', 'array'],
            'roles.*' => $this->roleAssignmentRules(),
            'status' => ['required', Rule::in(['active', 'suspended', 'inactive'])],
        ];
    }

    public function messages(): array
    {
        return $this->roleAssignmentMessages();
    }
}
