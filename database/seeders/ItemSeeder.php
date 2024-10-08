<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item; // Make sure to import your Item model
use Faker\Factory as Faker;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID'); // Create a Faker instance with Indonesian locale

        // Generate 50 item records
        foreach (range(1, 50) as $index) {
            Item::create([
                'title' => $faker->word,
                'description' => $faker->paragraph,
            ]);
        }
    }
}

