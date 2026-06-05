<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreNavigationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('manage navigations') || $this->user()->hasRole('Super Admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'url' => ['required', 'string', 'max:255'],
            'parent_id' => ['nullable', 'exists:navigations,id'],
            'is_external' => ['boolean'],
            'is_active' => ['boolean'],
            'visibility_roles' => ['nullable', 'array'],
            'visibility_roles.*' => ['string', 'exists:roles,name'],
        ];
    }
}
