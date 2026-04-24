<?php

namespace App\Console\Commands;

use App\Contracts\Admin\User\CreateUserActionInterface;
use App\Data\Admin\CreateUserData;
use App\Enums\Access\RoleEnum;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

#[Signature('app:create-s-admin')]
#[Description('Create super admin user')]
class CreateSuperAdminCommand extends Command
{
    /**
     * @return array{name: string, email: string, password: string}
     */
    private function validatedInput(): array
    {
        $payload = [
            'name' => trim((string) $this->ask('What is your name?')),
            'email' => trim((string) $this->ask('What is your email?')),
            'password' => (string) $this->secret('What is your password?'),
        ];

        return Validator::make($payload, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ])->validate();
    }

    public function handle(CreateUserActionInterface $createUser): int
    {
        try {
            $payload = $this->validatedInput();
        } catch (ValidationException $exception) {
            foreach ($exception->errors() as $messages) {
                foreach ($messages as $message) {
                    $this->error($message);
                }
            }

            return self::FAILURE;
        }

        $sAdmin = $createUser->execute(new CreateUserData(
            name: $payload['name'],
            email: $payload['email'],
            password: $payload['password'],
            role: RoleEnum::SuperAdmin
        ));

        $this->info("Admin {$sAdmin->email} created successfully.");

        return self::SUCCESS;
    }
}
