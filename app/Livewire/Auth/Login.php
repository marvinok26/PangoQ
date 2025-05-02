<?php

namespace App\Livewire\Auth;

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class Login extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->form->validate();

        try {
            $this->form->authenticate();
        } catch (ValidationException $e) {
            $this->addError('email', $e->getMessage());
            return;
        }

        session()->regenerate();

        $this->redirect(session('url.intended', route('dashboard')), navigate: true);
    }

    public function render()
    {
        return view('livewire.pages.auth.login');
    }
}