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

$userPayload = function (array $overrides = []): array {
    return array_merge([
        'name' => 'Test User',
        'email' => fake()->unique()->safeEmail(),
        'password' => 'password',
        'role' => RoleEnum::Editor->value,
    ], $overrides);
};

$updateUserPayload = function (array $overrides = []): array {
    return array_merge([
        'name' => 'Updated User',
        'email' => fake()->unique()->safeEmail(),
        'role' => RoleEnum::Editor->value,
    ], $overrides);
};

it('gets users list', function () {
    $superAdmin = createUserWithRole(RoleEnum::SuperAdmin);
    User::factory()->count(3)->create();

    $this->actingAs($superAdmin)
        ->getJson('/api/admin/users')
        ->assertOk()
        ->assertJsonStructure(['data']);
});

it('shows user', function () {
    $superAdmin = createUserWithRole(RoleEnum::SuperAdmin);
    $user = createUserWithRole(RoleEnum::Editor);

    $this->actingAs($superAdmin)
        ->getJson("/api/admin/users/{$user->id}")
        ->assertOk()
        ->assertJsonPath('data.id', $user->id);
});

it('creates user', function () use ($userPayload) {
    $superAdmin = createUserWithRole(RoleEnum::SuperAdmin);

    $this->actingAs($superAdmin)
        ->postJson('/api/admin/users', $userPayload([
            'email' => 'editor@example.com',
            'role' => RoleEnum::Editor->value,
        ]))
        ->assertCreated();

    $createdUser = User::where('email', 'editor@example.com')->first();

    expect($createdUser)->not->toBeNull()
        ->and($createdUser->hasRole(RoleEnum::Editor->value))->toBeTrue();
});

it('updates user', function () use ($updateUserPayload) {
    $superAdmin = createUserWithRole(RoleEnum::SuperAdmin);
    $editor = createUserWithRole(RoleEnum::Editor);

    $this->actingAs($superAdmin)
        ->putJson("/api/admin/users/{$editor->id}", $updateUserPayload([
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
    $superAdmin = createUserWithRole(RoleEnum::SuperAdmin);
    $editor = createUserWithRole(RoleEnum::Editor);

    $this->actingAs($superAdmin)
        ->deleteJson("/api/admin/users/{$editor->id}")
        ->assertOk();

    $this->assertSoftDeleted('users', [
        'id' => $editor->id,
    ]);
});

it('forbids changing another user role when not allowed', function (RoleEnum $actorRole, RoleEnum $targetRole, RoleEnum $newRole) use ($updateUserPayload) {
    $actor = createUserWithRole($actorRole);
    $target = createUserWithRole($targetRole);

    $this->actingAs($actor)
        ->putJson("/api/admin/users/{$target->id}", $updateUserPayload([
            'name' => 'Updated User',
            'email' => 'updated-user@example.com',
            'role' => $newRole->value,
        ]))
        ->assertForbidden();

    $target->refresh();

    expect($target->hasRole($targetRole->value))->toBeTrue()
        ->and($target->hasRole($newRole->value))->toBeFalse();
})->with([
    'admin promotes editor to admin' => [RoleEnum::Admin, RoleEnum::Editor, RoleEnum::Admin],
    'admin promotes editor to super admin' => [RoleEnum::Admin, RoleEnum::Editor, RoleEnum::SuperAdmin],
    'editor changes editor to admin' => [RoleEnum::Editor, RoleEnum::Editor, RoleEnum::Admin],
]);

it('forbids creating forbidden roles', function (RoleEnum $actorRole, RoleEnum $targetRole) use ($userPayload) {
    $actor = createUserWithRole($actorRole);

    $email = fake()->unique()->safeEmail();

    $this->actingAs($actor)
        ->postJson('/api/admin/users', $userPayload([
            'email' => $email,
            'role' => $targetRole->value,
        ]))
        ->assertForbidden();

    $this->assertDatabaseMissing('users', [
        'email' => $email,
    ]);
})->with([
    'admin creates admin' => [RoleEnum::Admin, RoleEnum::Admin],
    'admin creates user' => [RoleEnum::Admin, RoleEnum::User],
    'admin creates super admin' => [RoleEnum::Admin, RoleEnum::SuperAdmin],
    'super admin creates user' => [RoleEnum::SuperAdmin, RoleEnum::User],
    'super admin creates super admin' => [RoleEnum::SuperAdmin, RoleEnum::SuperAdmin],
    'editor creates editor' => [RoleEnum::Editor, RoleEnum::Editor],
]);

it('forbids changing own role', function (RoleEnum $currentRole, RoleEnum $newRole) use ($updateUserPayload) {
    $user = createUserWithRole($currentRole);

    $this->actingAs($user)
        ->putJson("/api/admin/users/{$user->id}", $updateUserPayload([
            'name' => 'Updated Self',
            'email' => 'updated-self@example.com',
            'role' => $newRole->value,
        ]))
        ->assertForbidden();

    $user->refresh();

    expect($user->hasRole($currentRole->value))->toBeTrue()
        ->and($user->hasRole($newRole->value))->toBeFalse();
})->with([
    'editor to admin' => [RoleEnum::Editor, RoleEnum::Admin],
    'editor to super admin' => [RoleEnum::Editor, RoleEnum::SuperAdmin],
    'admin to editor' => [RoleEnum::Admin, RoleEnum::Editor],
    'admin to super admin' => [RoleEnum::Admin, RoleEnum::SuperAdmin],
]);

it('updates user without changing role', function () {
    $superAdmin = createUserWithRole(RoleEnum::SuperAdmin);
    $editor = createUserWithRole(RoleEnum::Editor);

    $this->actingAs($superAdmin)
        ->putJson("/api/admin/users/{$editor->id}", [
            'name' => 'Updated Without Role',
            'email' => 'updated-without-role@example.com',
        ])
        ->assertOk();

    $editor->refresh();

    expect($editor->name)->toBe('Updated Without Role')
        ->and($editor->email)->toBe('updated-without-role@example.com')
        ->and($editor->hasRole(RoleEnum::Editor->value))->toBeTrue();
});

