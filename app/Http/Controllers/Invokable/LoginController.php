<?php

declare(strict_types=1);

namespace App\Http\Controllers\Invokable;

use App\Contracts\Services\AuthServiceInterface;
use App\Http\Requests\LoginUserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;

/**
 * Controller for handling user login.
 *
 * This controller uses an invokable approach to process user login requests,
 * validate credentials, and return an authentication token along with user details.
 */
final readonly class LoginController
{
    /**
     * Inject the authentication service.
     *
     * @param  AuthServiceInterface  $authService  The service handling authentication operations.
     */
    public function __construct(private AuthServiceInterface $authService) {}

    /**
     * Handle the incoming request.
     *
     * Validates user credentials and returns an authentication token along with the user resource.
     *
     * @param  LoginUserRequest  $request  The validated login request.
     * @return JsonResponse A JSON response containing the user resource and token.
     */
    public function __invoke(LoginUserRequest $request): JsonResponse
    {
        // Retrieve the validated user from the request
        $user = $request->validatedUser();

        // Authenticate the user and generate a token
        $results = $this->authService->login($user);

        // Transform the user into a resource
        $userResource = new UserResource($results['user']);
        $token = $results['token'];

        // Return a success response with user details and token
        return response()->success(
            __('app.login_successful'),
            [
                'user' => $userResource,
                'token' => $token,
            ]
        );
    }
}
