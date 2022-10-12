<?php

namespace Database\Factories;

use App\Models\User;
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
    public function definition()
    {
        return [
            'title' => fake()->paragraph(1),
            'body' => fake()->text(),
            'slug' => fake()->url(),
            'user_id' => User::all()->random()->id,

            // 'user_id' => auth()->user()->id      for runtime
        ];
    }
}
