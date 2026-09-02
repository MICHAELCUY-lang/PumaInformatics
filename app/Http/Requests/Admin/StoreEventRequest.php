<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('manage.events');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'category_id' => ['nullable', 'exists:event_categories,id'],
            'description' => ['nullable', 'string'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'status' => ['required', 'in:draft,published,scheduled'],
            'is_featured' => ['boolean'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'timezone' => ['required', 'string', 'timezone'],
            'location_name' => ['nullable', 'string', 'max:255'],
            'location_address' => ['nullable', 'string'],
            'external_registration_url' => ['nullable', 'url', 'max:255'],
            'internal_rsvp_enabled' => ['boolean'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['exists:event_tags,id'],
            'featured_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'gallery' => ['nullable', 'array'],
            'gallery.*' => ['image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
        ];
    }
}
