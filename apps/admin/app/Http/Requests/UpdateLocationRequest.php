<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLocationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'               => ['required', 'string', 'max:255'],
            'slug'                => ['required', 'string', 'max:64', Rule::unique('locations', 'slug')->ignore($this->location), 'regex:/^[a-z0-9-]+$/'],
            'description'         => ['required', 'string'],
            'logo_path'           => ['nullable', 'string', 'max:2048'],
            'primary_color'       => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'primary_light_color' => ['nullable', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'primary_dark_color'  => ['nullable', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'accent_color'        => ['nullable', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'accent_light_color'  => ['nullable', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'accent_dark_color'   => ['nullable', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'is_default'          => ['boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['is_default' => $this->boolean('is_default')]);
    }
}
