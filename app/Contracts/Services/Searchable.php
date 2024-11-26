<?php

declare(strict_types=1);

namespace App\Contracts\Services;

use App\Models\Branch;
use App\Models\Product;
use Illuminate\Support\Collection;

interface Searchable
{
    /**
     * Search for items based on the branch and a query string.
     *
     * @param  Branch  $branch  The branch where the product belongs.
     * @param  string  $query  The search query string (product name).
     * @return Collection<int, Product> The search results as a collection.
     */
    public function search(Branch $branch, string $query): Collection;
}
