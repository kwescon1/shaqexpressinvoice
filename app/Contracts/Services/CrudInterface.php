<?php

declare(strict_types=1);

namespace App\Contracts\Services;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

/**
 * Interface CrudInterface
 *
 * General CRUD operations for API services.
 *
 * @template T of \Illuminate\Database\Eloquent\Model
 */
interface CrudInterface
{
    /**
     * Retrieve a paginated list of resources.
     *
     * @return LengthAwarePaginator<T> Paginated list of resources.
     */
    public function index(): LengthAwarePaginator;

    /**
     * Store a new resource.
     *
     * @param  User|null  $user  The user associated with the resource or null.
     * @param  array<string, mixed>|null  $data  Data for creating the resource or null.
     * @return T The newly created resource.
     */
    public function store(?User $user = null, ?array $data = null): Model;

    /**
     * Retrieve a specific resource.
     *
     * @param  T  $resource  The resource instance.
     * @return T The requested resource.
     */
    public function show(Model $resource): Model;

    /**
     * Update a specific resource.
     *
     * @param  T  $resource  The resource instance.
     * @param  array<string, mixed>  $data  Data for updating the resource.
     * @return T The updated resource.
     */
    public function update(Model $resource, array $data): Model;

    /**
     * Delete a specific resource.
     *
     * @param  T  $resource  The resource instance.
     */
    public function destroy(Model $resource): void;
}
