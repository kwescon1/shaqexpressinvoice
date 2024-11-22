<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Contracts\Services\DetectBotInterface;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final readonly class FirewallMiddleware
{
    /**
     * Inject the Firewall service into the middleware.
     *
     * @see \Tests\Feature\FirewallMiddlewareTest
     */
    public function __construct(private DetectBotInterface $firewall) {}

    /**
     * Handle the incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Use the Firewall service to detect bots
        if ($this->firewall->isBot($request)) {
            return response()->simplerror(__('app.access_denied'), Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
