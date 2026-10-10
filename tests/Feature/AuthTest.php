<?php

use App\Models\User;

it('registers a new user and returns a token', function () {
    $response = $this->postJson('/api/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertCreated()->assertJsonStructure(['user', 'token']);
    $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
});

it('logs in with valid credentials', function () {
    $user = User::factory()->create(['password' => 'password123']);

    $this->postJson('/api/login', [
        'email' => $user->email,
        'password' => 'password123',
    ])->assertOk()->assertJsonStructure(['token']);
});

it('rejects invalid credentials', function () {
    $user = User::factory()->create();

    $this->postJson('/api/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ])->assertUnauthorized();
});