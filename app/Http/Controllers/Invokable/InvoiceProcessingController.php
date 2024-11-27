<?php

declare(strict_types=1);

namespace App\Http\Controllers\Invokable;

use App\Contracts\Builders\QueryInvoice;
use App\Contracts\Services\ProcessInvoice;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use LogicException;
use RuntimeException;

final class InvoiceProcessingController
{
    public function __construct(private ProcessInvoice $invoice, private QueryInvoice $latestUserInvoice) {}

    /**
     * Handle adding a product to an invoice.
     */
    public function __invoke(Request $request, Product $product): JsonResponse
    {
        // TODO refactor cache keys into enums or constant class to avoid errors
        $invoice = Cache::get('current_invoice');

        $user = Auth::user();

        if (! $user instanceof User) {
            throw new LogicException('User not user model instance');
        }

        $branch = $user->facilityBranches;

        // If the invoice is not found in the cache, retrieve the latest created invoice for the user
        if (! $invoice) {
            /** @phpstan-ignore-next-line */
            $invoice = $this->latestUserInvoice->lastSavedInvoice($branch);

            if (! $invoice) {
                throw new RuntimeException('No available invoice');
            }
        }

        $invoice = $this->invoice->processInvoice($invoice);

        return response()->success(__('app.operation_successful'), $invoice);
    }
}
