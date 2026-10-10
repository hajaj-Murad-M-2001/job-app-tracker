<?php

namespace App\Http\Requests;

use App\Enums\ApplicationStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'company_name' => ['required', 'string', 'max:255'],
            'position' => ['required', 'string', 'max:255'],
            'status' => ['sometimes', Rule::enum(ApplicationStatus::class)],
            'job_url' => ['nullable', 'url', 'max:2048'],
            'salary' => ['nullable', 'integer', 'min:0'],
            'notes' => ['nullable', 'string'],
            'applied_at' => ['nullable', 'date'],
        ];
    }
}