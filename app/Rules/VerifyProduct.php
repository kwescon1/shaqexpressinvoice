<?php

declare(strict_types=1);

namespace App\Rules;

use App\Models\Invoice;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

final class VerifyProduct implements ValidationRule
{
    /**
     * Create a new rule instance.
     */
    public function __construct(private Invoice $invoice) {}

    /**
     * Determine if the validation rule passes.
     *
     * @param  Closure(string): void  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Check if the product already exists in the invoice
        if ($this->invoice->products()->where('product_id', $value)->exists()) {
            $fail('The selected product is already part of the invoice.');
        }
    }
}
