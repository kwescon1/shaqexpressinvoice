<?php

declare(strict_types=1);

namespace App\Enums;

use Illuminate\Contracts\Support\DeferringDisplayableValue;

/**
 * Enum representing user roles in the application.
 */
enum UserRole: string implements DeferringDisplayableValue
{
    /**
     * Admin role with full access.
     */
    case ADMIN = 'admin';

    /**
     * Salesperson role with limited access.
     */
    case SALESPERSON = 'salesperson';

    /**
     * Check if the role is Admin.
     *
     * @return bool True if the role is Admin, false otherwise.
     */
    public function isAdmin(): bool
    {
        return $this === self::ADMIN;
    }

    /**
     * Check if the role is Salesperson.
     *
     * @return bool True if the role is Salesperson, false otherwise.
     */
    public function isSalesperson(): bool
    {
        return $this === self::SALESPERSON;
    }

    /**
     * Resolve a human-readable display value for the role.
     *
     * @return string The formatted display name of the role.
     */
    public function resolveDisplayableValue(): string
    {
        return match ($this) {
            self::ADMIN => 'Administrator',
            self::SALESPERSON => 'Salesperson',
        };
    }

    /**
     * Get the description for each role.
     *
     * Provides a detailed description of what each role entails.
     *
     * @return string The description of the role.
     */
    public function getDescription(): string
    {
        return match ($this) {
            self::ADMIN => 'Administrator with full access to the system.',
            self::SALESPERSON => 'Salesperson with access to manage sales and customer interactions.',
        };
    }
}
