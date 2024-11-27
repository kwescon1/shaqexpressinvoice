<?php

declare(strict_types=1);

namespace App\Builders;

use App\Contracts\Builders\QueryInvoice;
use App\Models\Branch;
use App\Models\Invoice;
use Illuminate\Support\Facades\Cache;

final class InvoiceQueryBuilder implements QueryInvoice
{
    /**
     * Create a new class instance.
     */
    public function __construct(private Invoice $invoice)
    {
        //
    }

    public function lastSavedInvoice(Branch $branch): ?Invoice
    {
        // Use the cache key for the current invoice
        return Cache::remember(
            'current_invoice', // Cache key
            now()->addHour(), // Cache duration of 1 hour
            function () use ($branch): ?Invoice {
                /** @phpstan-ignore-next-line */
                return $branch->invoices()->withTrashed()->latest()->first();
            }
        );
    }

    public function verifyInvoiceNumber(string $invoiceNumber, Branch $branch): bool
    {
        return $this->invoice->query()->withTrashed()->whereBelongsTo($branch)->whereInvoiceNumber($invoiceNumber)->exists();
    }
}
