<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);
test('can register a user', function () {
    visit('/register')
        ->type('name', 'Test User')
        ->type('email', 'test@test.test')
        ->type('password', 'test@test.test')
        ->type('password_confirmation', 'test@test.test')
        ->press('Register')
        ->assertPathIs('/');

    $this->assertAuthenticated();

    expect(Auth::user()->name)->toBe('Test User');
});

test('can login a user', function () {
    $user = User::factory()->create(['email' => 'test@test.test', 'password' => 'test@test.test']);
    visit('/login')
        ->type('email', 'test@test.test')
        ->type('password', 'test@test.test')
        ->press('@login')
        ->assertPathIs('/');

    $this->assertAuthenticated();

    expect(Auth::user()->name)->toBe($user->name);
});

test('can logout a user', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    visit('/')->
        click('@logout')
        ->assertPathIs('/');

    $this->assertGuest();
});

test('can see the login page', function () {
    visit('/login')->assertSee('Log in');
});

test('can see the register page', function () {
    visit('/register')->assertSee('Register');
});

test('registration form validation', function () {
    visit('/register')
        ->type('name', 'a')
        ->click('@register')
        ->assertSee('The name field must be at least 3 characters.')
        ->assertSee('The email field is required.')
        ->assertSee('The password field is required.');
});
