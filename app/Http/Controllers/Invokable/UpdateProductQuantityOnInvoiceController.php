<?php

declare(strict_types=1);

namespace App\Http\Controllers\Invokable;

use App\Contracts\Services\ManagesItem;
use App\Contracts\Services\ProvidesLatestInvoice;
use App\Http\Requests\VerifyQuantity;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

final class UpdateProductQuantityOnInvoiceController
{
    public function __construct(private ManagesItem $manageInvoice, private ProvidesLatestInvoice $latestUserInvoice) {}

    /**
     * Handle adding a product to an invoice.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(VerifyQuantity $request, Product $product)
    {
        // TODO refactor cache keys into enums or constant class to avoid errors

        $quantity = $request->validated()['quantity'];

        $invoice = Cache::get('current_invoice');

        // Get the authenticated user
        $user = Auth::user();

        // If the invoice is not found in the cache, retrieve the latest created invoice for the user
        if (! $invoice) {
            /** @phpstan-ignore-next-line */
            $invoice = $this->latestUserInvoice->latestCreatedInvoice($user);
        }
        /** @phpstan-ignore-next-line */
        $this->manageInvoice->updateItemQuantity($invoice, $product, $quantity);

        return response()->noContent();
    }
}