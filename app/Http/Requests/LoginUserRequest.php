<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

final class LoginUserRequest extends FormRequest
{
    /**
     * Store the user object after validation.
     */
    protected ?User $user = null;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email|max:255',
            'password' => 'required|string|max:255',
        ];
    }

    /**
     * Retrieve the validated email input.
     */
    public function getSanitizedEmailInput(): string
    {
        return (string) $this->validated()['email'];
    }

    /**
     * Retrieve the validated password input.
     */
    public function getValidatedPassword(): string
    {
        return (string) $this->validated()['password'];
    }

    /**
     * Find the user by the email field.
     *
     * This method uses the email input to search for the user in the database.
     * If a user is found, it returns the user object; otherwise, it returns null.
     */
    public function findUser(): ?User
    {
        return User::where('email', $this->getSanitizedEmailInput())->first();
    }

    /**
     * Check if the provided password matches the stored password.
     */
    public function checkPassword(User $user): bool
    {
        return Hash::check($this->getValidatedPassword(), $user->password);
    }

    /**
     * Validate the user's credentials.
     *
     * @throws AuthenticationException
     */
    public function validateUserCredentials(): void
    {
        $user = $this->findUser();

        // Check if the user exists and the password matches
        if (! $user || ! $this->checkPassword($user)) {
            throw new AuthenticationException(__('auth.failed'));
        }

        // Store the validated user
        $this->user = $user;
    }

    /**
     * Get the validated user.
     */
    public function validatedUser(): User
    {
        // Ensure credentials are validated before returning the user
        if (! $this->user) {
            $this->validateUserCredentials();
        }

        return $this->user;
    }

    /**
     * Prepare the data for validation.
     *
     * This method is called before the validation rules are applied.
     * It sanitizes the email input to ensure the data is in the correct format before validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'email' => trim(mb_strtolower((string) $this->input('email'))),
        ]);
    }
}
