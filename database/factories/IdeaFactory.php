<?php

namespace Database\Factories;

use App\Models\Idea;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class IdeaFactory extends Factory
{
    protected $model = Idea::class;

    public function definition(): array
    {
        $links = [];
        for ($i = 0; $i < 5; $i++) {
            $links[] = fake()->url();
        }

        return [
            'title' => fake()->word(),
            'description' => fake()->text(),
            'links' => $links,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'image_path' => 'https://placehold.co/600x400',
            'user_id' => User::factory(),
        ];
    }
}
