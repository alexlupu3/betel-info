<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateContentItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type'          => ['required', Rule::in(['card', 'poster', 'richtext', 'group'])],
            'title'         => ['required_unless:type,richtext', 'nullable', 'string', 'max:255'],
            'content'       => ['required_if:type,richtext', 'nullable', 'string'],
            'description'   => ['nullable', 'string'],
            'thumbnail_url' => ['nullable', 'url', 'max:2048'],
            'image_url'     => ['required_if:type,poster', 'nullable', 'url', 'max:2048'],
            'date'          => ['nullable', 'date'],
            'time'          => ['nullable', 'regex:/^\d{2}:\d{2}$/'],
            'link_url'      => ['nullable', 'url', 'max:2048'],
            'link_text'     => ['nullable', 'string', 'max:255'],
            'parent_id'     => ['nullable', 'integer', 'exists:content_items,id'],
            'published'     => ['boolean'],
            'locations'     => ['nullable', 'array'],
            'locations.*'   => ['integer', 'exists:locations,id'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['published' => $this->boolean('published')]);
    }
}
