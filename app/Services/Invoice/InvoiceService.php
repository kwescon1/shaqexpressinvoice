<?php

declare(strict_types=1);

namespace App\Services\Invoice;

use App\Contracts\Services\CrudInterface;
use App\Contracts\Services\ManagesItem;
use App\Contracts\Services\ProcessInvoice;
use App\Enums\InvoiceStatus;
use App\Exceptions\UniqueConstraintException;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\User;
use Facades\App\Contracts\Services\GeneratesInvoiceNumber;
use Facades\App\Contracts\Services\Sellable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use LogicException;
use RuntimeException;

use function Illuminate\Support\enum_value;

/**
 * @implements CrudInterface<Invoice>
 */
final class InvoiceService implements CrudInterface, ManagesItem, ProcessInvoice
{
    /**
     * Create a new Invoice instance.
     *
     * @param  Invoice  $invoice  The Invoice model.
     * @param  int  $perPage  Number of items per page for pagination.
     */
    public function __construct(private Invoice $invoice, private int $perPage = 20) {}

    public function store(?User $user = null, ?array $data = null): Invoice
    {
        if (! $user instanceof User) {
            throw new LogicException('The authenticated user is not a valid User instance.');
        }

        $facility = $user->facility;
        $branch = $user->facilityBranches;

        if ($facility === null) {
            throw new LogicException('The user is not associated with a valid facility.');
        }

        if ($branch === null) {
            throw new LogicException('The user is not associated with a valid branch.');
        }

        // Generate the invoice number
        $invoiceNumber = GeneratesInvoiceNumber::generateInvoiceNumber($facility, $branch);

        // Create the invoice using the relationship
        return $user->invoices()->create([
            'invoice_number' => $invoiceNumber,
            'facility_id' => $facility->getKey(),
            'branch_id' => $branch->getKey(),
        ]);
    }

    /**
     * @return LengthAwarePaginator<Invoice>
     */
    public function index(): LengthAwarePaginator
    {
        return $this->invoice::query()->paginate($this->perPage);
    }

    public function show(Model $invoice): Invoice
    {
        // Ensure the provided model is an Invoice instance
        return $this->ensureInvoice($invoice);
    }

    public function update(Model $invoice, array $data): Invoice
    {
        // Ensure the provided model is an Invoice instance
        $invoice = $this->ensureInvoice($invoice);

        // Update the invoice with the provided data
        $invoice->update($data);

        return $invoice;
    }

    public function destroy(Model $invoice): void
    {
        // Ensure the provided model is an Invoice instance
        $invoice = $this->ensureInvoice($invoice);

        // Delete the invoice
        $invoice->delete();
    }

