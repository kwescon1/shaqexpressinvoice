<?php

declare(strict_types=1);

namespace App\Services\Product;

use App\Contracts\Services\Sellable;
use App\Models\Invoice;
use Illuminate\Support\Facades\DB;
use RuntimeException;

final class SoldProductService implements Sellable
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function soldProducts(Invoice $invoice): void
    {

        // Ensure the invoice has products
        if ($invoice->products->isEmpty()) {
            throw new RuntimeException('No products found for this invoice.');
        }

        // Prepare the data for batch insertion
        $soldProductsData = $invoice->products->map(function ($product) use ($invoice) {
            return [
                'uuid' => \Illuminate\Support\Str::uuid()->toString(),
                'invoice_id' => $invoice->getKey(), // Use getKey() for the primary key of the invoice
                'product_id' => $product->getKey(), // Use getKey() for the primary key of the product
                'quantity' => $product->pivot->quantity,
                'price' => $product->pivot->price,
            ];
        })->toArray();

        // Perform batch insertion into the sold_products table
        DB::table('sold_products')->insert($soldProductsData);
    }
}
