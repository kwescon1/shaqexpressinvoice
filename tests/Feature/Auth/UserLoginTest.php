<?php

use App\Models\User;
use Illuminate\Support\Str;

beforeEach(function () {
    // Generate random password
    $this->password = Str::password();

    // Prepare user data for testing
    $this->user = User::factory()->make(['password' => $this->password])->makeVisible('password')->toArray();

    // Helper function for login request
    $this->loginUser = function (array $data) {
        return $this->postJson(route('api.auth.login'), $data);
    };

    // Helper function for login data
    $this->loginData = fn($email, $password) => ['email' => $email, 'password' => $password];

    // Create user in the database for all tests
    User::create($this->user);
});

it('logs in a user, generates token, and returns a login successful message', function () {
    $this->assertDatabaseHas('users', [
        'name' => $this->user['name'],
    ]);

    $response = ($this->loginUser)(($this->loginData)($this->user['email'], $this->password));

    $response->assertOk()
        ->assertJsonPath('message', __('app.login_successful'));

    // Assert the structure of the response
    expect($response->json('data'))->toHaveKeys(['user', 'token']);
});


it('throws an unauthorized error when input is invalid', function () {
    $response = ($this->loginUser)(($this->loginData)($this->user['email'], Str::random(12)));

    $response->assertUnauthorized()->assertJsonPath('error', __('auth.failed'));
});
