<?php

declare(strict_types=1);

namespace Database\Factories\Concerns;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Trait RefreshOnCreate
 *
 * Extends the behavior of the create method in a factory to automatically
 * refresh newly created model instances, ensuring that the returned data
 * is up-to-date with the database. This is particularly useful for tests
 * where fresh data (e.g., timestamps or database-calculated fields) is
 * immediately required after model creation.
 *
 * @mixin Factory<TModel>
 *
 * @template TModel of Model
 */
trait RefreshOnCreate
{
    /**
     * Overrides the default create method to refresh the model(s) after creation.
     * If a single model is created, it will be refreshed directly.
     * If multiple models are created (as a collection), each model in the
     * collection will be refreshed.
     *
     * @param  (callable(array<string, mixed>): array<string, mixed>)|array<string, mixed>  $attributes
     * @return Collection<int, TModel>|TModel
     */
    public function create($attributes = [], ?Model $parent = null): Model|Collection
    {
        // Apply state if attributes are provided as a callable or array
        $models = ! empty($attributes) ? $this->state($attributes)->create([], $parent) : parent::create($attributes, $parent);

        // Refresh each model or collection after creation
        return $models instanceof Model ? $models->refresh() : $models->map->refresh();
    }
}
