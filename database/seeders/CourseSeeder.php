<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Level;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $beginner     = Level::where('slug', 'beginner')->first();
        $intermediate = Level::where('slug', 'intermediate')->first();
        $advanced     = Level::where('slug', 'advanced')->first();

        $courses = [

            // ── Course 1: Free beginner course ───────────────────────────────
            [
                'course' => [
                    'level_id'       => $beginner->id,
                    'title'          => 'Web Development Fundamentals',
                    'slug'           => 'web-development-fundamentals',
                    'price'          => 0.00,
                    'description'    => 'Learn the building blocks of the web: HTML, CSS, and basic JavaScript. Perfect for absolute beginners with no prior experience.',
                    'image'          => null,
                    'status'         => 'published',
                    'is_free_preview'=> true,
                ],
                'lessons' => [
                    [
                        'title'           => 'Introduction to HTML',
                        'order'           => 1,
                        'video_url'       => 'https://www.youtube.com/watch?v=UB1O30fR-EE',
                        'duration_seconds'=> 3600,
                        'is_free_preview' => true,  // free — guests can watch
                    ],
                    [
                        'title'           => 'HTML Forms and Tables',
                        'order'           => 2,
                        'video_url'       => 'https://www.youtube.com/watch?v=HcOc7197SLw',
                        'duration_seconds'=> 2700,
                        'is_free_preview' => true,
                    ],
                    [
                        'title'           => 'CSS Basics and Selectors',
                        'order'           => 3,
                        'video_url'       => 'https://www.youtube.com/watch?v=yfoY53QXEnI',
                        'duration_seconds'=> 3200,
                        'is_free_preview' => false, // requires enrollment
                    ],
                    [
                        'title'           => 'CSS Flexbox and Grid',
                        'order'           => 4,
                        'video_url'       => 'https://www.youtube.com/watch?v=JJSoEo8JSnc',
                        'duration_seconds'=> 4100,
                        'is_free_preview' => false,
                    ],
                    [
                        'title'           => 'Introduction to JavaScript',
                        'order'           => 5,
                        'video_url'       => 'https://www.youtube.com/watch?v=W6NZfCO5SIk',
                        'duration_seconds'=> 4800,
                        'is_free_preview' => false,
                    ],
                ],
            ],

            // ── Course 2: Paid intermediate course ───────────────────────────
            [
                'course' => [
                    'level_id'       => $intermediate->id,
                    'title'          => 'Laravel From Scratch',
                    'slug'           => 'laravel-from-scratch',
                    'price'          => 49.99,
                    'description'    => 'Master Laravel 11: routing, Eloquent ORM, Blade, queues, and building a real-world REST API. Hands-on from day one.',
                    'image'          => null,
                    'status'         => 'published',
                    'is_free_preview'=> false,
                ],
                'lessons' => [
                    [
                        'title'           => 'Laravel Installation & Project Setup',
                        'order'           => 1,
                        'video_url'       => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds'=> 1800,
                        'is_free_preview' => true,  // teaser — free
                    ],
                    [
                        'title'           => 'Routing & Controllers',
                        'order'           => 2,
                        'video_url'       => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds'=> 2400,
                        'is_free_preview' => false,
                    ],
                    [
                        'title'           => 'Eloquent ORM & Relationships',
                        'order'           => 3,
                        'video_url'       => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds'=> 3600,
                        'is_free_preview' => false,
                    ],
                    [
                        'title'           => 'Blade Templates & Components',
                        'order'           => 4,
                        'video_url'       => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds'=> 2800,
                        'is_free_preview' => false,
                    ],
                    [
                        'title'           => 'Queues & Jobs',
                        'order'           => 5,
                        'video_url'       => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds'=> 3100,
                        'is_free_preview' => false,
                    ],
                    [
                        'title'           => 'Building a REST API',
                        'order'           => 6,
                        'video_url'       => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds'=> 4200,
                        'is_free_preview' => false,
                    ],
                ],
            ],

            // ── Course 3: Paid advanced course ───────────────────────────────
            [
                'course' => [
                    'level_id'       => $advanced->id,
                    'title'          => 'Advanced Laravel: Architecture & Patterns',
                    'slug'           => 'advanced-laravel-architecture-patterns',
                    'price'          => 79.99,
                    'description'    => 'Go beyond the basics. Learn DDD, CQRS, repository pattern, action classes, event sourcing, and testing strategies for large-scale Laravel apps.',
                    'image'          => null,
                    'status'         => 'published',
                    'is_free_preview'=> false,
                ],
                'lessons' => [
                    [
                        'title'           => 'Repository Pattern Deep Dive',
                        'order'           => 1,
                        'video_url'       => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds'=> 3600,
                        'is_free_preview' => true,
                    ],
                    [
                        'title'           => 'Action Classes & Single Responsibility',
                        'order'           => 2,
                        'video_url'       => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds'=> 3200,
                        'is_free_preview' => false,
                    ],
                    [
                        'title'           => 'Service Layer Architecture',
                        'order'           => 3,
                        'video_url'       => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds'=> 4000,
                        'is_free_preview' => false,
                    ],
                    [
                        'title'           => 'Event Sourcing with Laravel',
                        'order'           => 4,
                        'video_url'       => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds'=> 5400,
                        'is_free_preview' => false,
                    ],
                    [
                        'title'           => 'Advanced Pest Testing Strategies',
                        'order'           => 5,
                        'video_url'       => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds'=> 4800,
                        'is_free_preview' => false,
                    ],
                ],
            ],

            // ── Course 4: Draft — should NOT appear on public listing ─────────
            [
                'course' => [
                    'level_id'       => $beginner->id,
                    'title'          => 'Python for Beginners (Coming Soon)',
                    'slug'           => 'python-for-beginners',
                    'price'          => 29.99,
                    'description'    => 'A draft course — not visible to the public.',
                    'image'          => null,
                    'status'         => 'draft',  // intentionally draft
                    'is_free_preview'=> false,
                ],
                'lessons' => [
                    [
                        'title'           => 'Python Installation',
                        'order'           => 1,
                        'video_url'       => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds'=> 900,
                        'is_free_preview' => false,
                    ],
                ],
            ],
        ];

        foreach ($courses as $data) {
            $course = Course::firstOrCreate(
                ['slug' => $data['course']['slug']],
                $data['course']
            );

            foreach ($data['lessons'] as $lessonData) {
                $course->lessons()->firstOrCreate(
                    [
                        'title' => $lessonData['title'],
                        'order' => $lessonData['order'],
                    ],
                    $lessonData
                );
            }
        }

        $this->command->info('✅ Courses and lessons seeded (3 published, 1 draft).');
    }
}