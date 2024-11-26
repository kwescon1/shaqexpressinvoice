<?php

declare(strict_types=1);

namespace App\Contracts\Services;

use App\Models\Invoice;

interface ProcessInvoice
{
    /**
     * Process an invoice.
     */
    public function processInvoice(Invoice $invoice): Invoice;
}
