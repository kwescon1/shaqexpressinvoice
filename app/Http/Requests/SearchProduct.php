<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class SearchProduct extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Allow the request to proceed
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'product' => 'nullable|string|max:255', // Make 'product' required
        ];
    }

    /**
     * Get custom error messages for validation failures.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'product.required' => 'The product parameter is required for search.',
        ];
    }

    /**
     * Sanitize the input before validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'product' => trim((string) filter_var($this->query('product', ''), FILTER_SANITIZE_STRING)), // Ensure a string
        ]);
    }
}
