<?php

declare(strict_types=1);

arch('enums')
    ->expect('App\Enums')
    ->toBeEnums()
    ->toExtendNothing()
    ->toImplement(Illuminate\Contracts\Support\DeferringDisplayableValue::class)
    ->toHaveMethod('resolveDisplayableValue')
    ->toOnlyBeUsedIn([
        'App\Console\Commands',
        'App\Http\Requests',
        'App\Models',
        'App\Jobs',
        'Database\Seeders',
        //will probably extend it to more folders
    ]);
