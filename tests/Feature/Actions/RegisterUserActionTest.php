<?php 

use App\Actions\Auth\RegisterUserAction;
use App\Mail\WelcomeMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

describe('RegisterUserAction', function () {

    it('creates a user with hashed password', function () {
        Mail::fake();

        $action = new RegisterUserAction();
        $user   = $action->execute([
            'name'     => 'Jane Doe',
            'email'    => 'jane@example.com',
            'password' => 'password123',
        ]);

        expect($user)->toBeInstanceOf(User::class)
            ->and($user->name)->toBe('Jane Doe')
            ->and($user->email)->toBe('jane@example.com')
            ->and($user->password)->not->toBe('password123'); // hashed

        $this->assertDatabaseHas('users', ['email' => 'jane@example.com']);
    });

    it('queues a welcome email after registration', function () {
        Mail::fake();

        $action = new RegisterUserAction();
        $user   = $action->execute([
            'name'     => 'Jane Doe',
            'email'    => 'jane@example.com',
            'password' => 'password123',
        ]);

        Mail::assertQueued(WelcomeMail::class, fn ($mail) =>
            $mail->hasTo('jane@example.com')
        );
    });

    it('stores user as non-admin by default', function () {
        Mail::fake();

        $action = new RegisterUserAction();
        $user   = $action->execute([
            'name'     => 'Jane Doe',
            'email'    => 'jane@example.com',
            'password' => 'password123',
        ]);

        expect((bool) $user->is_admin)->toBeFalse();
    });
});