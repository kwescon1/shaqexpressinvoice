<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\StockFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Stock extends Base
{
    /** @use HasFactory<StockFactory> */
    use HasFactory, SoftDeletes;

    /**
     * Get the attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * The product associated with this stock.
     *
     * @return BelongsTo<Product, Stock>
     */
    public function product(): BelongsTo
    {
        /** @var BelongsTo<Product, Stock> */
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * Accessor & Mutator for retail_price.
     * Handles division and rounding on access, and multiplication on assignment.
     *
     * @return Attribute<float, int>
     */
    protected function retailPrice(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value): float => is_numeric($value) ? round((int) $value / 100, 2) : 0.0,
            set: fn (mixed $value): int => is_numeric($value) ? (int) round((float) $value * 100) : 0
        );
    }

    /**
     * Accessor & Mutator for cost_price.
     * Handles division and rounding on access, and multiplication on assignment.
     *
     * @return Attribute<float, int>
     */
    protected function costPrice(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value): float => is_numeric($value) ? round((int) $value / 100, 2) : 0.0,
            set: fn (mixed $value): int => is_numeric($value) ? (int) round((float) $value * 100) : 0
        );
    }
}
