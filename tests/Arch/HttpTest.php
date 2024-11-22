<?php

declare(strict_types=1);

arch('controllers')
    ->expect('App\Http\Controllers')
    ->toExtendNothing()
    ->toHaveMethods([
        'index',
        'update',
        'show',
        'destroy',
    ])->ignoring('App\Http\Controllers\Invokable');

arch('invokable controllers')
    ->expect('App\Http\Controllers\Invokable') // Specifically target invokable controllers
    ->toExtendNothing()
    ->toHaveMethod('__invoke'); // Ensure they have only the __invoke method

arch('middleware')
    ->expect('App\Http\Middleware')
    ->toHaveMethod('handle')
    ->toUse(Illuminate\Http\Request::class)
    ->not->toBeUsed();

arch('requests')
    ->expect('App\Http\Requests')
    ->toExtend(Illuminate\Foundation\Http\FormRequest::class)
    ->toHaveMethod('rules')
    ->toBeUsedIn('App\Http\Controllers');
