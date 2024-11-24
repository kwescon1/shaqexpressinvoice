<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Contracts\Services\AuthServiceInterface;
use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use RuntimeException;

use function Illuminate\Support\enum_value;

/**
 * Service class for handling authentication-related operations.
 */
final readonly class AuthService implements AuthServiceInterface
{
    /**
     * Login an existing user and generate an authentication token.
     *
     * @param  User  $user  The authenticated user.
     * @return array{user: User, token: string} The user resource and the authentication token.
     */
    public function login(User $user): array
    {
        // Load necessary relationships
        $user->load('facilityBranches');

        logger($user);

        // Handle salesperson-specific logic
        if ($user->role === enum_value(UserRole::SALESPERSON)) {
            $branch = $this->handleSalespersonLogin($user);
            $this->cacheBranchData($user, $branch);
        } else {
            $this->cacheUserData($user);
        }

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
     * Handle salesperson login logic.
     *
     * @param  User  $user  The authenticated salesperson.
     * @return mixed The branch details for the logged-in salesperson.
     *
     * @throws RuntimeException If no branch is assigned.
     */
    private function handleSalespersonLogin(User $user): mixed
    {
        // Get the last logged-in branch or throw an exception
        $branch = $user->facilityBranches
            ->sortByDesc(fn ($branch) => $branch->pivot->last_login_at)
            ->first();

        if (! $branch) {
            throw new RuntimeException('No branch assigned');
        }

        // Update the pivot table for the current login
        $user->facilityBranches()->updateExistingPivot($branch->id, [
            'current_login_at' => now(),
            'last_login_at' => $branch->pivot->current_login_at,
        ]);

        return $branch;
    }

    /**
     * Generate an authentication token for the user.
     *
     * @param  User  $user  The user for whom the token is generated.
     * @return string The plain-text authentication token.
     */
    private function generateUserToken(User $user): string
    {
        return $user->createToken('auth_token')->plainTextToken;
    }

    /**
     * Cache branch-specific data for a salesperson.
     *
     * @param  User  $user  The authenticated user.
     * @param  mixed  $branch  The branch details from the pivot table.
     */
    private function cacheBranchData(User $user, $branch): void
    {
        Cache::put("user_{$user->id}_branch", [
            'branch_id' => $branch->id,
            'branch_name' => $branch->name,
            'facility_id' => $branch->facility_id,
        ], now()->addHours(1));
    }

    /**
     * Cache general user data.
     *
     * @param  User  $user  The authenticated user.
     */
    private function cacheUserData(User $user): void
    {
        Cache::put("user_{$user->id}_data", [
            'user_id' => $user->id,
            'role' => $user->role,
            'facility_id' => $user->facility_id,
        ], now()->addHours(1));
    }
}
