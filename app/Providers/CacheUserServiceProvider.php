<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\Auth\CachedUserProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

final class CacheUserServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Auth::provider('cached', function ($app, array $config) {
            return new CachedUserProvider($app['hash'], $config['model']);
        });
    }
}
