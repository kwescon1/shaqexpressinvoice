<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final readonly class EnforceJson
{
    /**
     * The required content type for incoming requests.
     *
     * @var string
     */
    private const CONTENT_TYPE = 'application/json';

    /**
     * Handle an incoming request.
     *
     * @param  Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->wantsJson()) {
            return response()->simplerror(__('validation.custom.json_content_type_required', ['type' => self::CONTENT_TYPE]), Response::HTTP_NOT_ACCEPTABLE);
        }

        return $next($request);
    }
}
