<?php

declare(strict_types=1);

namespace App\Contracts\Services;

use App\Models\Product;
use Illuminate\Support\Collection;

interface Searchable
{
    /**
     * Search for items based on branch ID and a query string.
     *
     * @param  int  $branchId  The ID of the branch where the product belongs.
     * @param  string  $query  The search query string (product name).
     * @return Collection<int, Product> The search results as a collection.
     */
    public function search(int $branchId, string $query): Collection;
}
