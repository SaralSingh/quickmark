<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can register a new user', function () {
    $response = $this->postJson('/api/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
    ]);

    $response->assertStatus(201)
        ->assertJson([
            'status' => true,
            'message' => 'Registration successful. Please login.',
        ]);

    $this->assertDatabaseHas('users', [
        'email' => 'test@example.com',
        'name' => 'Test User',
    ]);
});

it('cannot register with invalid or duplicate email', function () {
    User::factory()->create([
        'email' => 'test@example.com',
    ]);

    $response = $this->postJson('/api/register', [
        'name' => 'Another User',
        'email' => 'test@example.com',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['email']);
});

it('cannot register with a weak password', function () {
    $response = $this->postJson('/api/register', [
        'name' => 'Test User',
        'email' => 'testuser@example.com',
        'password' => 'weak',
        'password_confirmation' => 'weak',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['password']);
});

it('can login with valid credentials', function () {
    $user = User::factory()->create([
        'password' => bcrypt('Password123!'),
    ]);

    $response = $this->withSession([])->postJson('/api/login', [
        'email' => $user->email,
        'password' => 'Password123!',
    ]);

    $response->assertStatus(200)
        ->assertJson([
            'status' => true,
        ]);

    $this->assertAuthenticatedAs($user);
});

it('cannot login with invalid credentials', function () {
    $user = User::factory()->create([
        'password' => bcrypt('Password123!'),
    ]);

    $response = $this->withSession([])->postJson('/api/login', [
        'email' => $user->email,
        'password' => 'WrongPassword',
    ]);

    $response->assertStatus(401)
        ->assertJson([
            'status' => false,
            'message' => 'Invalid credentials',
        ]);

    $this->assertGuest();
});
