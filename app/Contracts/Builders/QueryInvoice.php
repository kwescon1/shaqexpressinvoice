<?php

declare(strict_types=1);

namespace App\Contracts\Builders;

use App\Models\Branch;
use App\Models\Invoice;

/**
 * Contract for querying invoice-related data.
 */
interface QueryInvoice
{
    /**
     * Retrieve the last saved invoice for a given branch.
     *
     * @param  Branch  $branch  The branch for which to retrieve the last saved invoice.
     * @return Invoice|null The last saved invoice or null if none exists.
     */
    public function lastSavedInvoice(Branch $branch): ?Invoice;

    /**
     * Verify whether a given invoice number is unique for a branch.
     *
     * @param  string  $invoiceNumber  The invoice number to verify.
     * @param  Branch  $branch  The branch to check against.
     * @return bool True if the invoice number exists for the branch, false otherwise.
     */
    public function verifyInvoiceNumber(string $invoiceNumber, Branch $branch): bool;
}
