<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use App\Models\Category;
use App\Models\Poster;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // 👤 1. Create 3 users
        $users = User::factory(3)->create();

        // 🏷 2. Create 5 main categories
        $categories = Category::factory(5)->create();

        // 🌿 3. Add nesting
        $categories[0]->children()->createMany([
            [
                'name' => 'Sub ' . fake()->word(),
                'slug' => fake()->unique()->slug(),
            ],
            [
                'name' => 'Sub ' . fake()->word(),
                'slug' => fake()->unique()->slug(),
            ],
        ]);

        $categories[1]->children()->create([
            'name' => 'Nested ' . fake()->word(),
            'slug' => fake()->unique()->slug(),
        ]);

        // 🖼 4. Create 20 posters
        $categories = Category::all();

        Poster::factory(20)->create()->each(function ($poster) use ($users, $categories) {

            $poster->user_id = $users->random()->id;
            $poster->save();

            $poster->categories()->attach(
                $categories->random(rand(1, 3))->pluck('id')->toArray()
            );
        });
    }
}