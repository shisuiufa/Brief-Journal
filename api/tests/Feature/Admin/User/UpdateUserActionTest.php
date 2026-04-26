<?php

use App\Contracts\Admin\User\UpdateUserActionInterface;
use App\Data\Admin\User\UpdateUserData;
use App\Enums\Access\RoleEnum;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    Role::findOrCreate(RoleEnum::Admin->value);
    Role::findOrCreate(RoleEnum::Editor->value);
});

$updateUser = function (User $user, UpdateUserData $updateUserData): User {
    return app(UpdateUserActionInterface::class)->execute($user, $updateUserData);
};

$createUpdateUserData = function (?RoleEnum $role = null): UpdateUserData {
    return new UpdateUserData(
        name: 'New Name',
        email: 'new@example.com',
        role: $role,
    );
};

it('updates user name and email', function () use ($createUpdateUserData, $updateUser) {
    $user = User::factory()->create([
        'name' => 'Old Name',
        'email' => 'old@example.com',
    ]);

    $updatedUser = $updateUser(
        $user,
        $createUpdateUserData(),
    );

    expect($updatedUser->name)->toBe('New Name')
        ->and($updatedUser->email)->toBe('new@example.com');

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'name' => 'New Name',
        'email' => 'new@example.com',
    ]);
});

it('replaces user role when role is provided', function () use ($createUpdateUserData, $updateUser) {
    $user = User::factory()->create();
    $user->assignRole(RoleEnum::Editor->value);

    $updatedUser = $updateUser(
        $user,
        $createUpdateUserData(RoleEnum::Admin),
    );

    expect($updatedUser->hasRole(RoleEnum::Admin->value))->toBeTrue()
        ->and($updatedUser->hasRole(RoleEnum::Editor->value))->toBeFalse();
});

it('keeps current user role when role is not provided', function () use ($createUpdateUserData, $updateUser) {
    $user = User::factory()->create();
    $user->assignRole(RoleEnum::Editor->value);

    $updatedUser = $updateUser(
        $user,
        $createUpdateUserData(),
    );

    expect($updatedUser->hasRole(RoleEnum::Editor->value))->toBeTrue()
        ->and($updatedUser->hasRole(RoleEnum::Admin->value))->toBeFalse();
});
