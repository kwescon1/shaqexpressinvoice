<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Contracts\Services\AddItem;
use App\Models\Invoice;
use App\Models\Product;
use App\Rules\VerifyProduct;
use Illuminate\Http\Request;

final class AddProductToInvoiceController
{
    public function __construct(private AddItem $invoiceService) {}

    /**
     * Handle adding a product to an invoice.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request, Invoice $invoice, Product $product)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id', new VerifyProduct($invoice)],
            'quantity' => 'required|integer|min:1',
        ]);

        $result = $this->invoiceService->addItem(
            $invoice,
            $product,
            $validated['quantity']
        );

        return response()->noContent();
    }
}
