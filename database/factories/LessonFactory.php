<?php

namespace Database\Factories;

use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

class LessonFactory extends Factory
{
    public function definition(): array
    {
        static $order = 1;

        return [
            'course_id'        => Course::factory(),
            'title'            => $this->faker->sentence(5),
            'video_url'        => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'order'            => $order++,
            'duration_seconds' => $this->faker->numberBetween(120, 3600),
            'is_free_preview'  => false,
        ];
    }

    public function freePreview(): static
    {
        return $this->state(['is_free_preview' => true]);
    }
}