{{-- resources/views/livewire/pages/profile/edit.blade.php --}}
@extends('layouts.dashboard')

@section('title', 'Edit Profile - PangoQ')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-gray-100">
    <!-- Header Section -->
    <div class="bg-white border-b border-gray-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('profile.show') }}" class="flex items-center text-gray-600 hover:text-gray-900 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Back to Profile
                    </a>
                    <div class="h-6 w-px bg-gray-300"></div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Profile Settings</h1>
                        <p class="text-sm text-gray-600">Manage your account information and preferences</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 8 8">
                            <circle cx="4" cy="4" r="3"/>
                        </svg>
                        Account Active
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Success/Error Messages -->
        @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-xl p-4">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-green-800">{{ session('success') }}</p>
            </div>
        </div>
        @endif

        <!-- Navigation Tabs -->
        <div class="mb-8">
            <nav class="flex space-x-1 bg-gray-100 rounded-xl p-1">
                <button onclick="switchTab('personal')" id="personal-tab" 
                        class="tab-button flex-1 flex items-center justify-center px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200 bg-white text-gray-900 shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Personal Information
                </button>
                <button onclick="switchTab('account')" id="account-tab" 
                        class="tab-button flex-1 flex items-center justify-center px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200 text-gray-600 hover:text-gray-900">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                    Account Settings
                </button>
                <button onclick="switchTab('security')" id="security-tab" 
                        class="tab-button flex-1 flex items-center justify-center px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200 text-gray-600 hover:text-gray-900">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5-6a9 9 0 11-10 9"/>
                    </svg>
                    Security & Privacy
                </button>
            </nav>
        </div>

        <!-- Personal Information Tab -->
        <div id="personal-content" class="tab-content">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <!-- Header -->
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-8 py-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-xl font-semibold text-gray-900">Personal Information</h2>
                            <p class="text-sm text-gray-600 mt-1">Update your profile details and personal information</p>
                        </div>
                        <div class="flex items-center space-x-2 text-sm text-gray-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            <span>Private & Secure</span>
                        </div>
                    </div>
                </div>

                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="p-8">
                    @csrf
                    @method('PATCH')

                    <!-- Profile Photo Section -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Profile Photo</h3>
                        <div class="flex items-start space-x-6">
                            <div class="relative">
                                <div id="profile-photo-preview" class="w-32 h-32 rounded-2xl overflow-hidden border-4 border-white shadow-lg bg-gradient-to-br from-gray-100 to-gray-200">
                                    @if ($user->profile_photo_path)
                                        <img src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="Profile Photo" class="w-full h-full object-cover" id="current-photo">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-500 to-purple-600 text-white text-2xl font-bold" id="initials-fallback">
                                            {{ $user->initials }}
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Status indicator -->
                                <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-green-500 rounded-full border-4 border-white flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </div>

                            <div class="flex-1">
                                <div class="mb-4">
                                    <div class="flex items-center space-x-3">
                                        <label for="profile_photo" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors cursor-pointer">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            Upload New Photo
                                        </label>
                                        <button type="button" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                                            Remove
                                        </button>
                                    </div>
                                    <input type="file" name="profile_photo" id="profile_photo" class="hidden" accept="image/*" onchange="previewImage(this)">
                                </div>
                                
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <h4 class="font-medium text-gray-900 mb-2">Photo Guidelines</h4>
                                    <ul class="text-sm text-gray-600 space-y-1">
                                        <li class="flex items-center"><svg class="w-4 h-4 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Use a clear, professional headshot</li>
                                        <li class="flex items-center"><svg class="w-4 h-4 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>JPG, PNG or GIF format, Max 2MB</li>
                                        <li class="flex items-center"><svg class="w-4 h-4 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Square aspect ratio recommended</li>
                                    </ul>
                                </div>

                                @error('profile_photo')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Preview Panel -->
                        <div id="preview-panel" class="hidden mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                            <div class="flex items-center space-x-4">
                                <div class="w-16 h-16 rounded-lg overflow-hidden">
                                    <img id="preview-image" src="#" alt="Preview" class="w-full h-full object-cover">
                                </div>
                                <div class="flex-1">
                                    <p class="font-medium text-blue-900">New photo selected</p>
                                    <p class="text-sm text-blue-700">This will be your new profile photo</p>
                                </div>
                                <button type="button" onclick="cancelSelection()" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    Cancel
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Form Fields -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Left Column -->
                        <div class="space-y-6">
                            <div>
                                <label for="name" class="block text-sm font-semibold text-gray-900 mb-2">Full Name *</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-semibold text-gray-900 mb-2">Email Address *</label>
                                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="phone_number" class="block text-sm font-semibold text-gray-900 mb-2">Phone Number</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                        </svg>
                                    </div>
                                    <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number', $user->phone_number) }}"
                                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                        placeholder="+1 (555) 123-4567">
                                </div>
                                @error('phone_number')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="address" class="block text-sm font-semibold text-gray-900 mb-2">Address</label>
                                <textarea name="address" id="address" rows="3"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                    placeholder="Enter your full address">{{ old('address', $user->address) }}</textarea>
                                @error('address')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="space-y-6">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="date_of_birth" class="block text-sm font-semibold text-gray-900 mb-2">Date of Birth</label>
                                    <input type="date" name="date_of_birth" id="date_of_birth" 
                                        value="{{ old('date_of_birth', $user->date_of_birth ? $user->date_of_birth->format('Y-m-d') : '') }}"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    @error('date_of_birth')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="gender" class="block text-sm font-semibold text-gray-900 mb-2">Gender</label>
                                    <select id="gender" name="gender"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                        <option value="">Select gender</option>
                                        <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                        <option value="other" {{ old('gender', $user->gender) == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('gender')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label for="nationality" class="block text-sm font-semibold text-gray-900 mb-2">Nationality</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-lg">🌍</span>
                                    </div>
                                    <input type="text" name="nationality" id="nationality" value="{{ old('nationality', $user->nationality) }}"
                                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                        placeholder="e.g., Australian">
                                </div>
                                @error('nationality')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="id_card_number" class="block text-sm font-semibold text-gray-900 mb-2">ID Card Number</label>
                                    <input type="text" name="id_card_number" id="id_card_number" value="{{ old('id_card_number', $user->id_card_number) }}"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    @error('id_card_number')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="passport_number" class="block text-sm font-semibold text-gray-900 mb-2">Passport Number</label>
                                    <input type="text" name="passport_number" id="passport_number" value="{{ old('passport_number', $user->passport_number) }}"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    @error('passport_number')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Save Button -->
                    <div class="mt-8 flex justify-between items-center">
                        <p class="text-sm text-gray-500">* Required fields</p>
                        <button type="submit" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-500 focus:ring-opacity-50 transition-all duration-200 shadow-lg hover:shadow-xl">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Account Settings Tab -->
        <div id="account-content" class="tab-content hidden">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <!-- Header -->
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-8 py-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-xl font-semibold text-gray-900">Account Settings</h2>
                            <p class="text-sm text-gray-600 mt-1">Manage your wallet, payment preferences, and account details</p>
                        </div>
                        <div class="flex items-center space-x-2 text-sm text-gray-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5-6a9 9 0 11-10 9"/>
                            </svg>
                            <span>Secure & Encrypted</span>
                        </div>
                    </div>
                </div>

                <form action="{{ route('profile.account.update') }}" method="POST" class="p-8">
                    @csrf
                    @method('PATCH')

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Left Column -->
                        <div class="space-y-6">
                            <div>
                                <label for="account_number" class="block text-sm font-semibold text-gray-900 mb-2">Account ID</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                        </svg>
                                    </div>
                                    <input type="text" id="account_number" value="{{ $user->account_number ?? '#PQ-' . str_pad($user->id, 6, '0', STR_PAD_LEFT) }}" disabled
                                        class="w-full pl-10 pr-4 py-3 bg-gray-100 border border-gray-300 rounded-lg text-gray-500 cursor-not-allowed">
                                </div>
                                <p class="mt-1 text-xs text-gray-500">This ID is automatically generated and cannot be changed</p>
                            </div>

                            <div>
                                <label for="account_type" class="block text-sm font-semibold text-gray-900 mb-2">Account Type</label>
                                <select id="account_type" name="account_type"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
                                    <option value="personal" {{ old('account_type', $user->account_type) == 'personal' ? 'selected' : '' }}>Personal Account</option>
                                    <option value="business" {{ old('account_type', $user->account_type) == 'business' ? 'selected' : '' }}>Business Account</option>
                                    <option value="premium" {{ old('account_type', $user->account_type) == 'premium' ? 'selected' : '' }}>Premium Account</option>
                                </select>
                                @error('account_type')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="currency" class="block text-sm font-semibold text-gray-900 mb-2">Preferred Currency</label>
                                <select id="currency" name="currency"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
                                    <option value="USD" {{ old('currency', $user->currency) == 'USD' ? 'selected' : '' }}>🇺🇸 USD - United States Dollar</option>
                                    <option value="AUD" {{ old('currency', $user->currency) == 'AUD' ? 'selected' : '' }}>🇦🇺 AUD - Australian Dollar</option>
                                    <option value="KES" {{ old('currency', $user->currency) == 'KES' ? 'selected' : '' }}>🇰🇪 KES - Kenyan Shilling</option>
                                    <option value="GBP" {{ old('currency', $user->currency) == 'GBP' ? 'selected' : '' }}>🇬🇧 GBP - British Pound</option>
                                    <option value="EUR" {{ old('currency', $user->currency) == 'EUR' ? 'selected' : '' }}>🇪🇺 EUR - Euro</option>
                                </select>
                                @error('currency')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="space-y-6">
                            <div>
                                <label for="wallet_provider" class="block text-sm font-semibold text-gray-900 mb-2">Wallet Provider</label>
                                <select id="wallet_provider" name="wallet_provider"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
                                    <option value="pangoq" {{ old('wallet_provider', $user->wallet_provider) == 'pangoq' ? 'selected' : '' }}>💳 PangoQ Wallet</option>
                                    <option value="mpesa" {{ old('wallet_provider', $user->wallet_provider) == 'mpesa' ? 'selected' : '' }}>📱 M-Pesa</option>
                                    <option value="paypal" {{ old('wallet_provider', $user->wallet_provider) == 'paypal' ? 'selected' : '' }}>🏦 PayPal</option>
                                    <option value="stripe" {{ old('wallet_provider', $user->wallet_provider) == 'stripe' ? 'selected' : '' }}>💎 Stripe</option>
                                    <option value="wise" {{ old('wallet_provider', $user->wallet_provider) == 'wise' ? 'selected' : '' }}>🌍 Wise</option>
                                </select>
                                @error('wallet_provider')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="preferred_payment_method" class="block text-sm font-semibold text-gray-900 mb-2">Preferred Payment Method</label>
                                <select id="preferred_payment_method" name="preferred_payment_method"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
                                    <option value="wallet" {{ old('preferred_payment_method', $user->preferred_payment_method) == 'wallet' ? 'selected' : '' }}>💰 Digital Wallet</option>
                                    <option value="bank_transfer" {{ old('preferred_payment_method', $user->preferred_payment_method) == 'bank_transfer' ? 'selected' : '' }}>🏦 Bank Transfer</option>
                                    <option value="credit_card" {{ old('preferred_payment_method', $user->preferred_payment_method) == 'credit_card' ? 'selected' : '' }}>💳 Credit Card</option>
                                    <option value="debit_card" {{ old('preferred_payment_method', $user->preferred_payment_method) == 'debit_card' ? 'selected' : '' }}>💳 Debit Card</option>
                                </select>
                                @error('preferred_payment_method')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="linked_bank_account" class="block text-sm font-semibold text-gray-900 mb-2">Linked Bank Account</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                    </div>
                                    <input type="text" name="linked_bank_account" id="linked_bank_account" 
                                        value="{{ old('linked_bank_account', $user->linked_bank_account) }}"
                                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                                        placeholder="Account number or IBAN">
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Enter your bank account details for direct transfers</p>
                                @error('linked_bank_account')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Account Status Information -->
                    <div class="mt-8 p-6 bg-gradient-to-r from-gray-50 to-slate-50 rounded-xl border border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Account Status</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="text-center">
                                <div class="inline-flex items-center justify-center w-12 h-12 bg-green-100 rounded-full mb-3">
                                    <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <p class="font-semibold text-gray-900">Verified</p>
                                <p class="text-sm text-gray-600">Identity confirmed</p>
                            </div>
                            <div class="text-center">
                                <div class="inline-flex items-center justify-center w-12 h-12 bg-blue-100 rounded-full mb-3">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5-6a9 9 0 11-10 9"/>
                                    </svg>
                                </div>
                                <p class="font-semibold text-gray-900">{{ ucfirst($user->account_status ?? 'Active') }}</p>
                                <p class="text-sm text-gray-600">Account status</p>
                            </div>
                            <div class="text-center">
                                <div class="inline-flex items-center justify-center w-12 h-12 bg-purple-100 rounded-full mb-3">
                                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                </div>
                                <p class="font-semibold text-gray-900">Premium</p>
                                <p class="text-sm text-gray-600">Account tier</p>
                            </div>
                        </div>
                    </div>

                    <!-- Save Button -->
                    <div class="mt-8 flex justify-end">
                        <button type="submit" class="inline-flex items-center px-6 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 focus:ring-4 focus:ring-green-500 focus:ring-opacity-50 transition-all duration-200 shadow-lg hover:shadow-xl">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Update Account Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Security & Privacy Tab -->
        <div id="security-content" class="tab-content hidden">
            <div class="space-y-6">
                <!-- Password Section -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-red-50 to-pink-50 px-8 py-6 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-xl font-semibold text-gray-900">Password & Security</h2>
                                <p class="text-sm text-gray-600 mt-1">Manage your password and security preferences</p>
                            </div>
                            <div class="flex items-center space-x-2 text-sm text-green-600">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span>Strong Security</span>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('profile.password.update') }}" method="POST" class="p-8">
                        @csrf
                        @method('PATCH')

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <div class="space-y-6">
                                <div>
                                    <label for="current_password" class="block text-sm font-semibold text-gray-900 mb-2">Current Password *</label>
                                    <div class="relative">
                                        <input type="password" name="current_password" id="current_password" required
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors pr-10"
                                            placeholder="Enter current password">
                                        <button type="button" onclick="togglePasswordVisibility('current_password')" 
                                                class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                            <svg class="w-5 h-5 text-gray-400 hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </button>
                                    </div>
                                    @error('current_password')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="new_password" class="block text-sm font-semibold text-gray-900 mb-2">New Password *</label>
                                    <div class="relative">
                                        <input type="password" name="new_password" id="new_password" required
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors pr-10"
                                            placeholder="Enter new password">
                                        <button type="button" onclick="togglePasswordVisibility('new_password')" 
                                                class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                            <svg class="w-5 h-5 text-gray-400 hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </button>
                                    </div>
                                    @error('new_password')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="new_password_confirmation" class="block text-sm font-semibold text-gray-900 mb-2">Confirm New Password *</label>
                                    <div class="relative">
                                        <input type="password" name="new_password_confirmation" id="new_password_confirmation" required
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors pr-10"
                                            placeholder="Confirm new password">
                                        <button type="button" onclick="togglePasswordVisibility('new_password_confirmation')" 
                                                class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                            <svg class="w-5 h-5 text-gray-400 hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gray-50 rounded-xl p-6">
                                <h3 class="font-semibold text-gray-900 mb-4">Password Requirements</h3>
                                <ul class="space-y-2 text-sm">
                                    <li class="flex items-center text-gray-600">
                                        <svg class="w-4 h-4 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        At least 8 characters long
                                    </li>
                                    <li class="flex items-center text-gray-600">
                                        <svg class="w-4 h-4 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        Contains uppercase and lowercase letters
                                    </li>
                                    <li class="flex items-center text-gray-600">
                                        <svg class="w-4 h-4 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        Contains at least one number
                                    </li>
                                    <li class="flex items-center text-gray-600">
                                        <svg class="w-4 h-4 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        Contains special characters (!@#$%^&*)
                                    </li>
                                </ul>

                                <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                                    <h4 class="font-medium text-blue-900 mb-2">Security Tip</h4>
                                    <p class="text-sm text-blue-800">Use a unique password that you don't use for other accounts. Consider using a password manager for better security.</p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end">
                            <button type="submit" class="inline-flex items-center px-6 py-3 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 focus:ring-4 focus:ring-red-500 focus:ring-opacity-50 transition-all duration-200 shadow-lg hover:shadow-xl">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                Update Password
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Two-Factor Authentication -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-purple-50 to-indigo-50 px-8 py-6 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-xl font-semibold text-gray-900">Two-Factor Authentication</h2>
                                <p class="text-sm text-gray-600 mt-1">Add an extra layer of security to your account</p>
                            </div>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                Recommended
                            </span>
                        </div>
                    </div>

                    <div class="p-8">
                        <div class="flex items-start space-x-6">
                            <div class="flex-shrink-0">
                                <div class="w-16 h-16 bg-purple-100 rounded-2xl flex items-center justify-center">
                                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">SMS Authentication</h3>
                                <p class="text-gray-600 mb-4">Receive verification codes via SMS to your registered phone number.</p>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-500">Currently: <span class="font-medium">Disabled</span></span>
                                    <button type="button" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                                        Enable 2FA
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Account Deletion -->
                <div class="bg-white rounded-2xl shadow-sm border border-red-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-red-50 to-pink-50 px-8 py-6 border-b border-red-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-xl font-semibold text-gray-900">Danger Zone</h2>
                                <p class="text-sm text-gray-600 mt-1">Irreversible and destructive actions</p>
                            </div>
                            <div class="flex items-center space-x-2 text-sm text-red-600">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                <span>Proceed with caution</span>
                            </div>
                        </div>
                    </div>

                    <div class="p-8">
                        <div class="bg-red-50 border border-red-200 rounded-xl p-6">
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0">
                                    <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-red-900 mb-2">Delete Account</h3>
                                    <p class="text-red-800 mb-4">Once you delete your account, there is no going back. All your data, trips, and savings will be permanently removed from our servers.</p>
                                    
                                    <div class="space-y-3 text-sm text-red-700 mb-6">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                            </svg>
                                            All trip data and savings will be lost
                                        </div>
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                            </svg>
                                            Your profile and settings will be removed
                                        </div>
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                            </svg>
                                            This action cannot be undone
                                        </div>
                                    </div>

                                   <button type="button" onclick="openDeleteModal()" 
                                            class="inline-flex items-center px-6 py-3 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 focus:ring-4 focus:ring-red-500 focus:ring-opacity-50 transition-all duration-200">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        Delete My Account
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Account Modal -->
<div id="delete-modal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeDeleteModal()"></div>
        
        <!-- Modal positioning -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
        
        <!-- Modal content -->
        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <!-- Modal header -->
            <div class="bg-red-50 px-6 py-4 border-b border-red-100">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <h3 class="ml-3 text-lg font-semibold text-gray-900">Delete Account</h3>
                    </div>
                    <button type="button" onclick="closeDeleteModal()" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Modal body -->
            <div class="px-6 py-6">
                <div class="mb-6">
                    <p class="text-gray-700 mb-4">
                        This action will permanently delete your PangoQ account and cannot be undone. All your data will be immediately removed from our servers.
                    </p>
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                        <div class="flex">
                            <svg class="w-5 h-5 text-yellow-600 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <div class="text-sm text-yellow-800">
                                <p class="font-medium mb-1">Before you proceed:</p>
                                <ul class="list-disc list-inside space-y-1">
                                    <li>Download any important data you want to keep</li>
                                    <li>Cancel any active trips or bookings</li>
                                    <li>Withdraw any remaining wallet balance</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <form action="{{ route('profile.destroy') }}" method="POST" id="delete-form">
                    @csrf
                    @method('DELETE')
                    
                    <div class="space-y-4">
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                Enter your password to confirm
                            </label>
                            <input type="password" name="password" id="password_confirmation" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                placeholder="Enter your password">
                        </div>
                        
                        <div>
                            <label for="delete_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                Type "DELETE" to confirm
                            </label>
                            <input type="text" id="delete_confirmation" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                placeholder="Type DELETE here"
                                oninput="validateDeleteInput()">
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" id="final_confirmation" required
                                class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                            <label for="final_confirmation" class="ml-2 text-sm text-gray-700">
                                I understand this action is permanent and irreversible
                            </label>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Modal footer -->
            <div class="bg-gray-50 px-6 py-4 flex flex-col-reverse sm:flex-row sm:justify-end sm:space-x-3 space-y-3 space-y-reverse sm:space-y-0">
                <button type="button" onclick="closeDeleteModal()"
                    class="w-full sm:w-auto inline-flex justify-center px-4 py-2 border border-gray-300 text-gray-700 bg-white rounded-lg hover:bg-gray-50 focus:ring-2 focus:ring-gray-500 transition-colors">
                    Cancel
                </button>
                <button type="button" onclick="submitDeleteForm()" id="delete-submit-btn" disabled
                    class="w-full sm:w-auto inline-flex justify-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 focus:ring-2 focus:ring-red-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                    Delete Account Permanently
                </button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
