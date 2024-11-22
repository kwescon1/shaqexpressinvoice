<?php

declare(strict_types=1);

arch('exceptions')
    ->expect('App\Exceptions')
    ->toImplement('Throwable')
    ->toOnlyBeUsedIn([
        'App\Console\Commands',
        'App\Services',
        'App\Requests',
        'App\Jobs',
    ]);
