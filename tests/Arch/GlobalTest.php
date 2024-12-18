<?php

declare(strict_types=1);

arch('globals')
    ->expect(['dd', 'dump', 'die', 'var_dump', 'sleep', 'dispatch', 'dispatch_sync'])
    ->not->toBeUsed();

arch('http helpers')
    ->expect(['auth', 'request'])
    ->toOnlyBeUsedIn([
        'App\Http',
        'App\Rules',
    ]);
