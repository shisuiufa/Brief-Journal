<?php

use App\Enums\Access\RoleEnum;
use App\Models\User;
use Database\Seeders\PermissionsSeeder;
use Database\Seeders\RolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(PermissionsSeeder::class);
    $this->seed(RolesSeeder::class);
});

function createUserWithRole(RoleEnum $role = RoleEnum::SuperAdmin): User
{
    $user = User::factory()->create();

    $user->assignRole($role->value);

    return $user;
}

function userPayload(array $overrides = []): array
{
    return array_merge([
        'name' => 'Test User',
        'email' => fake()->unique()->safeEmail(),
        'password' => 'password',
        'role' => RoleEnum::Editor->value,
    ], $overrides);
}

function updateUserPayload(array $overrides = []): array
{
    return array_merge([
        'name' => 'Updated User',
        'email' => fake()->unique()->safeEmail(),
        'role' => RoleEnum::Editor->value,
    ], $overrides);
}

describe('UserController', function () {
    it('gets users list', function () {
        $superAdmin = createUserWithRole();
        User::factory()->count(3)->create();

        $this->actingAs($superAdmin)
            ->getJson('/api/admin/users')
            ->assertOk()
            ->assertJsonStructure(['data']);
    });

    it('shows user', function () {
        $superAdmin = createUserWithRole();
        $user = createUserWithRole(RoleEnum::Editor);

        $this->actingAs($superAdmin)
            ->getJson("/api/admin/users/{$user->id}")
            ->assertOk()
            ->assertJsonPath('data.id', $user->id);
    });

    it('creates user', function () {
        $superAdmin = createUserWithRole();

        $this->actingAs($superAdmin)
            ->postJson('/api/admin/users', userPayload([
                'email' => 'editor@example.com',
                'role' => RoleEnum::Editor->value,
            ]))
            ->assertCreated();

        $createdUser = User::where('email', 'editor@example.com')->first();

        expect($createdUser)->not->toBeNull()
            ->and($createdUser->hasRole(RoleEnum::Editor->value))->toBeTrue();
    });

    it('updates user', function () {
        $superAdmin = createUserWithRole();
        $editor = createUserWithRole(RoleEnum::Editor);

        $this->actingAs($superAdmin)
            ->putJson("/api/admin/users/{$editor->id}", updateUserPayload([
                'name' => 'Updated Editor',
                'email' => 'updated-editor@example.com',
                'role' => RoleEnum::Editor->value,
            ]))
            ->assertOk();

        $editor->refresh();

        expect($editor->name)->toBe('Updated Editor')
            ->and($editor->email)->toBe('updated-editor@example.com')
            ->and($editor->hasRole(RoleEnum::Editor->value))->toBeTrue();
    });

    it('deletes user', function () {
        $superAdmin = createUserWithRole();
        $editor = createUserWithRole(RoleEnum::Editor);

        $this->actingAs($superAdmin)
            ->deleteJson("/api/admin/users/{$editor->id}")
            ->assertOk();

        expect(User::whereKey($editor->id)->exists())->toBeFalse();
    });
});
