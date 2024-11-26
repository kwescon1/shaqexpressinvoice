<?php

declare(strict_types=1);

namespace App\Contracts\Services;

use App\Models\Invoice;
use App\Models\Product;

interface ManagesItem
{
    /**
     * Add a product to an invoice.
     *
     * @param  Invoice  $invoice  The invoice to add the product to.
     * @param  Product  $product  The product to add.
     * @param  int  $quantity  The quantity of the product to add.
     */
    public function addItem(Invoice $invoice, Product $product, int $quantity = 1): void;

    /**
     * Remove a product from an invoice.
     *
     * @param  Invoice  $invoice  The invoice to remove the product from.
     * @param  Product  $product  The product to remove.
     */
    public function removeItem(Invoice $invoice, Product $product): void;

    /**
     * Remove all products from an invoice.
     *
     * @param  Invoice  $invoice  The invoice to remove the products from.
     */
    public function removeItems(Invoice $invoice): void;

    public function updateItemQuantity(Invoice $invoice, Product $product, int $quantity): void;
}
