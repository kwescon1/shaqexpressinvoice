<?php

declare(strict_types=1);

namespace App\Http\Controllers\Invokable;

use App\Contracts\Services\Searchable;
use App\Http\Requests\SearchProduct;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use LogicException;

final class ProductSearchController
{
    public function __construct(private Searchable $product) {}

    /**
     * Handle the incoming request.
     */
    public function __invoke(SearchProduct $request): JsonResponse
    {
        // Get the currently authenticated user
        $user = Auth::user();

        if (! $user instanceof User) {
            throw new LogicException('The authenticated user is not a valid User instance.');
        }

        $branch = $user->facilityBranches;

        // Access the sanitized 'product' parameter
        $product = (string) $request->validated()['product'];

        // Perform the search
        $results = $this->product->search($branch, $product);

        return response()->json($results);
    }
}
