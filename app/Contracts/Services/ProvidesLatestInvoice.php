<?php

declare(strict_types=1);

namespace App\Contracts\Services;

use App\Models\Branch;
use App\Models\Invoice;

/**
 * Contract for providing the latest unique invoice number functionality.
 */
interface ProvidesLatestInvoice
{
    /**
     * Cache key prefix for storing unique invoice numbers.
     */
    public const CACHE_KEY_UNIQUE_INVOICE_NUMBER = 'UniqueInvoiceNum::';

    /**
     * Cache expiration time in seconds.
     */
    public const CACHE_SECONDS = 300;

    /**
     * Retrieve the latest unique invoice number for a given branch.
     *
     * @param  Branch  $branch  The branch for which to retrieve the latest unique invoice number.
     * @return string The latest unique invoice number.
     */
    public function latestUniqueInvoiceNumber(Branch $branch): string;
}
