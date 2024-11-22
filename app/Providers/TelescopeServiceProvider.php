<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Laravel\Telescope\IncomingEntry;
use Laravel\Telescope\Telescope;
use Laravel\Telescope\TelescopeApplicationServiceProvider;

final class TelescopeServiceProvider extends TelescopeApplicationServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Telescope::night();

        if ($this->app->environment('local') && $this->app->runningInConsole()) {
            config(['telescope.enabled' => false]);
        }

        $this->hideSensitiveRequestDetails();

        $isLocal = $this->app->environment('local');

        Telescope::filter(function (IncomingEntry $incomingEntry) use ($isLocal): bool {
            if ($isLocal) {
                return true;
            }
            if ($incomingEntry->isReportableException()) {
                return true;
            }
            if ($incomingEntry->isFailedRequest()) {
                return true;
            }
            if ($incomingEntry->isFailedJob()) {
                return true;
            }
            if ($incomingEntry->isScheduledTask()) {
                return true;
            }

            return $incomingEntry->hasMonitoredTag();
        });
    }

    /**
     * Prevent sensitive request details from being logged by Telescope.
     */
    protected function hideSensitiveRequestDetails(): void
    {
        if ($this->app->environment('local')) {
            return;
        }

        Telescope::hideRequestParameters(['_token']);

        Telescope::hideRequestHeaders([
            'cookie',
            'x-csrf-token',
            'x-xsrf-token',
        ]);
    }

    /**
     * Register the Telescope gate.
     *
     * This gate determines who can access Telescope in non-local environments.
     */
    protected function gate(): void
    {
        Gate::define('viewTelescope', fn (User $user): bool => in_array($user->email, [
            //
        ]));
    }
}
