<?php

namespace App\Actions\Admin\User;

use App\Contracts\Admin\User\UpdateUserActionInterface;
use App\Contracts\Realtime\RealtimePublisherInterface;
use App\Data\Admin\User\UpdateUserData;
use App\Enums\Realtime\RealtimeEventEnum;
use App\Models\User;
use Illuminate\Support\Facades\DB;

final readonly class UpdateUserAction implements UpdateUserActionInterface
{
    public function __construct(
        private RealtimePublisherInterface $realtimePublisher,
    ) {}

    public function execute(User $user, UpdateUserData $data): User
    {
        $user = DB::transaction(function () use ($user, $data): User {
            $attributes = [
                'name' => $data->name,
                'email' => $data->email,
            ];

            if (! blank($data->password)) {
                $attributes['password'] = $data->password;
            }

            $user->update($attributes);

            if ($data->role) {
                $user->syncRoles([$data->role->value]);
            }

            return $user->refresh();
        });

        $this->realtimePublisher->publish(RealtimeEventEnum::UserUpdated, [
            'id' => $user->id,
        ]);

        return $user;
    }
}
