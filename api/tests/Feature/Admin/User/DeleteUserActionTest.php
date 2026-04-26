<?php

use App\Contracts\Admin\User\DeleteUserActionInterface;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;

uses(RefreshDatabase::class);

$deleteUser = function (User $user): void
{
    app(DeleteUserActionInterface::class)->execute($user);
};

it('deletes another user', function () use ($deleteUser) {
    $authUser = User::factory()->create();
    $user = User::factory()->create();

    $this->actingAs($authUser);

    $deleteUser($user);

    $this->assertSoftDeleted('users', [
        'id' => $user->id,
    ]);
});

it('cannot delete itself', function () use ($deleteUser) {
    $user = User::factory()->create();

    $this->actingAs($user);

    try {
        $deleteUser($user);

        $this->fail('ValidationException was not thrown.');
    } catch (ValidationException $exception) {
        expect($exception->errors())->toBe([
            'user' => ['You cannot delete yourself.'],
        ]);
    }

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'deleted_at' => null,
    ]);
});
