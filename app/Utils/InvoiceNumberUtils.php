<?php

declare(strict_types=1);

namespace App\Utils;

use RuntimeException;

final class InvoiceNumberUtils
{
    public const STRLEN_UNIQUE_INVOICE_NUMBER = 11;

    public const REC_ID = 'IN';

    /**
     * Generate an invoice number.
     *
     * Format: ABC99-IN22-123456
     * - Chars 1-3: Facility Account Code (e.g., "ABC" for "ABC Hospital").
     * - Chars 4-5: Branch Code (e.g., "99" for the Tema branch).
     * - Char 6: Separator '-'.
     * - Chars 7-8: Record identifier "IN" for invoice.
     * - Chars 9-10: Year of record entry in "YY" format (e.g., "22" for 2022).
     * - Char 11: Separator '-'.
     * - Chars 12-17: Unique numeric sequence, starting at 000001.
     *
     * @throws RuntimeException
     */
    public static function generate(string $facilityCode, string $facilityBranchCode, string $currentUniqueInvoiceNumber): string
    {
        $currentYear = date('y');

        self::validateUniqueInvoiceNumber($currentUniqueInvoiceNumber);

        $nextNumSeq = self::invoiceNumberSeq($currentUniqueInvoiceNumber) + 1;

        if ($currentYear > self::getYear(self::extractRecordIdentifier($currentUniqueInvoiceNumber))) {
            // Reset numSeq to 1
            $nextNumSeq = 1;
        }

        return self::make($facilityCode, $facilityBranchCode, self::REC_ID, self::makeUniqueInvoiceNumber($nextNumSeq));
    }

    /**
     * Extract the year from a record identifier.
     */
    public static function getYear(string $data): int
    {
        return (int) mb_substr($data, 2, 2);
    }

    /**
     * Extract the numeric sequence from the unique invoice number.
     *
     * @throws RuntimeException
     */
    public static function invoiceNumberSeq(string $uniqueInvoiceNumber): int
    {
        $num = explode('-', $uniqueInvoiceNumber)[1];

        if (! is_numeric($num)) {
            throw new RuntimeException("Numeric sequence for the unique Invoice number must be a number. '$uniqueInvoiceNumber' was provided.");
        }

        return (int) $num;
    }

    /**
     * Construct the full invoice number.
     */
    public static function make(string $facilityCode, string $facilityBranchCode, string $recordIdentifier, string $uniqueInvoiceNumber): string
    {
        return "{$facilityCode}{$facilityBranchCode}-".self::defineUniqueInvoiceNumber($recordIdentifier.date('y'), $uniqueInvoiceNumber);
    }

    /**
     * Extract the unique invoice number from a full invoice number.
     */
    public static function extractUniqueInvoiceNumber(string $invoiceNumber): string
    {
        $data = explode('-', $invoiceNumber);

        return self::defineUniqueInvoiceNumber($data[1] ?? '', $data[2] ?? '');
    }

    /**
     * Extract the record identifier from a unique invoice number.
     */
    public static function extractRecordIdentifier(string $currentUniqueInvoiceNumber): string
    {

        return explode('-', $currentUniqueInvoiceNumber)[0];
    }

    /**
     * Define the unique invoice number.
     */
    public static function defineUniqueInvoiceNumber(string $recId, string $num): string
    {

        return "{$recId}-{$num}";
    }

    /**
     * Create a unique invoice number sequence.
     */
    private static function makeUniqueInvoiceNumber(int $numSeq): string
    {
        return mb_str_pad((string) $numSeq, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Validate the unique invoice number.
     *
     * @throws RuntimeException
     */
    private static function validateUniqueInvoiceNumber(string $uniqueInvoiceNumber): void
    {
        if (mb_strlen($uniqueInvoiceNumber) !== self::STRLEN_UNIQUE_INVOICE_NUMBER) {
            throw new RuntimeException('Unique Invoice number should be '.self::STRLEN_UNIQUE_INVOICE_NUMBER." chars. '$uniqueInvoiceNumber' was provided.");
        }
    }
}
