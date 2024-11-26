<?php

declare(strict_types=1);

namespace App\Http\Controllers\Invokable;

use App\Contracts\Services\ProcessInvoice;
use App\Contracts\Services\ProvidesLatestInvoice;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

final class InvoiceProcessingController
{
    public function __construct(private ProcessInvoice $invoice, private ProvidesLatestInvoice $latestUserInvoice) {}

    /**
     * Handle adding a product to an invoice.
     */
    public function __invoke(Request $request, Product $product): JsonResponse
    {
        // TODO refactor cache keys into enums or constant class to avoid errors
        $invoice = Cache::get('current_invoice');

        // Get the authenticated user
        $user = Auth::user();

        // If the invoice is not found in the cache, retrieve the latest created invoice for the user
        if (! $invoice) {
            /** @phpstan-ignore-next-line */
            $invoice = $this->latestUserInvoice->latestCreatedInvoice($user);
        }
        /** @phpstan-ignore-next-line */
        $invoice = $this->invoice->processInvoice($invoice);

        return response()->success(__('app.operation_successful'), $invoice);
    }
}
