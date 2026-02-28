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
                    'level_id'        => $beginner->id,
                    'title'           => 'Web Development Fundamentals',
                    'slug'            => 'web-development-fundamentals',
                    'price'           => 0.00,
                    'description'     => 'Learn the building blocks of the web: HTML, CSS, and basic JavaScript. Perfect for absolute beginners with no prior experience.',
                    'image'           => null,
                    'status'          => 'published',
                    'is_free_preview' => true,
                ],
                'lessons' => [
                    [
                        'title'            => 'Introduction to HTML',
                        'order'            => 1,
                        'video_url'        => 'https://www.youtube.com/watch?v=UB1O30fR-EE',
                        'duration_seconds' => 3600,
                        'is_free_preview'  => true,
                    ],
                    [
                        'title'            => 'HTML Forms and Tables',
                        'order'            => 2,
                        'video_url'        => 'https://www.youtube.com/watch?v=HcOc7197SLw',
                        'duration_seconds' => 2700,
                        'is_free_preview'  => true,
                    ],
                    [
                        'title'            => 'CSS Basics and Selectors',
                        'order'            => 3,
                        'video_url'        => 'https://www.youtube.com/watch?v=yfoY53QXEnI',
                        'duration_seconds' => 3200,
                        'is_free_preview'  => false,
                    ],
                    [
                        'title'            => 'CSS Flexbox and Grid',
                        'order'            => 4,
                        'video_url'        => 'https://www.youtube.com/watch?v=JJSoEo8JSnc',
                        'duration_seconds' => 4100,
                        'is_free_preview'  => false,
                    ],
                    [
                        'title'            => 'Introduction to JavaScript',
                        'order'            => 5,
                        'video_url'        => 'https://www.youtube.com/watch?v=W6NZfCO5SIk',
                        'duration_seconds' => 4800,
                        'is_free_preview'  => false,
                    ],
                ],
            ],

            // ── Course 2: Paid intermediate course ───────────────────────────
            [
                'course' => [
                    'level_id'        => $intermediate->id,
                    'title'           => 'Laravel From Scratch',
                    'slug'            => 'laravel-from-scratch',
                    'price'           => 49.99,
                    'description'     => 'Master Laravel 11: routing, Eloquent ORM, Blade, queues, and building a real-world REST API. Hands-on from day one.',
                    'image'           => null,
                    'status'          => 'published',
                    'is_free_preview' => false,
                ],
                'lessons' => [
                    [
                        'title'            => 'Laravel Installation & Project Setup',
                        'order'            => 1,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 1800,
                        'is_free_preview'  => true,
                    ],
                    [
                        'title'            => 'Routing & Controllers',
                        'order'            => 2,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 2400,
                        'is_free_preview'  => false,
                    ],
                    [
                        'title'            => 'Eloquent ORM & Relationships',
                        'order'            => 3,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 3600,
                        'is_free_preview'  => false,
                    ],
                    [
                        'title'            => 'Blade Templates & Components',
                        'order'            => 4,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 2800,
                        'is_free_preview'  => false,
                    ],
                    [
                        'title'            => 'Queues & Jobs',
                        'order'            => 5,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 3100,
                        'is_free_preview'  => false,
                    ],
                    [
                        'title'            => 'Building a REST API',
                        'order'            => 6,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 4200,
                        'is_free_preview'  => false,
                    ],
                ],
            ],

            // ── Course 3: Paid advanced course ───────────────────────────────
            [
                'course' => [
                    'level_id'        => $advanced->id,
                    'title'           => 'Advanced Laravel: Architecture & Patterns',
                    'slug'            => 'advanced-laravel-architecture-patterns',
                    'price'           => 79.99,
                    'description'     => 'Go beyond the basics. Learn DDD, CQRS, repository pattern, action classes, event sourcing, and testing strategies for large-scale Laravel apps.',
                    'image'           => null,
                    'status'          => 'published',
                    'is_free_preview' => false,
                ],
                'lessons' => [
                    [
                        'title'            => 'Repository Pattern Deep Dive',
                        'order'            => 1,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 3600,
                        'is_free_preview'  => true,
                    ],
                    [
                        'title'            => 'Action Classes & Single Responsibility',
                        'order'            => 2,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 3200,
                        'is_free_preview'  => false,
                    ],
                    [
                        'title'            => 'Service Layer Architecture',
                        'order'            => 3,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 4000,
                        'is_free_preview'  => false,
                    ],
                    [
                        'title'            => 'Event Sourcing with Laravel',
                        'order'            => 4,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 5400,
                        'is_free_preview'  => false,
                    ],
                    [
                        'title'            => 'Advanced Pest Testing Strategies',
                        'order'            => 5,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 4800,
                        'is_free_preview'  => false,
                    ],
                ],
            ],

            // ── Course 4: Free beginner — Python ─────────────────────────────
            [
                'course' => [
                    'level_id'        => $beginner->id,
                    'title'           => 'Python for Beginners',
                    'slug'            => 'python-for-beginners',
                    'price'           => 0.00,
                    'description'     => 'Start coding with Python from zero. Covers variables, loops, functions, and writing your first scripts — no experience needed.',
                    'image'           => null,
                    'status'          => 'published',
                    'is_free_preview' => true,
                ],
                'lessons' => [
                    [
                        'title'            => 'Python Installation & Setup',
                        'order'            => 1,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 900,
                        'is_free_preview'  => true,
                    ],
                    [
                        'title'            => 'Variables, Data Types & Operators',
                        'order'            => 2,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 2400,
                        'is_free_preview'  => true,
                    ],
                    [
                        'title'            => 'Control Flow: if / elif / else',
                        'order'            => 3,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 2000,
                        'is_free_preview'  => false,
                    ],
                    [
                        'title'            => 'Loops: for & while',
                        'order'            => 4,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 2200,
                        'is_free_preview'  => false,
                    ],
                    [
                        'title'            => 'Functions & Scope',
                        'order'            => 5,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 2800,
                        'is_free_preview'  => false,
                    ],
                    [
                        'title'            => 'Lists, Tuples & Dictionaries',
                        'order'            => 6,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 3000,
                        'is_free_preview'  => false,
                    ],
                ],
            ],

            // ── Course 5: Intermediate — Vue.js ──────────────────────────────
            [
                'course' => [
                    'level_id'        => $intermediate->id,
                    'title'           => 'Vue.js 3 Essentials',
                    'slug'            => 'vuejs-3-essentials',
                    'price'           => 39.99,
                    'description'     => 'Build reactive, component-driven UIs with Vue 3. Covers the Composition API, Pinia state management, Vue Router, and consuming REST APIs.',
                    'image'           => null,
                    'status'          => 'published',
                    'is_free_preview' => false,
                ],
                'lessons' => [
                    [
                        'title'            => 'Vue 3 Setup & Project Structure',
                        'order'            => 1,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 1500,
                        'is_free_preview'  => true,
                    ],
                    [
                        'title'            => 'Template Syntax & Directives',
                        'order'            => 2,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 2600,
                        'is_free_preview'  => false,
                    ],
                    [
                        'title'            => 'Composition API: ref & reactive',
                        'order'            => 3,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 3200,
                        'is_free_preview'  => false,
                    ],
                    [
                        'title'            => 'Components & Props',
                        'order'            => 4,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 2900,
                        'is_free_preview'  => false,
                    ],
                    [
                        'title'            => 'Pinia State Management',
                        'order'            => 5,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 3400,
                        'is_free_preview'  => false,
                    ],
                    [
                        'title'            => 'Vue Router & Navigation Guards',
                        'order'            => 6,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 3000,
                        'is_free_preview'  => false,
                    ],
                ],
            ],

            // ── Course 6: Intermediate — React ───────────────────────────────
            [
                'course' => [
                    'level_id'        => $intermediate->id,
                    'title'           => 'React 18 & Modern Hooks',
                    'slug'            => 'react-18-modern-hooks',
                    'price'           => 44.99,
                    'description'     => 'Build fast, scalable React applications using hooks, context, React Query, and React Router. Includes a full project from scratch.',
                    'image'           => null,
                    'status'          => 'published',
                    'is_free_preview' => false,
                ],
                'lessons' => [
                    [
                        'title'            => 'React Setup with Vite',
                        'order'            => 1,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 1200,
                        'is_free_preview'  => true,
                    ],
                    [
                        'title'            => 'JSX & Component Basics',
                        'order'            => 2,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 2800,
                        'is_free_preview'  => false,
                    ],
                    [
                        'title'            => 'useState & useEffect',
                        'order'            => 3,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 3300,
                        'is_free_preview'  => false,
                    ],
                    [
                        'title'            => 'useContext & Custom Hooks',
                        'order'            => 4,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 3100,
                        'is_free_preview'  => false,
                    ],
                    [
                        'title'            => 'React Query for Data Fetching',
                        'order'            => 5,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 3600,
                        'is_free_preview'  => false,
                    ],
                    [
                        'title'            => 'React Router v6',
                        'order'            => 6,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 2700,
                        'is_free_preview'  => false,
                    ],
                ],
            ],

            // ── Course 7: Advanced — Node.js & Microservices ─────────────────
            [
                'course' => [
                    'level_id'        => $advanced->id,
                    'title'           => 'Node.js Microservices with Docker',
                    'slug'            => 'nodejs-microservices-docker',
                    'price'           => 89.99,
                    'description'     => 'Design and deploy production-grade microservices using Node.js, Express, RabbitMQ, and Docker Compose. Covers service discovery, auth, and CI/CD.',
                    'image'           => null,
                    'status'          => 'published',
                    'is_free_preview' => false,
                ],
                'lessons' => [
                    [
                        'title'            => 'Microservices vs Monoliths',
                        'order'            => 1,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 2400,
                        'is_free_preview'  => true,
                    ],
                    [
                        'title'            => 'Dockerising a Node.js App',
                        'order'            => 2,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 3000,
                        'is_free_preview'  => false,
                    ],
                    [
                        'title'            => 'Inter-service Communication with RabbitMQ',
                        'order'            => 3,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 4200,
                        'is_free_preview'  => false,
                    ],
                    [
                        'title'            => 'JWT Auth Gateway',
                        'order'            => 4,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 3800,
                        'is_free_preview'  => false,
                    ],
                    [
                        'title'            => 'Docker Compose Orchestration',
                        'order'            => 5,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 3600,
                        'is_free_preview'  => false,
                    ],
                    [
                        'title'            => 'CI/CD Pipeline with GitHub Actions',
                        'order'            => 6,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 4000,
                        'is_free_preview'  => false,
                    ],
                ],
            ],

            // ── Course 8: Beginner — Git & GitHub ────────────────────────────
            [
                'course' => [
                    'level_id'        => $beginner->id,
                    'title'           => 'Git & GitHub for Beginners',
                    'slug'            => 'git-github-for-beginners',
                    'price'           => 0.00,
                    'description'     => 'Version control from scratch. Learn commits, branches, merging, pull requests, and collaborating on open-source projects using GitHub.',
                    'image'           => null,
                    'status'          => 'published',
                    'is_free_preview' => true,
                ],
                'lessons' => [
                    [
                        'title'            => 'What is Version Control?',
                        'order'            => 1,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 1200,
                        'is_free_preview'  => true,
                    ],
                    [
                        'title'            => 'Installing Git & First Commit',
                        'order'            => 2,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 1800,
                        'is_free_preview'  => true,
                    ],
                    [
                        'title'            => 'Branching & Merging',
                        'order'            => 3,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 2200,
                        'is_free_preview'  => false,
                    ],
                    [
                        'title'            => 'Resolving Merge Conflicts',
                        'order'            => 4,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 2000,
                        'is_free_preview'  => false,
                    ],
                    [
                        'title'            => 'Pull Requests & Code Reviews',
                        'order'            => 5,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 2400,
                        'is_free_preview'  => false,
                    ],
                ],
            ],

            // ── Course 9: Intermediate — TypeScript ──────────────────────────
            [
                'course' => [
                    'level_id'        => $intermediate->id,
                    'title'           => 'TypeScript Masterclass',
                    'slug'            => 'typescript-masterclass',
                    'price'           => 34.99,
                    'description'     => 'Add type safety to your JavaScript projects. Covers types, interfaces, generics, decorators, and integrating TypeScript with React and Node.',
                    'image'           => null,
                    'status'          => 'published',
                    'is_free_preview' => false,
                ],
                'lessons' => [
                    [
                        'title'            => 'Why TypeScript?',
                        'order'            => 1,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 1400,
                        'is_free_preview'  => true,
                    ],
                    [
                        'title'            => 'Basic Types & Type Inference',
                        'order'            => 2,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 2600,
                        'is_free_preview'  => false,
                    ],
                    [
                        'title'            => 'Interfaces & Type Aliases',
                        'order'            => 3,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 2800,
                        'is_free_preview'  => false,
                    ],
                    [
                        'title'            => 'Generics',
                        'order'            => 4,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 3200,
                        'is_free_preview'  => false,
                    ],
                    [
                        'title'            => 'TypeScript with React',
                        'order'            => 5,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 3600,
                        'is_free_preview'  => false,
                    ],
                ],
            ],

            // ── Course 10: Advanced — System Design ──────────────────────────
            [
                'course' => [
                    'level_id'        => $advanced->id,
                    'title'           => 'System Design for Developers',
                    'slug'            => 'system-design-for-developers',
                    'price'           => 99.99,
                    'description'     => 'Prepare for senior engineering roles. Design scalable systems: load balancers, caching strategies, databases at scale, message queues, and CDN architecture.',
                    'image'           => null,
                    'status'          => 'published',
                    'is_free_preview' => false,
                ],
                'lessons' => [
                    [
                        'title'            => 'Scalability Fundamentals',
                        'order'            => 1,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 3000,
                        'is_free_preview'  => true,
                    ],
                    [
                        'title'            => 'Load Balancing Strategies',
                        'order'            => 2,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 3400,
                        'is_free_preview'  => false,
                    ],
                    [
                        'title'            => 'Caching with Redis',
                        'order'            => 3,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 4000,
                        'is_free_preview'  => false,
                    ],
                    [
                        'title'            => 'Database Sharding & Replication',
                        'order'            => 4,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 4500,
                        'is_free_preview'  => false,
                    ],
                    [
                        'title'            => 'Message Queues & Event-Driven Design',
                        'order'            => 5,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 4200,
                        'is_free_preview'  => false,
                    ],
                    [
                        'title'            => 'Designing a URL Shortener End-to-End',
                        'order'            => 6,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 5400,
                        'is_free_preview'  => false,
                    ],
                ],
            ],

            // ── Course 11: Beginner — SQL ─────────────────────────────────────
            [
                'course' => [
                    'level_id'        => $beginner->id,
                    'title'           => 'SQL for Beginners',
                    'slug'            => 'sql-for-beginners',
                    'price'           => 19.99,
                    'description'     => 'Write SQL queries with confidence. Learn SELECT, JOINs, aggregations, subqueries, and basic database design principles using MySQL.',
                    'image'           => null,
                    'status'          => 'published',
                    'is_free_preview' => false,
                ],
                'lessons' => [
                    [
                        'title'            => 'Intro to Databases & MySQL Setup',
                        'order'            => 1,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 1500,
                        'is_free_preview'  => true,
                    ],
                    [
                        'title'            => 'SELECT, WHERE & ORDER BY',
                        'order'            => 2,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 2200,
                        'is_free_preview'  => false,
                    ],
                    [
                        'title'            => 'JOINs Explained',
                        'order'            => 3,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 2800,
                        'is_free_preview'  => false,
                    ],
                    [
                        'title'            => 'Aggregate Functions & GROUP BY',
                        'order'            => 4,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 2400,
                        'is_free_preview'  => false,
                    ],
                    [
                        'title'            => 'Subqueries & CTEs',
                        'order'            => 5,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 3000,
                        'is_free_preview'  => false,
                    ],
                ],
            ],

            // ── Course 12: Intermediate — REST API Design ─────────────────────
            [
                'course' => [
                    'level_id'        => $intermediate->id,
                    'title'           => 'RESTful API Design Best Practices',
                    'slug'            => 'restful-api-design-best-practices',
                    'price'           => 29.99,
                    'description'     => 'Design clean, versioned, well-documented REST APIs. Covers HTTP semantics, authentication patterns, error handling, pagination, and OpenAPI/Swagger.',
                    'image'           => null,
                    'status'          => 'published',
                    'is_free_preview' => false,
                ],
                'lessons' => [
                    [
                        'title'            => 'HTTP Methods & Status Codes',
                        'order'            => 1,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 2000,
                        'is_free_preview'  => true,
                    ],
                    [
                        'title'            => 'Resource Naming & URL Design',
                        'order'            => 2,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 2200,
                        'is_free_preview'  => false,
                    ],
                    [
                        'title'            => 'Authentication: API Keys, OAuth & JWT',
                        'order'            => 3,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 3600,
                        'is_free_preview'  => false,
                    ],
                    [
                        'title'            => 'Pagination, Filtering & Sorting',
                        'order'            => 4,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 2600,
                        'is_free_preview'  => false,
                    ],
                    [
                        'title'            => 'Documenting APIs with OpenAPI & Swagger',
                        'order'            => 5,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 3000,
                        'is_free_preview'  => false,
                    ],
                ],
            ],

            // ── Course 13: Advanced — Kubernetes ─────────────────────────────
            [
                'course' => [
                    'level_id'        => $advanced->id,
                    'title'           => 'Kubernetes in Production',
                    'slug'            => 'kubernetes-in-production',
                    'price'           => 94.99,
                    'description'     => 'Deploy and manage containerised apps at scale. Covers pods, deployments, services, ingress, Helm charts, horizontal scaling, and monitoring with Prometheus.',
                    'image'           => null,
                    'status'          => 'published',
                    'is_free_preview' => false,
                ],
                'lessons' => [
                    [
                        'title'            => 'Kubernetes Architecture Overview',
                        'order'            => 1,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 3000,
                        'is_free_preview'  => true,
                    ],
                    [
                        'title'            => 'Pods, Deployments & ReplicaSets',
                        'order'            => 2,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 3600,
                        'is_free_preview'  => false,
                    ],
                    [
                        'title'            => 'Services & Ingress Controllers',
                        'order'            => 3,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 3800,
                        'is_free_preview'  => false,
                    ],
                    [
                        'title'            => 'ConfigMaps, Secrets & Volumes',
                        'order'            => 4,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 3400,
                        'is_free_preview'  => false,
                    ],
                    [
                        'title'            => 'Helm Chart Packaging',
                        'order'            => 5,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 4000,
                        'is_free_preview'  => false,
                    ],
                    [
                        'title'            => 'Monitoring with Prometheus & Grafana',
                        'order'            => 6,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 4800,
                        'is_free_preview'  => false,
                    ],
                ],
            ],

            // ── Course 14: Beginner — Linux Command Line ──────────────────────
            [
                'course' => [
                    'level_id'        => $beginner->id,
                    'title'           => 'Linux Command Line Basics',
                    'slug'            => 'linux-command-line-basics',
                    'price'           => 0.00,
                    'description'     => 'Get comfortable in the terminal. Navigate the file system, manage files, write basic shell scripts, and work with processes and permissions.',
                    'image'           => null,
                    'status'          => 'published',
                    'is_free_preview' => true,
                ],
                'lessons' => [
                    [
                        'title'            => 'Navigating the File System',
                        'order'            => 1,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 1800,
                        'is_free_preview'  => true,
                    ],
                    [
                        'title'            => 'Files, Directories & Permissions',
                        'order'            => 2,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 2200,
                        'is_free_preview'  => true,
                    ],
                    [
                        'title'            => 'Processes & Job Control',
                        'order'            => 3,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 2000,
                        'is_free_preview'  => false,
                    ],
                    [
                        'title'            => 'Bash Scripting Fundamentals',
                        'order'            => 4,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 3200,
                        'is_free_preview'  => false,
                    ],
                ],
            ],

            // ── Course 15: Intermediate — Tailwind CSS ────────────────────────
            [
                'course' => [
                    'level_id'        => $intermediate->id,
                    'title'           => 'Tailwind CSS: Build Stunning UIs',
                    'slug'            => 'tailwind-css-build-stunning-uis',
                    'price'           => 24.99,
                    'description'     => 'Master utility-first CSS with Tailwind. Build responsive, dark-mode-ready, accessible interfaces faster than ever — no custom CSS required.',
                    'image'           => null,
                    'status'          => 'published',
                    'is_free_preview' => false,
                ],
                'lessons' => [
                    [
                        'title'            => 'Tailwind Setup & Core Concepts',
                        'order'            => 1,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 1600,
                        'is_free_preview'  => true,
                    ],
                    [
                        'title'            => 'Responsive Design with Breakpoints',
                        'order'            => 2,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 2400,
                        'is_free_preview'  => false,
                    ],
                    [
                        'title'            => 'Dark Mode & Custom Themes',
                        'order'            => 3,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 2800,
                        'is_free_preview'  => false,
                    ],
                    [
                        'title'            => 'Component Patterns with @apply',
                        'order'            => 4,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 3000,
                        'is_free_preview'  => false,
                    ],
                    [
                        'title'            => 'Building a Full Landing Page',
                        'order'            => 5,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 5400,
                        'is_free_preview'  => false,
                    ],
                ],
            ],

            // ── Course 16: Advanced — GraphQL API ────────────────────────────
            [
                'course' => [
                    'level_id'        => $advanced->id,
                    'title'           => 'GraphQL API Development',
                    'slug'            => 'graphql-api-development',
                    'price'           => 59.99,
                    'description'     => 'Build flexible, efficient APIs with GraphQL. Covers schema design, resolvers, mutations, subscriptions, DataLoader for N+1 prevention, and securing your API.',
                    'image'           => null,
                    'status'          => 'published',
                    'is_free_preview' => false,
                ],
                'lessons' => [
                    [
                        'title'            => 'GraphQL vs REST',
                        'order'            => 1,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 2000,
                        'is_free_preview'  => true,
                    ],
                    [
                        'title'            => 'Schema Definition Language',
                        'order'            => 2,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 2800,
                        'is_free_preview'  => false,
                    ],
                    [
                        'title'            => 'Resolvers, Queries & Mutations',
                        'order'            => 3,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 3600,
                        'is_free_preview'  => false,
                    ],
                    [
                        'title'            => 'Subscriptions & Real-time Data',
                        'order'            => 4,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 3400,
                        'is_free_preview'  => false,
                    ],
                    [
                        'title'            => 'DataLoader & N+1 Problem',
                        'order'            => 5,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 3800,
                        'is_free_preview'  => false,
                    ],
                ],
            ],

            // ── Course 17: Intermediate — PHP OOP ────────────────────────────
            [
                'course' => [
                    'level_id'        => $intermediate->id,
                    'title'           => 'Object-Oriented PHP',
                    'slug'            => 'object-oriented-php',
                    'price'           => 29.99,
                    'description'     => 'Take PHP beyond procedural scripting. Classes, interfaces, traits, abstract classes, dependency injection, and building a mini MVC from scratch.',
                    'image'           => null,
                    'status'          => 'published',
                    'is_free_preview' => false,
                ],
                'lessons' => [
                    [
                        'title'            => 'Classes & Objects',
                        'order'            => 1,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 2400,
                        'is_free_preview'  => true,
                    ],
                    [
                        'title'            => 'Inheritance & Abstract Classes',
                        'order'            => 2,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 2800,
                        'is_free_preview'  => false,
                    ],
                    [
                        'title'            => 'Interfaces & Traits',
                        'order'            => 3,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 3000,
                        'is_free_preview'  => false,
                    ],
                    [
                        'title'            => 'Dependency Injection & Containers',
                        'order'            => 4,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 3400,
                        'is_free_preview'  => false,
                    ],
                    [
                        'title'            => 'Build a Mini MVC Framework',
                        'order'            => 5,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 5400,
                        'is_free_preview'  => false,
                    ],
                ],
            ],

            // ── Course 18: Beginner — Command Line Tools ──────────────────────
            [
                'course' => [
                    'level_id'        => $beginner->id,
                    'title'           => 'Developer Tools & Productivity',
                    'slug'            => 'developer-tools-productivity',
                    'price'           => 0.00,
                    'description'     => 'A free crash course covering the tools every developer relies on daily: VS Code, browser DevTools, npm basics, and productivity shortcuts.',
                    'image'           => null,
                    'status'          => 'published',
                    'is_free_preview' => true,
                ],
                'lessons' => [
                    [
                        'title'            => 'VS Code Setup & Must-Have Extensions',
                        'order'            => 1,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 1800,
                        'is_free_preview'  => true,
                    ],
                    [
                        'title'            => 'Browser DevTools Deep Dive',
                        'order'            => 2,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 2400,
                        'is_free_preview'  => true,
                    ],
                    [
                        'title'            => 'npm & Package Management Basics',
                        'order'            => 3,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 2000,
                        'is_free_preview'  => false,
                    ],
                    [
                        'title'            => 'Keyboard Shortcuts & Workflow Tips',
                        'order'            => 4,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 1600,
                        'is_free_preview'  => false,
                    ],
                ],
            ],

            // ── Course 19: Advanced — Testing with Pest & PHPUnit ────────────
            [
                'course' => [
                    'level_id'        => $advanced->id,
                    'title'           => 'Testing PHP Applications',
                    'slug'            => 'testing-php-applications',
                    'price'           => 54.99,
                    'description'     => 'Write robust, maintainable test suites with PHPUnit and Pest. Covers unit, integration, and feature tests — including mocking, factories, and TDD workflows.',
                    'image'           => null,
                    'status'          => 'published',
                    'is_free_preview' => false,
                ],
                'lessons' => [
                    [
                        'title'            => 'Why Test? Unit vs Integration vs Feature',
                        'order'            => 1,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 2000,
                        'is_free_preview'  => true,
                    ],
                    [
                        'title'            => 'Writing Your First PHPUnit Test',
                        'order'            => 2,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 2400,
                        'is_free_preview'  => false,
                    ],
                    [
                        'title'            => 'Pest: Expressive Test Syntax',
                        'order'            => 3,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 2800,
                        'is_free_preview'  => false,
                    ],
                    [
                        'title'            => 'Mocking & Fakes',
                        'order'            => 4,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 3600,
                        'is_free_preview'  => false,
                    ],
                    [
                        'title'            => 'Test-Driven Development Workflow',
                        'order'            => 5,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 4000,
                        'is_free_preview'  => false,
                    ],
                    [
                        'title'            => 'Testing APIs & Database Interactions',
                        'order'            => 6,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 4200,
                        'is_free_preview'  => false,
                    ],
                ],
            ],

            // ── Course 20: Intermediate — Next.js ────────────────────────────
            [
                'course' => [
                    'level_id'        => $intermediate->id,
                    'title'           => 'Next.js 14: Full-Stack React',
                    'slug'            => 'nextjs-14-full-stack-react',
                    'price'           => 49.99,
                    'description'     => 'Build production-ready full-stack apps with Next.js 14. Covers the App Router, Server Components, Server Actions, API routes, authentication, and deployment to Vercel.',
                    'image'           => null,
                    'status'          => 'published',
                    'is_free_preview' => false,
                ],
                'lessons' => [
                    [
                        'title'            => 'Next.js App Router Overview',
                        'order'            => 1,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 2000,
                        'is_free_preview'  => true,
                    ],
                    [
                        'title'            => 'Server Components vs Client Components',
                        'order'            => 2,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 3200,
                        'is_free_preview'  => false,
                    ],
                    [
                        'title'            => 'Data Fetching & Caching Strategies',
                        'order'            => 3,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 3600,
                        'is_free_preview'  => false,
                    ],
                    [
                        'title'            => 'Server Actions & Form Handling',
                        'order'            => 4,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 3400,
                        'is_free_preview'  => false,
                    ],
                    [
                        'title'            => 'Authentication with NextAuth.js',
                        'order'            => 5,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 4000,
                        'is_free_preview'  => false,
                    ],
                    [
                        'title'            => 'Deploying to Vercel',
                        'order'            => 6,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 2400,
                        'is_free_preview'  => false,
                    ],
                ],
            ],

            // ── Course 21: Draft — should NOT appear on public listing ─────────
            [
                'course' => [
                    'level_id'        => $advanced->id,
                    'title'           => 'Rust for Web Developers (Coming Soon)',
                    'slug'            => 'rust-for-web-developers',
                    'price'           => 69.99,
                    'description'     => 'A draft course — not visible to the public.',
                    'image'           => null,
                    'status'          => 'draft',
                    'is_free_preview' => false,
                ],
                'lessons' => [
                    [
                        'title'            => 'Why Rust?',
                        'order'            => 1,
                        'video_url'        => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                        'duration_seconds' => 1800,
                        'is_free_preview'  => false,
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

        $this->command->info('✅ Courses and lessons seeded (20 published, 1 draft).');
    }
}