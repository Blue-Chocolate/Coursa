<?php

namespace App\Livewire\Auth;

use App\Actions\Auth\RegisterUserAction;
use Livewire\Attributes\Rule;
use Livewire\Component;

class Register extends Component
{
    #[Rule('required|string|max:255')]
    public string $name = '';

    #[Rule('required|email|unique:users,email')]
    public string $email = '';

    #[Rule('required|string|min:8')]
    public string $password = '';

    #[Rule('required|same:password')]
    public string $password_confirmation = '';

    public bool $showPassword = false;
    public bool $showConfirm  = false;

    public function register(RegisterUserAction $action): void
    {
        $this->validate();

        $user = $action->execute([
            'name'     => $this->name,
            'email'    => $this->email,
            'password' => $this->password,
        ]);

        auth()->login($user);

        $this->redirect(route('home'), navigate: true);
    }

    public function render()
    {
        return view('livewire.auth.register')
            ->layout('components.layouts.auth')
            ->title('Create Account');
    }
}