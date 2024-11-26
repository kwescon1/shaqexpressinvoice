<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use LogicException;

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
     * Retrieve the sanitized email input.
     */
    public function sanitizedEmail(): string
    {
        return (string) $this->validated()['email'];
    }

    /**
     * Retrieve the validated password input.
     */
    public function validatedPassword(): string
    {
        return (string) $this->validated()['password'];
    }

    /**
     * Find the user by the sanitized email field.
     *
     * @return User|null The user object if found, or null otherwise.
     */
    public function findUser(): ?User
    {
        return User::whereEmail($this->sanitizedEmail())->first();
    }

    /**
     * Check if the provided password matches the stored hashed password.
     *
     * @param  User  $user  The user whose password is being validated.
     * @return bool Whether the passwords match.
     */
    public function validatePassword(User $user): bool
    {
        return Hash::check($this->validatedPassword(), (string) $user->password);
    }

    /**
     * Validate the user's credentials.
     *
     * @throws AuthenticationException If the user is not found or the password is invalid.
     */
    public function validateCredentials(): void
    {
        $user = $this->findUser();

        if (! $user || ! $this->validatePassword($user)) {
            throw new AuthenticationException(__('auth.failed'));
        }

        $this->user = $user;
    }

    /**
     * Retrieve the validated user after ensuring credentials are valid.
     *
     * @return User The validated user.
     */
    public function validatedUser(): User
    {
        if (! $this->user) {
            $this->validateCredentials();
        }

        if (! $this->user instanceof User) {
            throw new LogicException('Expected an instance of User, but none was found.');
        }

        return $this->user;
    }

    /**
     * Prepare the data for validation by sanitizing the email input.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'email' => trim(mb_strtolower((string) $this->input('email'))),
        ]);
    }
}
