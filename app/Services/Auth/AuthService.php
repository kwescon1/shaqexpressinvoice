<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Contracts\Services\AuthServiceInterface;
use App\Models\User;

/**
 * Service class for handling authentication-related operations.
 */
final readonly class AuthService implements AuthServiceInterface
{
    /**
     * Login an existing user and generate an authentication token.
     *
     * This method clears any previous tokens the user had, generates a new
     * authentication token, and returns the user and token.
     *
     * @param  User  $user  The authenticated user.
     * @return array{user: User, token: string} An array containing the user resource and the authentication token.
     */
    public function login(User $user): array
    {
        // Revoke all previous tokens
        $user->tokens()->delete();

        // Generate a new token
        $token = $this->generateUserToken($user);

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    /**
     * Generate an authentication token for the user.
     *
     * This method creates a new token for the user using Sanctum's token
     * creation method and returns the plain-text token.
     *
     * @param  User  $user  The user for whom the token is generated.
     * @return string The plain-text authentication token.
     */
    private function generateUserToken(User $user): string
    {
        return $user->createToken('auth_token')->plainTextToken;
    }
}
