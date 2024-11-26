<?php

declare(strict_types=1);

namespace App\Contracts\Services;

use App\Models\User;

/**
 * Interface AuthServiceInterface
 *
 * Defines the contract for authentication services, including user login functionality.
 */
interface AuthServiceInterface
{
    /**
     * Login an existing user and generate an authentication token.
     *
     * @param  User  $user  The authenticated user.
     * @return array{user: User, token: string} An array containing the user resource and the authentication token.
     */
    public function login(User $user): array;
}
