<?php

namespace Database\Factories;

use App\Models\Level;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CourseFactory extends Factory
{
    public function definition(): array
    {
        $title = $this->faker->unique()->words(4, true);

        return [
            'title'       => ucfirst($title),
            'slug'        => Str::slug($title) . '-' . $this->faker->unique()->randomNumber(5),
            'description' => $this->faker->paragraph(),
            'level_id'    => Level::factory(),
            'price'       => $this->faker->randomElement([0, 9.99, 19.99, 49.99]),
            'status'      => 'published',
            'image'       => null,
        ];
    }

    public function unpublished(): static
    {
        return $this->state(['status' => 'draft']);
    }

    public function free(): static
    {
        return $this->state(['price' => 0]);
    }
}