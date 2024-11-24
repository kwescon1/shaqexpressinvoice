<?php

declare(strict_types=1);

namespace App\Contracts\Services;

use App\Models\Invoice;
use App\Models\Product;

interface UpdateInvoiceItemQuantity
{
    /**
     * Update the quantity of a product in an invoice.
     *
     * @param  Invoice  $invoice  The invoice containing the product.
     * @param  Product  $product  The product to update.
     * @param  int  $quantity  The new quantity for the product.
     */
    public function updateItemQuantity(Invoice $invoice, Product $product, int $quantity): void;
}
