<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
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
        $title = $title = fake()->sentence();
        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'user_id' => User::factory(),
            'category_id' => Category::factory(),
            'body' => fake()->text()
        ];
    }
}
