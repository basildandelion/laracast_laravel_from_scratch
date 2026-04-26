<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IdeaRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required'],
            'description' => ['nullable'],
            'user_id' => ['required', 'exists:users'],
            'links' => ['required'],
            'status' => ['required'],
            'image_path' => ['nullable'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
