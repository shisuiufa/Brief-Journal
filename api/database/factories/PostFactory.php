<?php

namespace Database\Factories;

use App\Enums\Post\PostStatusEnum;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(4);

        return [
            'user_id' => User::factory(),
            'title' => $title,
            'slug' => Str::slug($title) . '-' . fake()->unique()->numberBetween(1000, 9999),
            'image' => 'posts/example.jpg',
            'excerpt' => fake()->paragraph(),
            'content' => fake()->paragraphs(5, true),
            'status' => PostStatusEnum::Draft,
            'published_at' => null,
        ];
    }

    public function published(): static
    {
        return $this->state([
            'status' => PostStatusEnum::Published,
            'published_at' => now(),
        ]);
    }
}
