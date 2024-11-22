<?php

declare(strict_types=1);

arch('services')
    ->expect('App\Services')
    ->toHaveConstructor()->ignoring('App\Services\Auth')
    ->not->toBeAbstract()
    ->toOnlyBeUsedIn([
        'App\Http\Controllers',
        'App\Providers',
        'App\Services',
    ]);
