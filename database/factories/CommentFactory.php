<?php

namespace Database\Factories;

use App\Models\{Post, User};
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'body' => fake()->text(),
            'post_id' => Post::all()->random()->id,
            'user_id' => User::all()->random()->id,
        ];
    }
}
