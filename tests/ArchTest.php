<?php

declare(strict_types=1);

arch()->preset()->php();

// arch()->preset()->strict();

arch()->preset()->security()->ignoring('assert');
// ->ignoring('App\Jobs\PerformDatabaseBackupJob'); // ignoring because of exec function;

arch()->preset()->laravel()->ignoring([App\Providers\AppServiceProvider::class, 'App\Http\Controllers']);

arch('strict types')
    ->expect('App')
    ->toUseStrictTypes();

arch('avoid open for extension')
    ->expect('App')
    ->classes()
    ->toBeFinal()
    ->ignoring([
        App\Providers\TelescopeServiceProvider::class,
        'App\Models\Base',
    ]);

arch('ensure no extends')
    ->expect('App')
    ->classes()
    ->not->toBeAbstract()
    ->ignoring('App\Models\Base');

arch('avoid mutation')
    ->expect('App')
    ->classes()
    ->toBeReadonly()
    ->ignoring([
        'App\Console\Commands',
        'App\Exceptions',
        'App\Http\Requests',
        'App\Jobs',
        'App\Http\Resources',
        'App\Mail',
        'App\Models',
        'App\Notifications',
        'App\Providers',
        App\Providers\TelescopeServiceProvider::class,
    ]);

arch('avoid inheritance')
    ->expect('App')
    ->classes()
    ->toExtendNothing()
    ->ignoring([
        'App\Console\Commands',
        'App\Exceptions',
        'App\Http\Requests',
        'App\Jobs',
        'App\Mail',
        'App\Models',
        'App\Notifications',
        'App\Providers',
        'App\Http\Resources',
    ]);

arch('annotations')
    ->expect('App')
    ->toHavePropertiesDocumented()
    ->toHaveMethodsDocumented();
