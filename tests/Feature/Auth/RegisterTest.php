<?php

use App\Livewire\Auth\Register;
use App\Models\User;
use Livewire\Livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

describe('Registration', function () {

    it('renders the register page', function () {
        $this->get('/register')->assertOk();
    });

    it('redirects authenticated users away from register', function () {
        $user = User::factory()->create();
        $this->actingAs($user)->get('/register')->assertRedirect('/');
    });

    it('registers a new user successfully', function () {
        Livewire::test(Register::class)
            ->set('name', 'Jane Doe')
            ->set('email', 'jane@example.com')
            ->set('password', 'Password1!')
            ->set('password_confirmation', 'Password1!')
            ->call('register')
            ->assertHasNoErrors()
            ->assertRedirect('/');

        $this->assertDatabaseHas('users', ['email' => 'jane@example.com']);
    });

    it('fails with missing name', function () {
        Livewire::test(Register::class)
            ->set('name', '')
            ->set('email', 'jane@example.com')
            ->set('password', 'Password1!')
            ->set('password_confirmation', 'Password1!')
            ->call('register')
            ->assertHasErrors(['name']);
    });

    it('fails with invalid email', function () {
        Livewire::test(Register::class)
            ->set('name', 'Jane')
            ->set('email', 'not-an-email')
            ->set('password', 'Password1!')
            ->set('password_confirmation', 'Password1!')
            ->call('register')
            ->assertHasErrors(['email']);
    });

    it('fails with duplicate email', function () {
        User::factory()->create(['email' => 'jane@example.com']);

        Livewire::test(Register::class)
            ->set('name', 'Jane')
            ->set('email', 'jane@example.com')
            ->set('password', 'Password1!')
            ->set('password_confirmation', 'Password1!')
            ->call('register')
            ->assertHasErrors(['email']);
    });

    it('fails when passwords do not match', function () {
    Livewire::test(Register::class)
        ->set('name', 'Jane')
        ->set('email', 'jane@example.com')
        ->set('password', 'Password1!')
        ->set('password_confirmation', 'Different1!')
        ->call('register')
        ->assertHasErrors(['password_confirmation']);  
});

    it('fails with password shorter than 8 characters', function () {
        Livewire::test(Register::class)
            ->set('name', 'Jane')
            ->set('email', 'jane@example.com')
            ->set('password', 'short')
            ->set('password_confirmation', 'short')
            ->call('register')
            ->assertHasErrors(['password']);
    });
});