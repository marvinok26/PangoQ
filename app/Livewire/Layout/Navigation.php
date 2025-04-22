<?php

namespace App\Livewire\Layout;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Navigation extends Component
{
    public function render()
    {
        return view('livewire.layout.navigation');
    }

    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        
        return redirect()->route('home');
    }
}