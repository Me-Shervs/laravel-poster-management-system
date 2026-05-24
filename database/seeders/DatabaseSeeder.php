<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use App\Models\Category;
use App\Models\Poster;
use App\Models\Schedule;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $users = User::factory(3)->create();

        $categories = Category::factory(5)->create();

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

        $categories = Category::all();

        $posters = Poster::factory(20)
            ->create([
                'user_id' => fn () => $users->random()->id,
            ])
            ->each(function ($poster) use ($categories) {

                $poster->categories()->attach(
                    $categories->random(rand(1, 3))->pluck('id')
                );
            });

        foreach ($posters as $poster) {
            Schedule::factory()->create([
                'poster_id' => $poster->id,
            ]);
        }
    }
}