<?php

declare(strict_types=1);

arch('seeders')
    ->expect('Database\Seeders')
    ->toExtend(Illuminate\Database\Seeder::class)
    ->toHaveMethod('run')
    ->toOnlyBeUsedIn([
        'Database\Seeders',
        'App\Console\Commands',
    ]);
