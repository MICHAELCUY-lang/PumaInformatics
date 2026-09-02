<?php

namespace App\Http\Requests\Admin;

use App\Models\VotingSession;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateVotingSessionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('manage.voting');
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', Rule::in(VotingSession::STATUSES)],
            'results_visibility' => ['required', Rule::in(VotingSession::VISIBILITIES)],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ];
    }
}
