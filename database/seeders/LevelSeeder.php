<?php

namespace Database\Seeders;

use App\Models\Level;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class LevelSeeder extends Seeder
{
    public function run(): void
    {
        $levels = [
            ['name' => 'Beginner',     'slug' => 'beginner',     'order' => 1],
            ['name' => 'Intermediate', 'slug' => 'intermediate', 'order' => 2],
            ['name' => 'Advanced',     'slug' => 'advanced',     'order' => 3],
        ];

        foreach ($levels as $level) {
            Level::firstOrCreate(
                ['slug' => $level['slug']],
                $level
            );
        }

        $this->command->info('✅ Levels seeded.');
    }
}