// Tab switching functionality
function switchTab(tabName) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    // Remove active state from all tabs
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('bg-white', 'text-gray-900', 'shadow-sm');
        button.classList.add('text-gray-600', 'hover:text-gray-900');
    });
    
    // Show selected tab content
    document.getElementById(tabName + '-content').classList.remove('hidden');
    
    // Add active state to selected tab
    const activeTab = document.getElementById(tabName + '-tab');
    activeTab.classList.add('bg-white', 'text-gray-900', 'shadow-sm');
    activeTab.classList.remove('text-gray-600', 'hover:text-gray-900');
}

// Image preview functionality
function previewImage(input) {
    const preview = document.getElementById('preview-image');
    const previewPanel = document.getElementById('preview-panel');
    const currentPhoto = document.getElementById('current-photo');
    const initialsDiv = document.getElementById('initials-fallback');

    if (input.files && input.files[0]) {
        const file = input.files[0];
        
        // Validate file size (2MB max)
        if (file.size > 2 * 1024 * 1024) {
            alert('File size must be less than 2MB');
            input.value = '';
            return;
        }
        
        // Validate file type
        if (!file.type.match('image.*')) {
            alert('Please select a valid image file');
            input.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            previewPanel.classList.remove('hidden');
            
            // Update main preview
            if (currentPhoto) {
                currentPhoto.src = e.target.result;
            } else if (initialsDiv) {
                initialsDiv.style.display = 'none';
                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'w-full h-full object-cover';
                img.id = 'current-photo';
                document.getElementById('profile-photo-preview').appendChild(img);
            }
        };
        reader.readAsDataURL(file);
    }
}

