<?php

namespace App\Services;

use App\Actions\Auth\RegisterUserAction;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function __construct(
        private RegisterUserAction $registerUser,
    ) {}

    public function register(array $data): array
    {
        $user  = $this->registerUser->execute($data);
        $token = $user->createToken('api')->plainTextToken;

        return compact('user', 'token');
    }

    public function login(array $credentials): array
    {
        if (! Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $user  = Auth::user();
        $token = $user->createToken('api')->plainTextToken;

        return compact('user', 'token');
    }

    public function logout(User $user): void
    {
        $user->currentAccessToken()->delete();
    }
}