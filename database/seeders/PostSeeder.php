<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post; // Ensure this is the correct model for your posts
use Faker\Factory as Faker;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID'); // Create a Faker instance with Indonesian locale

        // Generate 50 item records
        foreach (range(1, 50) as $index) {
            Post::create([ // Use Post instead of post
                'title' => $faker->sentence, // Changed from word to sentence for better titles
                'description' => $faker->paragraph,
            ]);
        }
    }
}