function cancelSelection() {
    const input = document.getElementById('profile_photo');
    const previewPanel = document.getElementById('preview-panel');
    const currentPhoto = document.getElementById('current-photo');
    const initialsDiv = document.getElementById('initials-fallback');
    
    input.value = '';
    previewPanel.classList.add('hidden');
    
    // Reset to original state
    if (currentPhoto) {
        @if($user->profile_photo_path)
            currentPhoto.src = "{{ asset('storage/' . $user->profile_photo_path) }}";
        @else
            currentPhoto.remove();
            if (initialsDiv) {
                initialsDiv.style.display = 'flex';
            }
        @endif
    }
}

// Password visibility toggle
function togglePasswordVisibility(fieldId) {
    const field = document.getElementById(fieldId);
    const button = field.nextElementSibling;
    const icon = button.querySelector('svg');
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"/>
        `;
    } else {
        field.type = 'password';
        icon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
        `;
    }
}

// Delete modal functionality
function openDeleteModal() {
    document.getElementById('delete-modal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeDeleteModal() {
    document.getElementById('delete-modal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    
    // Reset form
    document.getElementById('delete-form').reset();
    document.getElementById('delete-submit-btn').disabled = true;
}

function validateDeleteInput() {
    const deleteConfirmation = document.getElementById('delete_confirmation').value;
    const passwordField = document.getElementById('password_confirmation').value;
    const checkbox = document.getElementById('final_confirmation').checked;
    const submitBtn = document.getElementById('delete-submit-btn');
    
    if (deleteConfirmation === 'DELETE' && passwordField && checkbox) {
        submitBtn.disabled = false;
        submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
    } else {
        submitBtn.disabled = true;
        submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
    }
}

function submitDeleteForm() {
    const form = document.getElementById('delete-form');
    const deleteConfirmation = document.getElementById('delete_confirmation').value;
    
    if (deleteConfirmation === 'DELETE') {
        if (confirm('This is your final warning. Your account will be permanently deleted. Are you absolutely sure?')) {
            form.submit();
        }
    } else {
        alert('Please type "DELETE" exactly to confirm account deletion.');
    }
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Add validation listeners
    document.getElementById('delete_confirmation').addEventListener('input', validateDeleteInput);
    document.getElementById('password_confirmation').addEventListener('input', validateDeleteInput);
    document.getElementById('final_confirmation').addEventListener('change', validateDeleteInput);
    
    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !document.getElementById('delete-modal').classList.contains('hidden')) {
            closeDeleteModal();
        }
    });
    
    // Form validation and enhancement
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = `
                    <svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Saving...
                `;
                
                // Re-enable after 5 seconds as fallback
                setTimeout(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = submitBtn.getAttribute('data-original-text') || 'Save Changes';
                }, 5000);
            }
        });
    });
    
    // Enhanced form interactions
    const inputs = document.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        // Add focus effects
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('ring-2', 'ring-blue-500', 'ring-opacity-50');
        });
        
        input.addEventListener('blur', function() {
            this.parentElement.classList.remove('ring-2', 'ring-blue-500', 'ring-opacity-50');
        });
        
        // Add change indicators
        input.addEventListener('change', function() {
            if (this.value !== this.defaultValue) {
                this.classList.add('border-blue-500');
            } else {
                this.classList.remove('border-blue-500');
            }
        });
    });
    
    // Smooth tab transitions
    const tabButtons = document.querySelectorAll('.tab-button');
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const tabName = this.id.replace('-tab', '');
            switchTab(tabName);
        });
    });
    
    // Auto-save draft functionality (for demonstration)
    let saveTimeout;
    const formInputs = document.querySelectorAll('input[type="text"], input[type="email"], textarea, select');
    formInputs.forEach(input => {
        input.addEventListener('input', function() {
            clearTimeout(saveTimeout);
            saveTimeout = setTimeout(() => {
                // Here you could implement auto-save functionality
                console.log('Auto-saving draft...');
            }, 2000);
        });
    });
});

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Set initial active tab
    switchTab('personal');
});
</script>

<!-- Custom styles for enhanced effects -->
<style>
    /* Custom transitions */
    .tab-content {
        transition: opacity 0.3s ease-in-out;
    }
    
    /* Enhanced focus states */
    input:focus, select:focus, textarea:focus {
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    /* Custom scrollbar for modal */
    .overflow-y-auto::-webkit-scrollbar {
        width: 6px;
    }
    
    .overflow-y-auto::-webkit-scrollbar-track {
        background: #f1f5f9;
    }
    
    .overflow-y-auto::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 3px;
    }
    
    .overflow-y-auto::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
    
    /* Loading animation */
    @keyframes spin {
        from {
            transform: rotate(0deg);
        }
        to {
            transform: rotate(360deg);
        }
    }
    
    .animate-spin {
        animation: spin 1s linear infinite;
    }
    
    /* Smooth hover transitions */
    button, a, input, select, textarea {
        transition: all 0.2s ease-in-out;
    }
    
    /* Enhanced modal backdrop */
    .modal-backdrop {
        backdrop-filter: blur(4px);
    }
</style>

@endsection