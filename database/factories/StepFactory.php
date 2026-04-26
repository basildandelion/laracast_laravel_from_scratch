<?php

namespace Database\Factories;

use App\Models\Idea;
use App\Models\Step;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class StepFactory extends Factory
{
    protected $model = Step::class;

    public function definition(): array
    {
        return [
            'idea_id' => Idea::factory(),
            'description' => $this->faker->text(),
            'completed' => false,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
