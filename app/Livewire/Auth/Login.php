<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class Login extends Component
{
    public $login = '';
    public $password = '';
    public $remember = false;

    protected $rules = [
        'login' => 'required|string',
        'password' => 'required|string',
    ];

    protected $messages = [
        'login.required' => 'L\'email ou nom d\'utilisateur est requis.',
        'password.required' => 'Le mot de passe est requis.',
    ];

    public function authenticate()
    {
        $this->validate();
        $field = filter_var($this->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        
        $credentials = [
            $field => $this->login,
            'password' => $this->password
        ];

        if (Auth::attempt($credentials, $this->remember)) {
            session()->regenerate();
            
            $this->redirect(route('dashboard'), navigate: true);
        } else {
            throw ValidationException::withMessages([
                'login' => 'Ces identifiants ne correspondent pas à nos enregistrements.',
            ]);
        }
    }

    public function render()
    {
        return view('livewire.auth.login')
            ->layout('layouts.guest');
    }
}