    /**
     * Add a product to the invoice with a default quantity of 1.
     *
     * @param  Invoice  $invoice  The invoice to add the product to.
     * @param  Product  $product  The product to add.
     * @param  int  $quantity  The quantity to add (default: 1).
     *
     * @throws RuntimeException If the stock quantity is insufficient.
     * @throws UniqueConstraintViolationException If the product is already added to the invoice.
     */
    public function addItem(Invoice $invoice, Product $product, int $quantity = 1): void
    {
        $stock = $product->stock;

        // Verify stock availability and get retail price
        $retailPrice = $this->verifyStock($product, $quantity);

        try {
            // Add product to the invoice
            $invoice->products()->attach($product, [
                'quantity' => $quantity,
                'price' => $retailPrice, // Price from stock table
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (QueryException $e) {
            // Check if it's a unique constraint violation
            if ($e instanceof UniqueConstraintViolationException) {
                $productName = $product->name ?? 'Unknown Product';

                // Throw the custom UniqueConstraintException
                throw new UniqueConstraintException("The product {$productName} has already been added to the invoice. Consider updating the quantity instead");
            }
            // Log the exception for debugging
            Log::error('Query exception caught while attaching product to invoice', [
                'exception_type' => get_class($e),
                'message' => $e->getMessage(),
                'invoice_id' => $invoice->id,
                'product_id' => $product->id,
                'trace' => $e->getTraceAsString(),
            ]);

            // Re-throw the exception for other cases
            throw $e;
        }
    }

    /**
     * Remove a product from an invoice.
     *
     * @param  Invoice  $invoice  The invoice to remove the product from.
     * @param  Product  $product  The product to remove.
     */
    public function removeItem(Invoice $invoice, Product $product): void
    {

        try {
            // Add product to the invoice
            $invoice->products()->detach($product);
        } catch (QueryException $e) {
            // Log the exception for debugging
            Log::error('Query exception caught while attaching product to invoice', [
                'exception_type' => get_class($e),
                'message' => $e->getMessage(),
                'invoice_id' => $invoice->id,
                'product_id' => $product->id,
                'trace' => $e->getTraceAsString(),
            ]);

            // Re-throw the exception for other cases
            throw $e;
        }
    }

    /**
     * Remove all products from an invoice.
     *
     * @param  Invoice  $invoice  The invoice to remove the products from.
     */
    public function removeItems(Invoice $invoice): void
    {
        $invoice->products()->detach();
    }

    /**
     * Update the quantity of a product in an invoice.
     */
    public function updateItemQuantity(Invoice $invoice, Product $product, int $quantity): void
    {

        // Retrieve the existing pivot record for the specific product
        $item = $this->itemExists($invoice, $product);

        if (! $item) {
            throw new RuntimeException('Product not on invoice');
        }

        // Verify stock availability and get retail price
        $retailPrice = $this->verifyStock($product, $quantity);

        DB::transaction(function () use ($invoice, $item, $quantity, $retailPrice): void {

            // Update the quantity in the pivot table
            $invoice->products()->updateExistingPivot($item, [
                'quantity' => $quantity,
                'price' => $quantity * $retailPrice,
            ]);
        });
    }

    /**
     * Process an invoice.
     */
    public function processInvoice(Invoice $invoice): Invoice
    {
        // Check if the invoice is open
        if (! $invoice->isOpen()) {
            throw new RuntimeException(__('The invoice is not open for processing.'));
        }

        // Eager load products and their stock relationships
        $invoiceWithRelations = $invoice->load('products.stock');

        return DB::transaction(function () use ($invoiceWithRelations): Invoice {
            // Sum the `price` column from the pivot table
            $totalPrice = $invoiceWithRelations->products->sum(function ($product) {
                return $product->pivot->price;
            });

            // Adjust stock for each product
            foreach ($invoiceWithRelations->products as $product) {
                $stock = $product->stock;

                if ($stock) {
                    // Capture the current stock level before decrementing
                    $previousStockLevel = $stock->quantity;

                    // Perform the stock decrement
                    $stock->decrement('quantity', $product->pivot->quantity);

                    // Update the product's stock-related columns
                    $product->update([
                        'last_updated_restock_level' => $previousStockLevel,
                        'current_stock_level' => $stock->quantity, // The new stock level after decrement
                    ]);
                }
            }

            // Update the invoice with the calculated totals
            $invoiceWithRelations->update([
                'total' => $totalPrice,
                'status' => enum_value(InvoiceStatus::FINALIZED),
            ]);

            $refreshed = $invoiceWithRelations->refresh();

            Sellable::soldProducts($invoiceWithRelations);

            // Detach all products from the invoice using
            $this->removeItems($invoiceWithRelations);

            // Return the refreshed invoice
            return $refreshed;
        });
    }

    /**
     * Ensure the given model is an instance of Invoice.
     */
    private function ensureInvoice(Model $model): Invoice
    {
        if (! $model instanceof Invoice) {
            throw new LogicException('The provided model is not an instance of Invoice.');
        }

        return $model;
    }

    /**
     * Verify stock availability and return the retail price.
     *
     * @param  Product  $product  The product to check.
     * @param  int  $quantity  The quantity to verify.
     * @return float The retail price of the product.
     *
     * @throws RuntimeException If the stock is insufficient.
     */
    private function verifyStock(Product $product, int $quantity): float
    {
        $stock = $product->stock;

        if ($stock === null || $quantity > $stock->quantity) {
            throw new RuntimeException(
                __('Insufficient quantity available for product: :productName', [
                    'productName' => $product->name ?? 'Unknown Product',
                ])
            );
        }

        return $stock->retail_price;
    }

    /**
     * Retrieve the product from the invoice if it exists.
     *
     * @param  Invoice  $invoice  The invoice to check.
     * @param  Product  $product  The product to retrieve.
     * @return Product|null The product instance with pivot data if it exists, null otherwise.
     */
    private function itemExists(Invoice $invoice, Product $product): ?Product
    {
        return $invoice->products()->whereKey($product)->first();
    }
}
