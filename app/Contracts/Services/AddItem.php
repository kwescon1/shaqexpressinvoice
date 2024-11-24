<?php

declare(strict_types=1);

namespace App\Contracts\Services;

use App\Models\Invoice;
use App\Models\Product;

interface AddItem
{
    /**
     * Add a product to an invoice.
     *
     * @param  Invoice  $invoice  The invoice to add the product to.
     * @param  Product  $product  The product to add.
     * @param  int  $quantity  The quantity of the product to add.
     */
    public function addItem(Invoice $invoice, Product $product, int $quantity = 1): void;
}
