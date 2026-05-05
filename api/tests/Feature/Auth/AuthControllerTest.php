<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\ClientRepository;

uses(RefreshDatabase::class);

beforeEach(function () {
    $passwordClient = app(ClientRepository::class)->createPasswordGrantClient(
        name: 'Test Password Client',
        provider: 'users',
    );

    app(ClientRepository::class)->createPersonalAccessGrantClient(
        name: 'Test Personal Access Client',
        provider: 'users',
    );

    config([
        'passport.password_id' => (string) $passwordClient->getKey(),
        'passport.password_secret' => null,
    ]);
});

it('logs in a user', function () {
    $user = User::factory()->create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => Hash::make('password'),
    ]);

    $response = $this->postJson('/api/auth/login', [
        'email' => 'test@example.com',
        'password' => 'password',
    ])
        ->assertOk()
        ->assertJsonPath('data.user.name', 'Test User')
        ->assertJsonPath('data.user.email', 'test@example.com')
        ->assertJsonPath('data.token.token_type', 'Bearer')
        ->assertJsonPath('data.token.refresh_token', null)
        ->assertJsonStructure([
            'data' => [
                'user' => ['id', 'name', 'email', 'roles'],
                'token' => ['access_token', 'token_type', 'expires_in'],
            ],
        ]);

    $this->withToken($response->json('data.token.access_token'))
        ->getJson('/api/user')
        ->assertOk()
        ->assertJsonPath('data.id', $user->id);
});

it('does not log in with invalid credentials', function () {
    User::factory()->create([
        'email' => 'test@example.com',
        'password' => Hash::make('password'),
    ]);

    $this->postJson('/api/auth/login', [
        'email' => 'test@example.com',
        'password' => 'wrong-password',
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['email']);

    $this->assertGuest();
});

it('requires authentication to log out', function () {
    $this->postJson('/api/auth/logout')
        ->assertUnauthorized();
});

it('logs out an authenticated user', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test-login');

    $this->withToken($token->accessToken)
        ->postJson('/api/auth/logout')
        ->assertOk()
        ->assertJsonPath('message', 'Logged out successfully.');

    $this->assertDatabaseHas('oauth_access_tokens', [
        'id' => $token->accessTokenId,
        'revoked' => true,
    ]);

    $this->withToken($token->accessToken)
        ->getJson('/api/user')
        ->assertUnauthorized();
});
