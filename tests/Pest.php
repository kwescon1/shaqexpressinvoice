<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| You may extend TestCase to apply specific setup for all tests in specific
| directories or globally. Pest uses `beforeEach` to make configurations available.
|
*/

// Apply TestCase to both Feature and Unit tests
pest()->extend(TestCase::class);

// Apply RefreshDatabase only to Feature tests (or other specific test directories that need database)
uses(RefreshDatabase::class)->in('Feature', 'Arch');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions.
| The "expect()" function gives you access to a set of "expectations" methods that you
| can use to assert different things.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| Here you can expose helpers as global functions to help you reduce the number
| of lines of code in your test files.
|
*/

function something()
{
    // ..
}
