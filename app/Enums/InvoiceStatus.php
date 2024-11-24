<?php

declare(strict_types=1);

namespace App\Enums;

use Illuminate\Contracts\Support\DeferringDisplayableValue;

/**
 * Enum representing invoice statuses in the application.
 */
enum InvoiceStatus: string implements DeferringDisplayableValue
{
    /**
     * Open status for invoices still in progress.
     */
    case OPEN = 'open';

    /**
     * Finalized status for completed invoices.
     */
    case FINALIZED = 'finalized';

    /**
     * Cancelled status for invoices that are voided.
     */
    case CANCELLED = 'cancelled';

    /**
     * Check if the invoice is Open.
     *
     * @return bool True if the invoice is Open, false otherwise.
     */
    public function isOpen(): bool
    {
        return $this === self::OPEN;
    }

    /**
     * Check if the invoice is Finalized.
     *
     * @return bool True if the invoice is Finalized, false otherwise.
     */
    public function isFinalized(): bool
    {
        return $this === self::FINALIZED;
    }

    /**
     * Check if the invoice is Cancelled.
     *
     * @return bool True if the invoice is Cancelled, false otherwise.
     */
    public function isCancelled(): bool
    {
        return $this === self::CANCELLED;
    }

    /**
     * Resolve a human-readable display value for the status.
     *
     * @return string The formatted display name of the status.
     */
    public function resolveDisplayableValue(): string
    {
        return match ($this) {
            self::OPEN => 'Open',
            self::FINALIZED => 'Finalized',
            self::CANCELLED => 'Cancelled',
        };
    }

    /**
     * Get the description for each status.
     *
     * Provides a detailed description of what each status entails.
     *
     * @return string The description of the status.
     */
    public function getDescription(): string
    {
        return match ($this) {
            self::OPEN => 'Invoice is currently open and being processed.',
            self::FINALIZED => 'Invoice has been completed and finalized.',
            self::CANCELLED => 'Invoice has been cancelled and is no longer valid.',
        };
    }
}
