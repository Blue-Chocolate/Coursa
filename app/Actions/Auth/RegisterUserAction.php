<?php

namespace App\Actions\Auth;

use App\Mail\WelcomeMail;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class RegisterUserAction
{
    public function execute(array $data): User
    {
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        // Dispatched async — does not block the response
        Mail::to($user->email)->queue(new WelcomeMail($user));

        return $user;
    }
}