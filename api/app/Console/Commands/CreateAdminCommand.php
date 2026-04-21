<?php

namespace App\Console\Commands;

use App\Actions\CreateUserAction;
use App\Data\CreateUserData;
use App\Enums\RoleEnum;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Throwable;

#[Signature('app:create-admin')]
#[Description('Create admin user')]
class CreateAdminCommand extends Command
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

    /**
     * Execute the console command.
     *
     * @throws Throwable
     */
    public function handle(CreateUserAction $createUser): int
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

        $admin = $createUser(new CreateUserData(
            name: $payload['name'],
            email: $payload['email'],
            password: $payload['password'],
            role: RoleEnum::Admin
        ));

        $this->info("Admin {$admin->email} created successfully.");

        return self::SUCCESS;
    }
}
