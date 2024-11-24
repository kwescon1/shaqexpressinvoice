<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\InvoiceStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Invoice extends Base
{
    use SoftDeletes;

    /**
     * Get the attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status' => InvoiceStatus::class, // Cast status to InvoiceStatus enum
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'invoice_product')
            ->withPivot('quantity', 'price') // Include extra fields
            ->withTimestamps();
    }

    /**
     * Accessor & Mutator for the total field.
     * Handles division and rounding on access, and multiplication on assignment.
     *
     * @return Attribute<float, int>
     */
    protected function total(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value): float => is_numeric($value) ? round((int) $value / 100, 2) : 0.0,
            set: fn (mixed $value): int => is_numeric($value) ? (int) round((float) $value * 100) : 0
        );
    }
}
