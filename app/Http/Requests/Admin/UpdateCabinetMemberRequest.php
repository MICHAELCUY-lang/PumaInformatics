<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCabinetMemberRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->hasRole('Super Admin') || $this->user()->hasRole('Admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'department_id' => ['nullable', 'exists:cabinet_departments,id'],
            'cabinet_id' => ['nullable', 'exists:cabinets,id'],
            'name' => ['required', 'string', 'max:255'],
            'role_title' => ['required', 'string', 'max:255'],
            'role_hierarchy_level' => ['integer', 'min:1'],
            'term_year' => ['required', 'string', 'max:50'],
            'is_active' => ['boolean'],
            'biography' => ['nullable', 'string'],
            'portrait' => ['nullable', 'image', 'max:2048'],
        ];
    }
}
