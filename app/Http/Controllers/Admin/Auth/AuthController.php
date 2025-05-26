<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Show the admin login form
     */
    public function showLogin(): View
    {
        return view('admin.auth.login');
    }

    /**
     * Handle admin login
     */
    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();
            
            // Check if user is an admin
            if (!$user->isAdmin()) {
                Auth::logout();
                throw ValidationException::withMessages([
                    'email' => 'Access denied. Admin privileges required.',
                ]);
            }

            $request->session()->regenerate();

            // Log admin login
            ActivityLog::log('admin_login', $user, [
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            return redirect()->intended(route('admin.dashboard'));
        }

        throw ValidationException::withMessages([
            'email' => 'These credentials do not match our records.',
        ]);
    }

    /**
     * Handle admin logout
     */
    public function logout(Request $request): RedirectResponse
    {
        $user = Auth::user();

        // Log admin logout
        if ($user && $user->isAdmin()) {
            ActivityLog::log('admin_logout', $user, [
                'ip_address' => $request->ip()
            ]);
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('status', 'You have been logged out successfully.');
    }
}