<?php

namespace App\Http\Requests\Admin;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Spatie\Permission\Models\Role;

class StoreUserInvitationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('manage.users');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email|unique:users,email|unique:user_invitations,email',
            'role_id' => [
                'nullable',
                'exists:roles,id',
                // Closes the escalation path of inviting a fresh Super Admin
                // account to an address the inviter controls.
                function (string $attribute, mixed $value, Closure $fail) {
                    if ($this->user()->hasRole('Super Admin') || blank($value)) {
                        return;
                    }

                    if (Role::find($value)?->name === 'Super Admin') {
                        $fail('Only a Super Admin may invite another Super Admin.');
                    }
                },
            ],
        ];
    }
}
