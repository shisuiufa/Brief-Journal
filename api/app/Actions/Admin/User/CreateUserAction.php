<?php

namespace App\Actions\Admin\User;

use App\Contracts\Admin\User\CreateUserActionInterface;
use App\Contracts\Realtime\RealtimePublisherInterface;
use App\Data\Admin\User\CreateUserData;
use App\Enums\Realtime\RealtimeEventEnum;
use App\Models\User;
use Illuminate\Support\Facades\DB;

final readonly class CreateUserAction implements CreateUserActionInterface
{
    public function __construct(
        private RealtimePublisherInterface $realtimePublisher,
    ) {}

    public function execute(CreateUserData $data): User
    {
        $user = DB::transaction(function () use ($data): User {
            $user = User::create([
                'name' => $data->name,
                'email' => $data->email,
                'password' => $data->password,
            ]);

            $user->assignRole($data->role->value);

            return $user;
        });

        $this->realtimePublisher->publish(RealtimeEventEnum::UserCreated);

        return $user;
    }
}
