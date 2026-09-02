<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('manage.projects');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'category_id' => ['nullable', 'exists:project_categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'status' => ['required', 'in:draft,published,archived'],
            'is_featured' => ['boolean'],
            'excerpt' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'start_date' => ['nullable', 'date'],
            'completion_date' => ['nullable', 'date'],
            'github_url' => ['nullable', 'url', 'max:255'],
            'demo_url' => ['nullable', 'url', 'max:255'],
            'documentation_url' => ['nullable', 'url', 'max:255'],
            'technologies' => ['nullable', 'array'],
            'technologies.*' => ['exists:technologies,id'],
        ];
    }
}
