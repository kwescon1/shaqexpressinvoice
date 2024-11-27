<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class SoldProduct extends Base
{
    /**
     * The attributes that should be appended to the model's array and JSON output.
     *
     * @var array<int, string>
     */
    protected $appends = ['total_price'];

    /**
     * @return BelongsTo<Invoice, SoldProduct>
     */
    public function invoice(): BelongsTo
    {
        /** @var BelongsTo<Invoice, SoldProduct> */
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    /**
     * Virtual total price field (quantity * price).
     */
    public function getTotalPriceAttribute(): float
    {
        return round($this->quantity * $this->price, 2);
    }

    /**
     * Accessor & Mutator for the price field.
     * Handles division and rounding on access, and multiplication on assignment.
     *
     * @return Attribute<float, int>
     */
    protected function price(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value): float => is_numeric($value) ? round((int) $value / 100, 2) : 0.0,
            set: fn (mixed $value): int => is_numeric($value) ? (int) round((float) $value * 100) : 0
        );
    }
}
