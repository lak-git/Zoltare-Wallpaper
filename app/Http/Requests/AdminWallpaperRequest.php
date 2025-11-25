<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminWallpaperRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:150'],
            'category' => ['required', 'string', 'max:50'],
            'price' => ['required', 'numeric', 'min:0', 'max:999'],
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:51200'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}

