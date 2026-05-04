<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\IdeaStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IdeaRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:10000'],
            'links' => ['nullable'],
            'status' => ['required', Rule::enum(IdeaStatus::class)],
            'image_path' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
