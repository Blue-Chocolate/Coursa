<?php

namespace App\Livewire\Auth;

use Livewire\Attributes\Rule;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class Login extends Component
{
    #[Rule('required|email')]
    public string $email = '';

    #[Rule('required|string')]
    public string $password = '';

    public bool $remember     = false;
    public bool $showPassword = false;
    public int  $attempts     = 0;

    public function login(): void
    {
        $this->validate();

        if (! Auth::attempt(
            ['email' => $this->email, 'password' => $this->password],
            $this->remember
        )) {
            $this->attempts++;
            $this->password = '';

            throw ValidationException::withMessages([
                'email' => 'These credentials do not match our records.',
            ]);
        }

        session()->regenerate();
        $this->redirect(route('home'), navigate: true);
    }

    public function render()
    {
        return view('livewire.auth.login')
            ->layout('layouts.auth')
            ->title('Sign In');
    }
}