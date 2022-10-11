<?php

namespace Database\Seeders;

use App\Models\{Post, User};
use Faker\Factory as Faker;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        for ($i = 0; $i < 20; $i++) {
            $post = new Post();
            $post->title = $faker->paragraph(1);
            $post->body = $faker->text();
            $post->user_id = User::first()->id;
            $post->save();
        }
    }
}
