<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\SavingsWallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Display the user's profile dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        $user = Auth::user();
        $totalBalance = $user->total_savings ?? 0;
        $totalTarget = $user->total_savings_goal ?? 0;
        $progressPercentage = $user->savings_progress_percentage ?? 0;

        // Get the user's trips with their savings wallets
        $trips = $user->trips()->with('savingsWallet')->latest()->take(3)->get();

        return view('livewire.pages.profile.show', compact('user', 'totalBalance', 'totalTarget', 'progressPercentage', 'trips'));
    }

    /**
     * Show the form for editing the user's profile.
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        return view('livewire.pages.profile.edit', [
            'user' => Auth::user(),
        ]);
    }

/**
 * Update the user's profile information.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\RedirectResponse
 */
public function update(Request $request)
{
    $user = Auth::user();

    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
        'phone_number' => ['nullable', 'string', 'max:20'],
        'id_card_number' => ['nullable', 'string', 'max:50'],
        'passport_number' => ['nullable', 'string', 'max:50'],
        'date_of_birth' => ['nullable', 'date'],
        'gender' => ['nullable', Rule::in(['male', 'female', 'other'])],
        'nationality' => ['nullable', 'string', 'max:100'],
        'address' => ['nullable', 'string', 'max:255'],
        'profile_photo' => ['nullable', 'image', 'max:1024'],
    ]);

    // Handle the profile photo separately
    if ($request->hasFile('profile_photo')) {
        // Delete old photo if exists
        if ($user->profile_photo_path && !filter_var($user->profile_photo_path, FILTER_VALIDATE_URL)) {
            Storage::delete('public/' . $user->profile_photo_path);
        }

        $path = $request->file('profile_photo')->store('profile-photos', 'public');
        
        // Save the profile photo path directly to the user model
        $user->profile_photo_path = $path;
    }

    // Remove profile_photo from the validated array since it's not a database column
    if (isset($validated['profile_photo'])) {
        unset($validated['profile_photo']);
    }

    // Update user with validated data
    $user->fill($validated);
    $user->save();

    return redirect()->route('profile.edit')->with('success', 'Profile updated successfully.');
}

    /**
     * Show the security settings page.
     *
     * @return \Illuminate\View\View
     */
    public function security()
    {
        return view('livewire.pages.profile.security');
    }

    /**
     * Update the user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'string', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('profile.security')->with('success', 'Password updated successfully.');
    }

    /**
     * Show the notifications settings page.
     *
     * @return \Illuminate\View\View
     */
    public function notifications()
    {
        return view('livewire.pages.profile.notifications');
    }

    /**
     * Update notification preferences.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateNotifications(Request $request)
    {
        // Implement notification settings update logic
        return redirect()->route('profile.notifications')->with('success', 'Notification preferences updated.');
    }

    /**
     * Delete the user's account.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Show the account settings page.
     *
     * @return \Illuminate\View\View
     */
    public function account()
    {
        return view('livewire.pages.profile.account', [
            'user' => Auth::user(),
        ]);
    }

    /**
     * Update account settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateAccount(Request $request)
    {
        $validated = $request->validate([
            'account_type' => ['required', Rule::in(['personal', 'business'])],
            'currency' => ['required', 'string', 'max:3'],
            'linked_bank_account' => ['nullable', 'string', 'max:255'],
            'wallet_provider' => ['nullable', 'string', 'max:255'],
            'account_status' => ['required', Rule::in(['active', 'inactive', 'pending'])],
            'preferred_payment_method' => ['required', Rule::in(['wallet', 'bank_transfer', 'credit_card', 'm_pesa'])],
            // Removed daily_transaction_limit since it doesn't exist
        ]);

        Auth::user()->update($validated);

        return redirect()->route('profile.account')->with('success', 'Account settings updated successfully.');
    }
}