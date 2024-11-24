<?php

declare(strict_types=1);

namespace App\Services\Invoice;

use App\Contracts\Services\AddItem;
use App\Contracts\Services\CrudInterface;
use App\Contracts\Services\UpdateInvoiceItemQuantity;
use App\Models\Invoice;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use RuntimeException;

final class InvoiceService implements AddItem, CrudInterface, UpdateInvoiceItemQuantity
{
    /**
     * Create a new Invoice instance.
     *
     * @param  Invoice  $invoice  The Invoice model.
     * @param  int  $perPage  Number of items per page for pagination.
     */
    public function __construct(private Invoice $invoice, int $perPage = 20) {}

    public function store(): Invoice
    {
        // Get the authenticated user
        $user = auth()->user();

        // Create the invoice using the relationship
        $invoice = $user->invoices()->create();

        return $invoice;
    }

    public function index(?int $perPage = 15): LengthAwarePaginator
    {
        // TODO
    }

    public function show(Invoice $invoice): Invoice
    {

        // TODO
    }

    public function update(Model $resource, array $data): Invoice
    {
        // TODO
    }

    public function destroy(Model $resource): void
    {
        // TODO
    }

    /**
     * Add a product to the invoice with a default quantity of 1.
     *
     * @param  Invoice  $invoice  The invoice to add the product to.
     * @param  Product  $product  The product to add.
     * @param  int  $quantity  The quantity to add (default: 1).
     */
    public function addItem(Invoice $invoice, Product $product, int $quantity = 1): void
    {
        $stock = $product->stock;

        // Check if stock is sufficient
        if ($quantity > $stock->quantity) {
            throw new RuntimeException(__('Insufficient Quantity'));
        }

        DB::transaction(function () use ($invoice, $product, $quantity) {
            // Deduct stock
            $product->stock->decrement('quantity', $quantity);

            // Add product to invoice
            $invoice->products()->attach($product, [
                'quantity' => $quantity,
                'price' => $product->stock->retail_price, // Price from stock table
            ]);
        });
    }

    /**
     * Update the quantity of a product in an invoice.
     */
    public function updateItemQuantity(Invoice $invoice, Product $product, int $quantity): void
    {
        $stock = $product->stock;
        $productKey = $product->getKey();

        // Retrieve the existing pivot record
        $existingItem = $invoice->products()->where('product_id', $productKey)->first();

        if (! $existingItem) {
            throw new RuntimeException(__('The product is not part of the invoice.'));
        }

        $existingQuantity = $existingItem->pivot->quantity;

        // Calculate the stock difference
        $difference = $quantity - $existingQuantity;

        // Check if sufficient stock is available for the update
        if ($difference > $stock->quantity) {
            throw new RuntimeException(__('Insufficient stock for the requested update.'));
        }

        DB::transaction(function () use ($invoice, $product, $quantity, $difference, $stock) {
            // Update stock
            if ($difference > 0) {
                $stock->decrement('quantity', $difference);
            } elseif ($difference < 0) {
                $stock->increment('quantity', abs($difference));
            }

            // Update the quantity in the pivot table
            $invoice->products()->updateExistingPivot($product, [
                'quantity' => $quantity,
            ]);
        });
    }
}
