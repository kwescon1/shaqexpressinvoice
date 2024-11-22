<?php

declare(strict_types=1);

namespace App\Providers;

use App\Contracts\Services\AuthServiceInterface;
use App\Contracts\Services\DetectBotInterface;
use App\Services\Auth\AuthService;
use App\Services\Firewall;
use Illuminate\Support\ServiceProvider;

final class BindingServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(DetectBotInterface::class, Firewall::class);
        $this->app->bind(AuthServiceInterface::class, AuthService::class);
    }
}
