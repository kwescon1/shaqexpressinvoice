<?php

declare(strict_types=1);

namespace App\Contracts\Services;

use App\Models\Invoice;

interface Sellable
{
    /**
     * Process the sold products for a given invoice.
     *
     * @param  Invoice  $invoice  The invoice that the sold products belong to.
     */
    public function soldProducts(Invoice $invoice): void;
}
