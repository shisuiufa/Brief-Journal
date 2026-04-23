<?php

use App\Actions\Admin\User\CreateUserAction;
use App\Data\Admin\CreateUserData;
use App\Enums\Access\RoleEnum;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

function createRole(): void
{
    Role::findOrCreate(RoleEnum::Admin->value);
}

function createUser(CreateUserData $createUserData): User
{
    $action = app(CreateUserAction::class);

    return $action($createUserData);
}

function createData(): CreateUserData
{
    return new CreateUserData(
        name: 'John Doe',
        email: 'test@example.com',
        password: 'password',
        role: RoleEnum::Admin,
    );
}

test('it creates a user', function () {
    createRole();

    $data = createData();

    $user = createUser($data);

    expect($user->email)->toBe('test@example.com');

    $this->assertDatabaseHas('users', [
        'email' => 'test@example.com',
    ]);
});

test('it assigns the admin role', function () {
    createRole();

    $data = createData();

    $user = createUser($data);

    expect($user->hasRole(RoleEnum::Admin->value))->toBeTrue();
});

test('it hashes the user password', function () {
    createRole();

    $data = createData();

    $user = createUser($data);

    expect(Hash::check('password', $user->password))->toBeTrue();
});
