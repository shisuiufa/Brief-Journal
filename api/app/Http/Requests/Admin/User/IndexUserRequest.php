<?php

namespace App\Http\Requests\Admin\User;

use App\Enums\Access\RoleEnum;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class IndexUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:255'],
            'role' => ['nullable', new Enum(RoleEnum::class)],
        ];
    }

    public function search(): string
    {
        return $this->string('search')->toString();
    }

    public function role(): ?RoleEnum
    {
        return $this->enum('role', RoleEnum::class);
    }
}
