<?php

/**
 * @see FirewallMiddleware
 */

declare(strict_types=1);

use App\Http\Middleware\FirewallMiddleware;
use Illuminate\Http\Response;

beforeEach(function (): void {
    $this->request = request();

    $this->middleware = resolve(FirewallMiddleware::class);
});

it('detects bots and blocks them', function (): void {
    // Set up a bot user-agent
    $this->request->server->set('HTTP_USER_AGENT', 'Googlebot/2.1 (+http://www.google.com/bot.html)');
    $this->request->headers->set('User-Agent', 'Googlebot/2.1 (+http://www.google.com/bot.html)');

    // Test bot detection
    $response = $this->middleware->handle($this->request, fn () => response('Allowed'));
    expect($response->status())->toBe(Response::HTTP_FORBIDDEN);
});

it('allows non-bots through', function (): void {
    $response = $this->middleware->handle($this->request, fn ($req) => response()->success('Allowed', []));

    // Assert the Content-Type is JSON
    expect($response->headers->get('Content-Type'))->toContain('application/json');

    expect($response->status())->toBe(Response::HTTP_OK);
});
