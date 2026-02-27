<?php

use App\Livewire\Auth\Login;
use App\Models\User;
use Livewire\Livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

describe('Login', function () {

    it('renders the login page', function () {
        $this->get('/login')->assertOk();
    });

    it('redirects authenticated users away from login', function () {
        $user = User::factory()->create();
        $this->actingAs($user)->get('/login')->assertRedirect('/');
    });

    it('logs in with correct credentials', function () {
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
        ]);

        Livewire::test(Login::class)
            ->set('email', $user->email)
            ->set('password', 'password123')
            ->call('login')
            ->assertHasNoErrors()
            ->assertRedirect('/');

        $this->assertAuthenticatedAs($user);
    });

    it('fails with wrong password', function () {
        $user = User::factory()->create([
            'password' => bcrypt('correct'),
        ]);

        Livewire::test(Login::class)
            ->set('email', $user->email)
            ->set('password', 'wrong')
            ->call('login')
            ->assertHasErrors(['email']);

        $this->assertGuest();
    });

    it('fails with non-existent email', function () {
        Livewire::test(Login::class)
            ->set('email', 'ghost@example.com')
            ->set('password', 'anything')
            ->call('login')
            ->assertHasErrors(['email']);
    });

    it('logs out authenticated user', function () {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post('/logout')
            ->assertRedirect('/');

        $this->assertGuest();
    });
});