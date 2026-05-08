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
            'links' => ['nullable', 'string', 'max:10000'],
            'status' => ['required', Rule::enum(IdeaStatus::class)],
            'image' => ['nullable', 'image', 'max:10240'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
