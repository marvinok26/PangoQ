{{-- resources/views/livewire/pages/profile/show.blade.php --}}
@extends('layouts.dashboard')

@section('title', 'Profile - PangoQ')

@section('content')
    <div class="relative">
        <!-- Enhanced Liquid Morphing SVG Banner Section -->
        <div class="absolute top-0 left-0 w-full h-50 overflow-hidden z-0">
            <svg class="w-full h-full" viewBox="0 0 1200 200" preserveAspectRatio="xMidYMid slice">
                <defs>
                    <linearGradient id="liquidGradient1" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" style="stop-color:#667eea;stop-opacity:1" />
                        <stop offset="33%" style="stop-color:#764ba2;stop-opacity:1" />
                        <stop offset="66%" style="stop-color:#fbce93;stop-opacity:1" />
                        <stop offset="100%" style="stop-color:#f5576c;stop-opacity:1" />
                    </linearGradient>
                    <radialGradient id="liquidGradient2" cx="30%" cy="30%" r="70%">
                        <stop offset="0%" style="stop-color:#4facfe;stop-opacity:0.8" />
                        <stop offset="100%" style="stop-color:#00f2fe;stop-opacity:0.3" />
                    </radialGradient>
                    <filter id="liquidGlow">
                        <feGaussianBlur stdDeviation="4" result="coloredBlur"/>
                        <feMerge> 
                            <feMergeNode in="coloredBlur"/>
                            <feMergeNode in="SourceGraphic"/>
                        </feMerge>
                    </filter>
                </defs>
                <rect width="100%" height="100%" fill="url(#liquidGradient1)"/>
                <ellipse cx="200" cy="100" rx="150" ry="80" fill="url(#liquidGradient2)" filter="url(#liquidGlow)">
                    <animateTransform attributeName="transform" type="rotate" values="0 200 100;360 200 100" dur="20s" repeatCount="indefinite"/>
                </ellipse>
                <ellipse cx="600" cy="50" rx="120" ry="60" fill="url(#liquidGradient2)" opacity="0.6" filter="url(#liquidGlow)">
                    <animateTransform attributeName="transform" type="rotate" values="360 600 50;0 600 50" dur="15s" repeatCount="indefinite"/>
                </ellipse>
                <ellipse cx="1000" cy="120" rx="100" ry="70" fill="url(#liquidGradient2)" opacity="0.4" filter="url(#liquidGlow)">
                    <animateTransform attributeName="transform" type="rotate" values="0 1000 120;360 1000 120" dur="25s" repeatCount="indefinite"/>
                </ellipse>
            </svg>
        </div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <h2 class="text-xl text-white font-semibold drop-shadow-lg">Profile</h2>
        </div>

        <!-- Profile content that overlays the blue section -->
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-12">
            <!-- Profile section with picture -->
            <div class="flex items-center mb-8">
                <div class="relative">
                    <div class="w-24 h-24 rounded-full overflow-hidden border-4 border-white">
                        @if($user->profile_photo_path)
                            <img src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="{{ $user->name }}"
                                class="w-full h-full object-cover">
                        @else
                            <div
                                class="w-full h-full bg-blue-100 flex items-center justify-center text-blue-700 text-2xl font-semibold">
                                {{ $user->initials }}
                            </div>
                        @endif
                    </div>
                    <a href="{{ route('profile.edit') }}"
                        class="absolute bottom-0 right-0 bg-white rounded-full p-1 shadow hover:bg-gray-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M5 12h14"></path>
                            <path d="M12 5v14"></path>
                        </svg>
                    </a>
                </div>
                <div class="ml-4">
                    <h1 class="text-2xl font-semibold text-gray-800">{{ $user->name }}</h1>
                    <p class="text-gray-600">{{ $user->email }}</p>
                </div>
                <div class="ml-auto bg-gray-100 rounded-full px-3 py-1 flex items-center">
                    <span class="mr-1">🇦🇺</span>
                    <span>{{ $user->nationality ?? 'Australia' }}</span>
                </div>
            </div>

            <!-- Two cards in a row -->
            <div class="flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-4 mb-8">
                <!-- Personal Information Card -->
                <div class="flex-1 bg-white rounded-lg shadow p-4">
                    <div class="flex justify-between items-center mb-4">
                        <div>
                            <h3 class="text-lg font-medium">Personal Information</h3>
                            <p class="text-xs text-gray-500">Your personal details for identification and verification.</p>
                        </div>
                        <a href="{{ route('profile.edit') }}" class="bg-blue-500 text-white px-3 py-1 rounded text-sm">
                            Edit
                        </a>
                    </div>

                    <div class="border-t border-gray-100 pt-4">
                        <div class="grid grid-cols-2">
                            <div class="py-2">
                                <p class="text-xs text-gray-500">Full Name</p>
                                <p>{{ $user->name }}</p>
                            </div>
                            <div class="py-2">
                                <p class="text-xs text-gray-500">Identity Card / Passport Number</p>
                                <p>{{ $user->id_card_number ?? $user->passport_number ?? '9876543210' }}</p>
                            </div>
                        </div>

                        <div class="border-t border-gray-100"></div>

                        <div class="grid grid-cols-2">
                            <div class="py-2">
                                <p class="text-xs text-gray-500">Date of Birth</p>
                                <p>{{ $user->date_of_birth ? $user->date_of_birth->format('F j, Y') : 'January 15, 1990' }}
                                </p>
                            </div>
                            <div class="py-2">
                                <p class="text-xs text-gray-500">Gender</p>
                                <p>{{ ucfirst($user->gender ?? 'Male') }}</p>
                            </div>
                        </div>

                        <div class="border-t border-gray-100"></div>

                        <div class="grid grid-cols-2">
                            <div class="py-2">
                                <p class="text-xs text-gray-500">Nationality</p>
                                <p>{{ $user->nationality ?? 'Australian' }}</p>
                            </div>
                            <div class="py-2">
                                <p class="text-xs text-gray-500">Phone Number</p>
                                <p>{{ $user->phone_number ?? '+61412345678' }}</p>
                            </div>
                        </div>

                        <div class="border-t border-gray-100"></div>

                        <div class="grid grid-cols-2">
                            <div class="py-2">
                                <p class="text-xs text-gray-500">Address</p>
                                <p>{{ $user->address ?? '123 George St, Sydney, Australia' }}</p>
                            </div>
                            <div class="py-2">
                                <p class="text-xs text-gray-500">Email</p>
                                <p>{{ $user->email }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Account Information Card -->
                <div class="flex-1 bg-white rounded-lg shadow p-4">
                    <div class="flex justify-between items-center mb-4">
                        <div>
                            <h3 class="text-lg font-medium">Account Information</h3>
                            <p class="text-xs text-gray-500">Your account and wallet details for financial transactions.</p>
                        </div>
                        <a href="{{ route('profile.edit') }}" class="bg-blue-500 text-white px-3 py-1 rounded text-sm">
                            Edit
                        </a>
                    </div>

                    <div class="border-t border-gray-100 pt-4">
                        <div class="grid grid-cols-2">
                            <div class="py-2">
                                <p class="text-xs text-gray-500">Account Number</p>
                                <p>{{ $user->account_number ?? 'Not generated yet' }}</p>
                            </div>
                            <div class="py-2">
                                <p class="text-xs text-gray-500">Account Type</p>
                                <p>{{ ucfirst($user->account_type ?? 'Personal') }}</p>
                            </div>
                        </div>

                        <div class="border-t border-gray-100"></div>

                        <div class="grid grid-cols-2">
                            <div class="py-2">
                                <p class="text-xs text-gray-500">Currency</p>
                                <p>{{ $user->currency ?? 'USD' }}</p>
                            </div>
                            <div class="py-2">
                                <p class="text-xs text-gray-500">Linked Bank Account</p>
                                <p>{{ $user->linked_bank_account ?? 'None' }}</p>
                            </div>
                        </div>

                        <div class="border-t border-gray-100"></div>

                        <div class="grid grid-cols-2">
                            <div class="py-2">
                                <p class="text-xs text-gray-500">Wallet Provider</p>
                                <p>{{ $user->wallet_provider ?? 'Not set' }}</p>
                            </div>
                            <div class="py-2">
                                <p class="text-xs text-gray-500">Account Status</p>
                                <p class="text-green-500">{{ ucfirst($user->account_status ?? 'Active') }}</p>
                            </div>
                        </div>

                        <div class="border-t border-gray-100"></div>

                        <div class="grid grid-cols-2">
                            <div class="py-2">
                                <p class="text-xs text-gray-500">Trip Savings</p>
                                <p>${{ number_format($totalBalance, 2) }}</p>
                            </div>
                            <div class="py-2">
                                <p class="text-xs text-gray-500">Preferred Method</p>
                                <p>{{ ucfirst(str_replace('_', ' ', $user->preferred_payment_method ?? 'Wallet')) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Savings Progress Card -->
            <div class="bg-white rounded-lg shadow p-4 mb-8">
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <h3 class="text-lg font-medium">Savings Progress</h3>
                        <p class="text-xs text-gray-500">Your overall trip savings progress.</p>
                    </div>
                </div>

                <div class="border-t border-gray-100 pt-4">
                    <div class="flex justify-between items-baseline mb-2">
                        <div>
                            <p class="text-gray-600">Total Saved</p>
                            <p class="text-2xl font-bold">${{ number_format($totalBalance, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Total Goal</p>
                            <p class="text-2xl font-bold">${{ number_format($totalTarget, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Remaining</p>
                            <p class="text-2xl font-bold">${{ number_format(max(0, $totalTarget - $totalBalance), 2) }}</p>
                        </div>
                    </div>

                    <div class="mt-4">
                        <div class="flex justify-between text-sm text-gray-600 mb-1">
                            <span>Progress</span>
                            <span>{{ $progressPercentage }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $progressPercentage }}%"></div>
                        </div>
                    </div>

                    <div class="mt-4 grid grid-cols-2 gap-4">
                        <a href="{{ route('wallet.contribute.form') }}"
                            class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="8" x2="12" y2="16"></line>
                                <line x1="8" y1="12" x2="16" y2="12"></line>
                            </svg>
                            Add Funds
                        </a>
                        <a href="{{ route('wallet.transactions') }}"
                            class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                            </svg>
                            Transaction History
                        </a>
                    </div>
                </div>
            </div>

            <!-- Recent Trips Card -->
            <div class="bg-white rounded-lg shadow p-4 mb-8">
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <h3 class="text-lg font-medium">Recent Trips</h3>
                        <p class="text-xs text-gray-500">Your recently planned or upcoming trips.</p>
                    </div>
                    <a href="{{ route('trips.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-500">
                        View all trips
                    </a>
                </div>

                <div class="border-t border-gray-100 pt-4">
                    @if(isset($user->trips) && $user->trips->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($user->trips->take(2) as $trip)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex justify-between">
                                        <h4 class="text-base font-medium text-gray-900">{{ $trip->title }}</h4>
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $trip->savingsWallet ? number_format($trip->savingsWallet->getProgressPercentageAttribute(), 0) : 0 }}%
                                            Funded
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-500 mt-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="inline-block h-4 w-4 mr-1 text-gray-400"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"></polygon>
                                        </svg>
                                        {{ $trip->destination }}
                                    </p>
                                    <p class="text-sm text-gray-500 mt-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="inline-block h-4 w-4 mr-1 text-gray-400"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                            <line x1="16" y1="2" x2="16" y2="6"></line>
                                            <line x1="8" y1="2" x2="8" y2="6"></line>
                                            <line x1="3" y1="10" x2="21" y2="10"></line>
                                        </svg>
                                        {{ $trip->start_date->format('M j, Y') }} - {{ $trip->end_date->format('M j, Y') }}
                                    </p>
                                    <div class="mt-3 w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-blue-600 h-2 rounded-full"
                                            style="width: {{ $trip->savingsWallet ? $trip->savingsWallet->getProgressPercentageAttribute() : 0 }}%">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <p>No trips planned yet.</p>
                            <a href="{{ route('trips.plan') }}"
                                class="mt-2 inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700">
                                Plan a Trip Now
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Account Settings Links -->
            <div class="bg-white rounded-lg shadow p-4 mb-8">
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <h3 class="text-lg font-medium">Account Settings</h3>
                        <p class="text-xs text-gray-500">Manage your account preferences and security settings.</p>
                    </div>
                </div>

                <div class="border-t border-gray-100 pt-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <a href="{{ route('profile.edit') }}"
                            class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                            <span class="ml-3 text-sm font-medium text-gray-900">Edit Profile</span>
                        </a>
                        <a href="{{ route('profile.security') }}"
                            class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                            </svg>
                            <span class="ml-3 text-sm font-medium text-gray-900">Security Settings</span>
                        </a>
                        <a href="{{ route('profile.notifications') }}"
                            class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                                <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                            </svg>
                            <span class="ml-3 text-sm font-medium text-gray-900">Notification Preferences</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection