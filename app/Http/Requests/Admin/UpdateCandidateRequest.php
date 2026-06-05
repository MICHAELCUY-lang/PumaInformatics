<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCandidateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('manage.voting');
    }

    public function rules(): array
    {
        return [
            'voting_session_id' => ['required', 'exists:voting_sessions,id'],
            'name' => ['required', 'string', 'max:255'],
            'vision' => ['nullable', 'string'],
            'mission' => ['nullable', 'string'],
            'biography' => ['nullable', 'string'],
            'achievements' => ['nullable', 'string'],
            'social_links' => ['nullable', 'array'],
            'order' => ['nullable', 'integer'],
            'is_featured' => ['nullable', 'boolean'],
        ];
    }
}
