<!-- resources/views/livewire/pages/profile/edit.blade.php -->
@extends('layouts.dashboard')
@section('title', 'Edit Profile - PangoQ')
@section('content')
<div class="py-8 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Profile Settings</h1>
            <p class="mt-2 text-gray-600">Manage your account information and preferences</p>
        </div>

        <div class="space-y-10">
            <!-- Personal Information Section -->
            <div class="bg-white shadow-sm rounded-xl overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100">
                    <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                        </svg>
                        Personal Information
                    </h2>
                    <p class="mt-1 text-sm text-gray-500">
                        Update your account's profile information and personal details
                    </p>
                </div>

                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="px-6 py-5">
                    @csrf
                    @method('PATCH')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Left Column - Basic Info -->
                        <div class="space-y-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                                    class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                                    class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="phone_number" class="block text-sm font-medium text-gray-700">Phone Number</label>
                                <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number', $user->phone_number) }}"
                                    class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                @error('phone_number')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                                <input type="text" name="address" id="address" value="{{ old('address', $user->address) }}"
                                    class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                @error('address')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Right Column - Additional Info & Profile Photo -->
                        <div class="space-y-6">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="id_card_number" class="block text-sm font-medium text-gray-700">ID Card Number</label>
                                    <input type="text" name="id_card_number" id="id_card_number" value="{{ old('id_card_number', $user->id_card_number) }}"
                                        class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                    @error('id_card_number')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="passport_number" class="block text-sm font-medium text-gray-700">Passport Number</label>
                                    <input type="text" name="passport_number" id="passport_number" value="{{ old('passport_number', $user->passport_number) }}"
                                        class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                    @error('passport_number')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="date_of_birth" class="block text-sm font-medium text-gray-700">Date of Birth</label>
                                    <input type="date" name="date_of_birth" id="date_of_birth" 
                                        value="{{ old('date_of_birth', $user->date_of_birth ? $user->date_of_birth->format('Y-m-d') : '') }}"
                                        class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                    @error('date_of_birth')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="nationality" class="block text-sm font-medium text-gray-700">Nationality</label>
                                    <input type="text" name="nationality" id="nationality" value="{{ old('nationality', $user->nationality) }}"
                                        class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                    @error('nationality')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label for="gender" class="block text-sm font-medium text-gray-700">Gender</label>
                                <select id="gender" name="gender"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                    <option value="">Select gender</option>
                                    <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ old('gender', $user->gender) == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('gender')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mt-6">
                                <label class="block text-sm font-medium text-gray-700 mb-3">Profile Photo</label>
                                <div class="flex items-start space-x-5">
                                    <div id="profile-photo-preview" class="flex-shrink-0 h-24 w-24 rounded-full overflow-hidden bg-gray-100 border-4 border-white shadow-md">
                                        @if ($user->profile_photo_path)
                                            <img src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="Profile Photo" class="h-full w-full object-cover" id="current-photo">
                                        @else
                                            <div class="h-full w-full flex items-center justify-center bg-gradient-to-r from-blue-500 to-indigo-600 text-white text-2xl font-semibold" id="initials-fallback">
                                                {{ $user->initials }}
                                            </div>
                                        @endif
                                    </div>

                                    <div class="flex flex-col space-y-3">
                                        <div class="relative">
                                            <input type="file" name="profile_photo" id="profile_photo" class="sr-only" accept="image/*" onchange="previewImage(this)">
                                            <label for="profile_photo" class="flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 cursor-pointer transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                Choose Photo
                                            </label>
                                        </div>
                                        <div id="selected-filename" class="text-xs text-gray-500 hidden">
                                            Selected: <span id="filename" class="font-medium"></span>
                                        </div>
                                        <p class="text-xs text-gray-500">
                                            JPG, PNG or GIF. Max 1MB.
                                        </p>
                                    </div>
                                </div>

                                <!-- Preview Panel - Initially Hidden -->
                                <div id="preview-panel" class="hidden mt-4 p-4 border border-gray-200 rounded-lg bg-gray-50 shadow-inner">
                                    <div class="flex items-center space-x-4">
                                        <div class="h-16 w-16 rounded-full overflow-hidden bg-white shadow">
                                            <img id="preview-image" src="#" alt="Profile Preview" class="h-full w-full object-cover">
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-700">Preview of your new profile photo</p>
                                            <button type="button" id="cancel-button" onclick="cancelSelection()" class="mt-1 text-xs text-red-600 hover:text-red-800">
                                                Cancel selection
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                
                                @error('profile_photo')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end">
                        <button type="submit" class="flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Save Personal Information
                        </button>
                    </div>
                </form>
            </div>

            <!-- Account Information Section -->
            <div class="bg-white shadow-sm rounded-xl overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100">
                    <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                        </svg>
                        Account Information
                    </h2>
                    <p class="mt-1 text-sm text-gray-500">
                        Update your wallet, payment preferences, and account details
                    </p>
                </div>

                <form action="{{ route('profile.account.update') }}" method="POST" class="px-6 py-5">
                    @csrf
                    @method('PATCH')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Left Column -->
                        <div class="space-y-6">
                            <div>
                                <label for="account_number" class="block text-sm font-medium text-gray-700">Account Number</label>
                                <div class="mt-1 flex rounded-md shadow-sm">
                                    <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                        </svg>
                                    </span>
                                    <input type="text" id="account_number" value="{{ $user->account_number ?? 'Auto-generated' }}" disabled
                                        class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md bg-gray-100 border-gray-300 text-gray-500 cursor-not-allowed">
                                </div>
                            </div>

                            <div>
                                <label for="account_type" class="block text-sm font-medium text-gray-700">Account Type</label>
                                <select id="account_type" name="account_type"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                    <option value="personal" {{ old('account_type', $user->account_type) == 'personal' ? 'selected' : '' }}>Personal</option>
                                    <option value="business" {{ old('account_type', $user->account_type) == 'business' ? 'selected' : '' }}>Business</option>
                                </select>
                                @error('account_type')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="account_status" class="block text-sm font-medium text-gray-700">Account Status</label>
                                <div class="mt-1 flex rounded-md shadow-sm">
                                    <select id="account_status" name="account_status"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                        <option value="active" {{ old('account_status', $user->account_status) == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ old('account_status', $user->account_status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        <option value="pending" {{ old('account_status', $user->account_status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                    </select>
                                </div>
                                @error('account_status')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="space-y-6">
                            <div>
                                <label for="currency" class="block text-sm font-medium text-gray-700">Currency</label>
                                <select id="currency" name="currency"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                    <option value="USD" {{ old('currency', $user->currency) == 'USD' ? 'selected' : '' }}>USD - United States Dollar</option>
                                    <option value="AUD" {{ old('currency', $user->currency) == 'AUD' ? 'selected' : '' }}>AUD - Australian Dollar</option>
                                    <option value="KES" {{ old('currency', $user->currency) == 'KES' ? 'selected' : '' }}>KES - Kenyan Shilling</option>
                                    <option value="GBP" {{ old('currency', $user->currency) == 'GBP' ? 'selected' : '' }}>GBP - British Pound</option>
                                    <option value="EUR" {{ old('currency', $user->currency) == 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                                </select>
                                @error('currency')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="wallet_provider" class="block text-sm font-medium text-gray-700">Wallet Provider</label>
                                    <select id="wallet_provider" name="wallet_provider"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                        <option value="Decircles" {{ old('wallet_provider', $user->wallet_provider) == 'Decircles' ? 'selected' : '' }}>Decircles</option>
                                        <option value="M-Pesa" {{ old('wallet_provider', $user->wallet_provider) == 'M-Pesa' ? 'selected' : '' }}>M-Pesa</option>
                                        <option value="PayPal" {{ old('wallet_provider', $user->wallet_provider) == 'PayPal' ? 'selected' : '' }}>PayPal</option>
                                        <option value="Stripe" {{ old('wallet_provider', $user->wallet_provider) == 'Stripe' ? 'selected' : '' }}>Stripe</option>
                                    </select>
                                    @error('wallet_provider')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="preferred_payment_method" class="block text-sm font-medium text-gray-700">Preferred Payment</label>
                                    <select id="preferred_payment_method" name="preferred_payment_method"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                        <option value="wallet" {{ old('preferred_payment_method', $user->preferred_payment_method) == 'wallet' ? 'selected' : '' }}>Wallet</option>
                                        <option value="bank_transfer" {{ old('preferred_payment_method', $user->preferred_payment_method) == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                        <option value="credit_card" {{ old('preferred_payment_method', $user->preferred_payment_method) == 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                                        <option value="m_pesa" {{ old('preferred_payment_method', $user->preferred_payment_method) == 'm_pesa' ? 'selected' : '' }}>M-Pesa</option>
                                    </select>
                                    @error('preferred_payment_method')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label for="linked_bank_account" class="block text-sm font-medium text-gray-700">Linked Bank Account</label>
                                <input type="text" name="linked_bank_account" id="linked_bank_account" value="{{ old('linked_bank_account', $user->linked_bank_account) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                @error('linked_bank_account')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end">
                        <button type="submit" class="flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Save Account Settings
                        </button>
                    </div>
                </form>
            </div>

            <!-- Delete Account Section -->
            <div class="bg-white shadow-sm rounded-xl overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100">
                    <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        Delete Account
                    </h2>
                    <p class="mt-1 text-sm text-gray-500">
                        Permanently delete your account and all associated data
                    </p>
                </div>

                <div class="px-6 py-5">
                    <div class="bg-red-50 rounded-lg p-4 border border-red-100">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-700">
                                    Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-5">
                        <button type="button" onclick="document.getElementById('delete-account-modal').classList.remove('hidden')"
                            class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Delete Account
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

   <!-- Delete Account Modal -->
<div id="delete-account-modal" class="hidden fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <!-- Modal Header -->
            <div class="bg-red-50 px-4 py-5 sm:px-6 border-b border-red-100">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 flex items-center" id="modal-title">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        Delete Account
                    </h3>
                    <button type="button" onclick="document.getElementById('delete-account-modal').classList.add('hidden')" class="rounded-md text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <span class="sr-only">Close</span>
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Modal Content -->
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Confirm Account Deletion
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                Are you sure you want to delete your account? All of your data will be permanently removed from our servers. This action <span class="font-semibold text-red-600">cannot be undone</span>.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mt-5">
                    <form id="delete-account-form" action="{{ route('profile.destroy') }}" method="POST" class="space-y-4">
                        @csrf
                        @method('DELETE')

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">
                                Confirm your password
                            </label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <input type="password" name="password" id="password" required
                                    class="block w-full pr-10 border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500 sm:text-sm"
                                    placeholder="Enter your password to confirm">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">For security reasons, please enter your password to confirm account deletion.</p>
                        </div>

                        <div class="flex items-center mt-3">
                            <input id="confirm-delete" name="confirm-delete" type="checkbox" required
                                class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                            <label for="confirm-delete" class="ml-2 block text-sm text-gray-900">
                                I understand that this action is irreversible
                            </label>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" onclick="document.getElementById('delete-account-form').submit();"
                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                    Delete Account
                </button>
                <button type="button" onclick="document.getElementById('delete-account-modal').classList.add('hidden')"
                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Image Preview Script -->
<script>
    function previewImage(input) {
        const preview = document.getElementById('preview-image');
        const previewPanel = document.getElementById('preview-panel');
        const filenameDisplay = document.getElementById('selected-filename');
        const filenameSpan = document.getElementById('filename');

        if (input.files && input.files[0]) {
            const file = input.files[0];

            // Check file size (max 1MB)
            if (file.size > 1024 * 1024) {
                alert('File is too large. Maximum size is 1MB.');
                input.value = '';
                return;
            }

            const reader = new FileReader();

            reader.onload = function (e) {
                // Show preview panel
                preview.src = e.target.result;
                previewPanel.classList.remove('hidden');

                // Show filename
                filenameSpan.textContent = file.name;
                filenameDisplay.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
    }

    function cancelSelection() {
        // Clear the file input
        const input = document.getElementById('profile_photo');
        input.value = '';

        // Hide preview panel
        document.getElementById('preview-panel').classList.add('hidden');

        // Hide filename
        document.getElementById('selected-filename').classList.add('hidden');
    }
</script>
@endsection