<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\InvoiceStatus;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

use function Illuminate\Support\enum_value;

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

    /**
     * Get the user who created the invoice.
     *
     * @return BelongsTo<User, Invoice>
     */
    public function user(): BelongsTo
    {
        /** @var BelongsTo<User, Invoice> */
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the facility associated to this invoice.
     *
     * @return BelongsTo<Facility, Invoice>
     */
    public function facility(): BelongsTo
    {
        /** @var BelongsTo<Facility, Invoice> */
        return $this->belongsTo(User::class, 'facility_id');
    }

    /**
     * Get the branch associated to this invoice.
     *
     * @return BelongsTo<Branch, Invoice>
     */
    public function branch(): BelongsTo
    {
        /** @var BelongsTo<Branch, Invoice> */
        return $this->belongsTo(User::class, 'branch_id');
    }

    /**
     * Get the products associated with the invoice.
     *
     * @return BelongsToMany<Product,Invoice>
     */
    public function products(): BelongsToMany
    {
        /** @var BelongsToMany<Product,Invoice> */
        return $this->belongsToMany(Product::class, 'invoice_product')
            ->withPivot('quantity', 'price') // Include extra fields
            ->withTimestamps();
    }

    /**
     * Scope a query to only include invoices that are open.
     */
    public function scopeOpen(Builder $query): Builder
    {
        return $query->whereStatus(enum_value(InvoiceStatus::OPEN)); // Adjust based on your actual status logic
    }

    /**
     * Determine if the invoice is open.
     */
    public function isOpen(): bool
    {
        return $this->status->value === enum_value(InvoiceStatus::OPEN);
    }

    protected static function booted(): void
    {
        // When an invoice is created
        self::created(function (Invoice $invoice): void {
            // Cache the created invoice as the "current_invoice" for the user
            Cache::put('current_invoice', $invoice, now()->addHour());
        });
        // When an invoice is updated
        self::updated(function (Invoice $invoice): void {
            if (in_array($invoice->status->value, [enum_value(InvoiceStatus::FINALIZED), enum_value(InvoiceStatus::CANCELLED)], true)) {
                // Remove the invoice from the cache if the status is "closed" or "cancelled"
                Cache::forget('current_invoice');
            }
        });

        // When an invoice is deleted
        self::deleted(function (Invoice $invoice): void {
            // Remove the invoice from the cache
            Cache::forget('current_invoice');
        });
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
