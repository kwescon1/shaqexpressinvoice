<?php

declare(strict_types=1);

use App\Models\Base;
use Illuminate\Database\Eloquent\Factories\Factory;

// Define the count variable globally for reuse
$count = 1;

arch('models')
    ->expect('App\Models')
    ->toHaveMethod('casts')
    ->ignoring('App\Models\Concerns')
    ->ignoring('App\Models\Base')
    ->toExtend(Base::class) // Ensure all models extend BaseModel
    ->ignoring(App\Models\User::class)
    ->ignoring('App\Models\Concerns')
    ->ignoring('App\Models\Base')
    ->toOnlyBeUsedIn([
        'App\Concerns',
        'App\Console',
        'App\Actions',
        'App\Http',
        'App\Jobs',
        'App\Observers',
        'App\Mail',
        'App\Models',
        'App\Notifications',
        'App\Policies',
        'App\Providers',
        'App\Rules',
        'App\Services',
        'App\Contracts',
        'Database\Factories',
        'Database\Seeders',
    ])
    ->ignoring('App\Models\Concerns');

arch('ensure factories', function () use ($count): void {
    // Pass $count into the closure
    $models = glob(__DIR__.'/../../app/Models/*.php') ?: [];
    $excludedModels = [
        'App\Models\Base', // Exclude Base as it is not directly instantiated
    ];

    $modelCollection = collect($models)
        ->map(fn ($file) => 'App\Models\\'.basename($file, '.php'))
        ->reject(fn ($model) => in_array($model, $excludedModels, true));

    // Use the count variable
    expect($modelCollection->count())->toBe($count);

    foreach ($modelCollection as $model) {
        if (in_array($model, $excludedModels, true)) {
            continue;
        }

        // Ensure that each model has an associated factory
        expect($model::factory())->toBeInstanceOf(Factory::class);
    }
});

arch('ensure datetime casts', function () use ($count): void {
    // Pass $count into the closure
    $models = glob(__DIR__.'/../../app/Models/*.php') ?: [];
    $excludedModels = [
        'App\Models\Base', // Exclude BaseModel as it is not directly instantiated
    ];

    $modelCollection = collect($models)
        ->map(fn ($file) => 'App\Models\\'.basename($file, '.php'))
        ->reject(fn ($model) => in_array($model, $excludedModels, true));

    // Use the count variable
    expect($modelCollection->count())->toBe($count);

    foreach ($modelCollection as $model) {
        if (in_array($model, $excludedModels, true)) {
            continue;
        }

        // Create an instance of each model
        $instance = $model::factory()->create();

        // Identify and test all datetime fields
        $dates = collect($instance->getAttributes())
            ->filter(fn ($_, $key) => str_ends_with($key, '_at'));

        // Assert that each datetime attribute has the correct cast
        foreach ($dates as $key => $value) {
            expect($instance->getCasts())->toHaveKey($key, 'datetime');
        }
    }
});
