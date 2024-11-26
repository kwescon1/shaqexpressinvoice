<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Support\Facades\Cache;

final class CachedUserProvider extends EloquentUserProvider
{
    /**
     * Retrieve a user by their unique identifier.
     *
     * @param  mixed  $identifier
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveById($identifier)
    {
        // Ensure the identifier is safely converted to a string
        $cacheKey = 'user_'.(string) $identifier;

        /** @phpstan-ignore-next-line */
        return Cache::remember(
            $cacheKey,                   // Use the safe cache key
            now()->addHour(),            // Cache expiration time
            function () use ($identifier) {
                $user = parent::retrieveById($identifier);

                // Ensure the returned instance is of the User type
                return $user instanceof User ? $user : null;
            }
        );
    }
}
