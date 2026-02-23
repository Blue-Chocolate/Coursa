<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ── Admin ─────────────────────────────────────────────────────────────
        User::firstOrCreate(
            ['email' => 'admin@lms.test'],
            [
                'name'     => 'Admin',
                'email'    => 'admin@lms.test',
                'password' => Hash::make('password'),
                'is_admin' => true,
            ]
        );

        // ── Regular user ──────────────────────────────────────────────────────
        User::firstOrCreate(
            ['email' => 'student@lms.test'],
            [
                'name'     => 'John Student',
                'email'    => 'student@lms.test',
                'password' => Hash::make('password'),
                'is_admin' => false,
            ]
        );

        $this->command->info('✅ Users seeded.');
        $this->command->table(
            ['Role', 'Email', 'Password'],
            [
                ['Admin',   'admin@lms.test',   'password'],
                ['Student', 'student@lms.test', 'password'],
            ]
        );
    }
}