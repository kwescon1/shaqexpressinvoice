<?php

declare(strict_types=1);

namespace App\Services\Invoice;

use App\Contracts\Services\GeneratesInvoiceNumber;
use App\Contracts\Services\ProvidesLatestInvoice;
use App\Models\Branch;
use App\Models\Facility;
use App\Utils\InvoiceNumberUtils;
use Facades\App\Contracts\Builders\QueryInvoice;
use Illuminate\Support\Facades\Cache;

final class InvoiceNumberService implements GeneratesInvoiceNumber, ProvidesLatestInvoice
{
    /**
     * Generate a unique invoice number for a branch in a facility.
     *
     * @param  Facility  $facility  The facility associated with the branch.
     * @param  Branch  $branch  The branch for which to generate the invoice number.
     * @return string The generated invoice number.
     */
    public function generateInvoiceNumber(Facility $facility, Branch $branch): string
    {
        // Get the latest unique invoice number
        $latestUniqueInvoiceNumber = $this->latestUniqueInvoiceNumber($branch);
        $invoiceNumber = InvoiceNumberUtils::generate($facility->code, $branch->code, $latestUniqueInvoiceNumber);

        // Resolve conflicts iteratively
        if (QueryInvoice::verifyInvoiceNumber($invoiceNumber, $branch)) {
            // Reset the cache with the conflicting number
            $this->resetCachedUniqueInvoiceNumber($branch, $invoiceNumber);

            // Generate a new invoice number using the extracted unique number
            $invoiceNumber = InvoiceNumberUtils::generate(
                $facility->code,
                $branch->code,
                InvoiceNumberUtils::extractUniqueInvoiceNumber($invoiceNumber)
            );
        }

        return $invoiceNumber;
    }

    /**
     * Retrieve the latest unique invoice number for a branch.
     *
     * @param  Branch  $branch  The branch for which to retrieve the latest unique invoice number.
     * @return string The latest unique invoice number.
     */
    public function latestUniqueInvoiceNumber(Branch $branch): string
    {
        $cacheKey = self::CACHE_KEY_UNIQUE_INVOICE_NUMBER.(string) $branch->getKey();

        return (string) Cache::remember(
            $cacheKey,
            self::CACHE_SECONDS,
            function () use ($branch): string {
                $lastSavedRecord = QueryInvoice::lastSavedInvoice($branch);

                return $lastSavedRecord
                    ? (string) InvoiceNumberUtils::extractUniqueInvoiceNumber($lastSavedRecord->invoice_number)
                    : (string) InvoiceNumberUtils::defineUniqueInvoiceNumber(
                        InvoiceNumberUtils::REC_ID.date('y'),
                        '000001'
                    );
            }
        );
    }

    /**
     * Reset the cached unique invoice number for a branch.
     *
     * @param  Branch  $branch  The branch associated with the invoice.
     * @param  string  $invoiceNumber  The invoice number to cache.
     */
    private function resetCachedUniqueInvoiceNumber(Branch $branch, string $invoiceNumber): void
    {
        $cacheKey = self::CACHE_KEY_UNIQUE_INVOICE_NUMBER.(string) $branch->getKey();

        Cache::put($cacheKey, InvoiceNumberUtils::extractUniqueInvoiceNumber($invoiceNumber));
    }
}
