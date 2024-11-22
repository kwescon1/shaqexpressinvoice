<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Routing\ResponseFactory;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

final class ResponseServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     */
    public function boot(ResponseFactory $responseFactory): void
    {
        JsonResource::withoutWrapping();

        $responseFactory->macro(
            'success',
            fn (?string $message = null, mixed $data = null) => response()->json([
                'message' => $message ?? __('app.operation_successful'),
                'data' => $data,
            ])
        );

        $responseFactory->macro('created', fn (?string $message = null, mixed $data = null) => response()->json([
            'message' => $message ?? __('app.resource_created'),
            'data' => $data ?: null,
        ], Response::HTTP_CREATED));

        $responseFactory->macro('notfound', fn (?string $error = null) => response()->json([
            'error' => $error ?? __('app.resource_not_found'),
        ], Response::HTTP_NOT_FOUND));

        $responseFactory->macro('simplerror', fn (?string $errorMessage = null, int $errorStatus = Response::HTTP_INTERNAL_SERVER_ERROR) => response()->json([
            'error' => $errorMessage ?? __('app.unexpected_error'),
        ], $errorStatus));

        $responseFactory->macro('error', fn (Throwable $error, int $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR) => match (true) {
            // If the error is a ValidationException
            $error instanceof ValidationException => response()->json([
                'message' => __('app.validation_failed'),
                'errors' => $error->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY),

            // If the error is an HttpException
            $error instanceof HttpException => response()->json([
                'error' => $error->getMessage(),
            ], $error->getStatusCode()),

            // Default error handling
            default => response()->json([
                'error' => $error->getMessage(),
            ], $statusCode),
        });
    }
}
