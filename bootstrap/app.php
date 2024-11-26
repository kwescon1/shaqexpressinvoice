<?php

declare(strict_types=1);

use App\Exceptions\UniqueConstraintException;
use App\Http\Middleware\EnforceJson;
use App\Http\Middleware\FirewallMiddleware;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'firewall' => FirewallMiddleware::class,
            'enforce_json' => EnforceJson::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->map(function (ModelNotFoundException $modelNotFound) {

            // Get the full class name of the model
            $modelClass = $modelNotFound->getModel();

            // Extract the model name from the class
            $modelName = class_basename($modelClass);

            $message = __('app.model_not_found', ['model' => $modelName]);

            return new NotFoundHttpException($message);
        });

        $exceptions->renderable(fn (NotFoundHttpException $notFoundHttpException) => response()->notfound($notFoundHttpException->getMessage()));

        $exceptions->renderable(fn (UniqueConstraintException $uniqueConstraintViolationException) => response()->simplerror($uniqueConstraintViolationException->getMessage(), Response::HTTP_CONFLICT));

        $exceptions->renderable(fn (AuthenticationException $authenticationException) => response()->simplerror($authenticationException->getMessage(), Response::HTTP_UNAUTHORIZED));

        $exceptions->renderable(function (Exception $exception) {
            Log::error($exception->getMessage()."\n".$exception->getTraceAsString());

            return response()->error($exception);
        });
    })->create();
