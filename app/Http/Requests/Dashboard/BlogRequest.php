<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class BlogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'title'            => ['required', 'string', 'max:255'],
            'short_description'=> ['nullable', 'string'],
            'description'      => ['required', 'string'],
            'author'           => ['nullable', 'string', 'max:255'],
            'published_at'     => ['nullable', 'date'],
            'is_active'        => ['nullable', 'boolean'],
            'is_featured'      => ['nullable', 'boolean'],
            'meta_title'       => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
            'meta_keywords'    => ['nullable', 'string'],
            'image'            => ['nullable', 'image', 'max:5120'],
            'tags'             => ['nullable', 'array'],
            'tags.*'           => ['exists:tags,id'],
        ];
    }
}
