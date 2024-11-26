<?php

declare(strict_types=1);

namespace App\Services\Product;

use App\Contracts\Services\Searchable;
use App\Models\Branch;
use App\Models\Product;
use Illuminate\Support\Collection;

final class ProductService implements Searchable
{
    /**
     * ProductService constructor.
     *
     * @param  Product  $product  The Product model instance.
     */
    public function __construct(private Product $product)
    {
        // Constructor injection for the Product model
    }

    /**
     * Search for products based on branch and name.
     *
     * @param  Branch  $branch  The branch where the product belongs.
     * @param  string  $query  The search query string (product name).
     * @return Collection<int, Product> The search results as a collection.
     */
    public function search(Branch $branch, string $query): Collection
    {
        return $this->product
            ->newQuery()
            ->whereBelongsTo($branch) // Filter by the branch relationship
            ->where('name', 'like', '%'.$query.'%') // Search by name
            ->get(); // Return as a collection
    }
}
