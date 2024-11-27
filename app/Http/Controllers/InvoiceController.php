<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Contracts\Services\CrudInterface;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use LogicException;

final class InvoiceController
{
    public function __construct(private CrudInterface $invoiceService)
    {
        // Dependency injection of CrudInterface
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Example implementation:
        // return $this->invoiceService->index();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        // Get the currently authenticated user
        $user = Auth::user();

        if (! $user instanceof User) {
            throw new LogicException('The authenticated user is not a valid User instance.');
        }

        $invoice = $this->invoiceService->store($user);

        return response()->created(__('app.resource_created'), $invoice);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Example implementation:
        // return $this->invoiceService->show($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Example implementation:
        $validated = $request->validate([
            // Add validation rules
        ]);

        return $this->invoiceService->update($id, $validated);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Example implementation:
        return $this->invoiceService->destroy($id);
    }
}
