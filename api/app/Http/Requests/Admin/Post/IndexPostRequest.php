<?php

namespace App\Http\Requests\Admin\Post;

use App\Enums\Post\PostStatusEnum;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class IndexPostRequest extends FormRequest
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
            'status' => ['nullable', new Enum(PostStatusEnum::class)],
        ];
    }

    public function search(): string
    {
        return $this->string('search')->toString();
    }

    public function status(): ?PostStatusEnum
    {
        return $this->enum('status', PostStatusEnum::class);
    }
}
