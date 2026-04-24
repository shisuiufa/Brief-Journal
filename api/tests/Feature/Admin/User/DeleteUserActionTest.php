<?php

use App\Actions\Admin\User\DeleteUserAction;
use App\Contracts\Admin\User\DeleteUserActionInterface;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;

uses(RefreshDatabase::class);

it('deletes another user', function () {
    $authUser = User::factory()->create();
    $user = User::factory()->create();

    $this->actingAs($authUser);

    app(DeleteUserActionInterface::class)->execute($user);

    $this->assertSoftDeleted('users', [
        'id' => $user->id,
    ]);
});

it('cannot delete itself', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    app(DeleteUserAction::class)->execute($user);
})->throws(ValidationException::class);
