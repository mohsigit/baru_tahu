<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Inventory; // Make sure to import your Inventory model
use Faker\Factory as Faker;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID'); // Create Faker instance with Indonesian locale

        // Generate 50 inventory records
        foreach (range(1, 50) as $index) {
            Inventory::create([
                'name' => $faker->word,
                'qty' => $faker->numberBetween(1, 100),
                'balance' => $faker->numberBetween(0, 100),
                'remarks' => $faker->sentence,
                'color' => $faker->colorName,
                'size' => $faker->word,
                'description' => $faker->paragraph,
            ]);
        }
    }
}
