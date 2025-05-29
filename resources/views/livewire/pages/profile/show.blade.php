{{-- resources/views/livewire/pages/profile/show.blade.php --}}
@extends('layouts.dashboard')

@section('title', 'Profile - PangoQ')

@section('content')
<div class="min-h-screen bg-slate-50">
    <!-- Hero Section with Cover -->
    <div class="relative">
        <!-- Cover Image -->
        <div class="h-80 bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900 relative overflow-hidden">
            <!-- Subtle pattern overlay -->
            <div class="absolute inset-0 opacity-10">
                <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
                            <path d="M 40 0 L 0 0 0 40" fill="none" stroke="white" stroke-width="1"/>
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#grid)" />
                </svg>
            </div>
            
            <!-- Action Buttons -->
            <div class="absolute top-6 right-6 flex space-x-3">
                <button class="bg-white/10 backdrop-blur-sm text-white px-4 py-2 rounded-lg border border-white/20 hover:bg-white/20 transition-all duration-200">
                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                    </svg>
                    Share Profile
                </button>
                <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-all duration-200">
                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                    </svg>
                    Edit Profile
                </button>
            </div>
        </div>

        <!-- Profile Info Card - Overlapping -->
        <div class="relative -mt-32 mx-6">
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6">
                <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between">
                    <!-- Profile Picture & Basic Info -->
                    <div class="flex flex-col sm:flex-row items-start sm:items-end space-y-4 sm:space-y-0 sm:space-x-6">
                        <div class="relative">
                            <div class="w-32 h-32 rounded-2xl overflow-hidden ring-4 ring-white shadow-lg">
                                @if($user->profile_photo_path)
                                    <img src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white text-3xl font-bold">
                                        {{ $user->initials }}
                                    </div>
                                @endif
                            </div>
                            <!-- Status Indicator -->
                            <div class="absolute -bottom-2 -right-2 bg-green-500 w-8 h-8 rounded-full border-4 border-white flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>

                        <div class="flex-1">
                            <div class="flex items-center space-x-3 mb-2">
                                <h1 class="text-3xl font-bold text-gray-900">{{ $user->name }}</h1>
                                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Verified</span>
                            </div>
                            <p class="text-gray-600 text-lg mb-3">{{ $user->email }}</p>
                            
                            <!-- Quick Stats -->
                            <div class="flex flex-wrap gap-6 text-sm">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    </svg>
                                    <span class="text-gray-600">{{ $user->address ?? 'Sydney, Australia' }}</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a4 4 0 118 0v4m-4 16l-4-4m4 4l4-4m-4 4V9a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2h2m8-12V3a4 4 0 10-8 0v4"/>
                                    </svg>
                                    <span class="text-gray-600">Joined {{ $user->created_at->format('M Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Stats -->
                    <div class="flex space-x-8 mt-6 lg:mt-0">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-gray-900">{{ $user->trips()->count() ?? 3 }}</div>
                            <div class="text-sm text-gray-500">Trips</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-gray-900">${{ number_format($totalBalance ?? 12450, 0) }}</div>
                            <div class="text-sm text-gray-500">Saved</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-gray-900">{{ $progressPercentage ?? 78 }}%</div>
                            <div class="text-sm text-gray-500">Progress</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-6 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column -->
            <div class="lg:col-span-2 space-y-6">
                <!-- About Section -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        About
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div class="flex justify-between py-3 border-b border-gray-100">
                                <span class="text-gray-600">Full Name</span>
                                <span class="font-medium text-gray-900">{{ $user->name }}</span>
                            </div>
                            <div class="flex justify-between py-3 border-b border-gray-100">
                                <span class="text-gray-600">Email</span>
                                <span class="font-medium text-gray-900">{{ $user->email }}</span>
                            </div>
                            <div class="flex justify-between py-3 border-b border-gray-100">
                                <span class="text-gray-600">Phone</span>
                                <span class="font-medium text-gray-900">{{ $user->phone_number ?? '+61 412 345 678' }}</span>
                            </div>
                            <div class="flex justify-between py-3 border-b border-gray-100">
                                <span class="text-gray-600">Date of Birth</span>
                                <span class="font-medium text-gray-900">{{ $user->date_of_birth ? $user->date_of_birth->format('M j, Y') : 'Jan 15, 1990' }}</span>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div class="flex justify-between py-3 border-b border-gray-100">
                                <span class="text-gray-600">Nationality</span>
                                <span class="font-medium text-gray-900 flex items-center">
                                    🇦🇺 {{ $user->nationality ?? 'Australian' }}
                                </span>
                            </div>
                            <div class="flex justify-between py-3 border-b border-gray-100">
                                <span class="text-gray-600">Gender</span>
                                <span class="font-medium text-gray-900">{{ ucfirst($user->gender ?? 'Male') }}</span>
                            </div>
                            <div class="flex justify-between py-3 border-b border-gray-100">
                                <span class="text-gray-600">ID Number</span>
                                <span class="font-medium text-gray-900">{{ $user->id_card_number ?? '••••••3210' }}</span>
                            </div>
                            <div class="flex justify-between py-3 border-b border-gray-100">
                                <span class="text-gray-600">Member Since</span>
                                <span class="font-medium text-gray-900">{{ $user->created_at->format('M Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Travel Statistics -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        Travel Statistics
                    </h2>
                    
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-6">
                        <div class="text-center p-4 bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg">
                            <div class="text-2xl font-bold text-blue-600">{{ $user->trips()->count() ?? 3 }}</div>
                            <div class="text-sm text-blue-600 font-medium">Total Trips</div>
                        </div>
                        <div class="text-center p-4 bg-gradient-to-br from-green-50 to-green-100 rounded-lg">
                            <div class="text-2xl font-bold text-green-600">{{ $user->trips()->where('status', 'completed')->count() ?? 1 }}</div>
                            <div class="text-sm text-green-600 font-medium">Completed</div>
                        </div>
                        <div class="text-center p-4 bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg">
                            <div class="text-2xl font-bold text-purple-600">{{ $user->trips()->where('status', 'active')->count() ?? 2 }}</div>
                            <div class="text-sm text-purple-600 font-medium">Active</div>
                        </div>
                        <div class="text-center p-4 bg-gradient-to-br from-orange-50 to-orange-100 rounded-lg">
                            <div class="text-2xl font-bold text-orange-600">8</div>
                            <div class="text-sm text-orange-600 font-medium">Countries</div>
                        </div>
                    </div>

                    <!-- Savings Progress -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex justify-between items-center mb-3">
                            <h3 class="font-medium text-gray-900">Overall Savings Progress</h3>
                            <span class="text-sm font-medium text-blue-600">{{ $progressPercentage ?? 78 }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2 mb-3">
                            <div class="bg-gradient-to-r from-blue-500 to-purple-600 h-2 rounded-full transition-all duration-1000 ease-out" style="width: {{ $progressPercentage ?? 78 }}%"></div>
                        </div>
                        <div class="flex justify-between text-sm text-gray-600">
                            <span>${{ number_format($totalBalance ?? 12450, 0) }} saved</span>
                            <span>${{ number_format($totalTarget ?? 16000, 0) }} goal</span>
                        </div>
                    </div>
                </div>

                <!-- Recent Trips -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Recent Trips
                        </h2>
                        <a href="{{ route('trips.index') }}" class="text-blue-600 hover:text-blue-700 font-medium text-sm">View All →</a>
                    </div>

                    @if(isset($user->trips) && $user->trips->count() > 0)
                        <div class="space-y-4">
                            @foreach($user->trips->take(3) as $trip)
                            <div class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                <div class="w-16 h-16 bg-gradient-to-br from-blue-400 to-purple-500 rounded-lg flex items-center justify-center text-white font-bold text-lg mr-4">
                                    {{ substr($trip->destination ?? 'TK', 0, 2) }}
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900">{{ $trip->title ?? 'Tokyo Adventure' }}</h3>
                                    <p class="text-sm text-gray-600 mb-2">{{ $trip->destination ?? 'Tokyo, Japan' }}</p>
                                    <div class="w-full bg-gray-200 rounded-full h-1.5">
                                        <div class="bg-blue-500 h-1.5 rounded-full" style="width: {{ $trip->progress ?? 78 }}%"></div>
                                    </div>
                                </div>
                                <div class="text-right ml-4">
                                    <div class="text-sm font-medium text-gray-900">${{ number_format($trip->budget ?? 5000, 0) }}</div>
                                    <div class="text-xs text-gray-500">{{ $trip->progress ?? 78 }}% funded</div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No trips yet</h3>
                            <p class="text-gray-600 mb-4">Start planning your first adventure!</p>
                            <a href="{{ route('trips.plan') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                Plan Your First Trip
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right Sidebar -->
            <div class="space-y-6">
                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                    <div class="space-y-3">
                        <a href="{{ route('trips.plan') }}" class="flex items-center p-3 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition-colors group">
                            <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            <span class="font-medium">Plan New Trip</span>
                        </a>
                        <a href="{{ route('wallet.index') }}" class="flex items-center p-3 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 transition-colors group">
                            <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                            </svg>
                            <span class="font-medium">Add Funds</span>
                        </a>
                        <a href="{{ route('profile.edit') }}" class="flex items-center p-3 bg-purple-50 text-purple-700 rounded-lg hover:bg-purple-100 transition-colors group">
                            <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                            </svg>
                            <span class="font-medium">Edit Profile</span>
                        </a>
                    </div>
                </div>

                <!-- Account Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Account Details</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Account Type</span>
                            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                {{ ucfirst($user->account_type ?? 'Premium') }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Status</span>
                            <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                {{ ucfirst($user->account_status ?? 'Active') }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Currency</span>
                            <span class="font-medium text-gray-900">{{ $user->currency ?? 'USD' }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Account ID</span>
                            <span class="font-mono text-sm text-gray-500">{{ $user->account_number ?? '#PQ-' . str_pad($user->id, 6, '0', STR_PAD_LEFT) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Security & Settings -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Security & Settings</h3>
                    <div class="space-y-3">
                        <a href="{{ route('profile.security') }}" class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5-6a9 9 0 11-10 9"/>
                                </svg>
                                <span class="text-gray-700">Security</span>
                            </div>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                        <a href="{{ route('profile.notifications') }}" class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                </svg>
                                <span class="text-gray-700">Notifications</span>
                            </div>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                       <a href="{{ route('wallet.transactions') }}" class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v6a2 2 0 002 2h2m0 0h6a2 2 0 002-2V7a2 2 0 00-2-2h-6m0 0V5a2 2 0 012-2h4a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                                <span class="text-gray-700">Transactions</span>
                            </div>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                        <a href="{{ route('support') }}" class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="text-gray-700">Help & Support</span>
                            </div>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Activity Timeline -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Activity</h3>
                    <div class="space-y-4">
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-gray-900">Added $500 to savings wallet</p>
                                <p class="text-xs text-gray-500">2 hours ago</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0 w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-gray-900">Completed profile verification</p>
                                <p class="text-xs text-gray-500">1 day ago</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0 w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-gray-900">Created new trip: Tokyo Adventure</p>
                                <p class="text-xs text-gray-500">3 days ago</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0 w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-gray-900">Joined PangoQ community</p>
                                <p class="text-xs text-gray-500">{{ $user->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Achievements -->
                <div class="bg-gradient-to-br from-yellow-50 to-orange-50 rounded-xl border border-yellow-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                        </svg>
                        Achievements
                    </h3>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="text-center p-3 bg-white rounded-lg shadow-sm">
                            <div class="text-2xl mb-1">🏆</div>
                            <div class="text-xs font-medium text-gray-900">First Trip</div>
                            <div class="text-xs text-gray-500">Unlocked</div>
                        </div>
                        <div class="text-center p-3 bg-white rounded-lg shadow-sm">
                            <div class="text-2xl mb-1">💰</div>
                            <div class="text-xs font-medium text-gray-900">Super Saver</div>
                            <div class="text-xs text-gray-500">$10K Saved</div>
                        </div>
                        <div class="text-center p-3 bg-white rounded-lg shadow-sm">
                            <div class="text-2xl mb-1">✅</div>
                            <div class="text-xs font-medium text-gray-900">Verified</div>
                            <div class="text-xs text-gray-500">Profile Complete</div>
                        </div>
                        <div class="text-center p-3 bg-white rounded-lg shadow-sm opacity-50">
                            <div class="text-2xl mb-1">🌍</div>
                            <div class="text-xs font-medium text-gray-900">Explorer</div>
                            <div class="text-xs text-gray-500">Visit 10 Countries</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for enhanced interactions -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animate progress bars
    const progressBars = document.querySelectorAll('[style*="width:"]');
    setTimeout(() => {
        progressBars.forEach(bar => {
            const width = bar.style.width;
            bar.style.width = '0%';
            setTimeout(() => {
                bar.style.width = width;
            }, 100);
        });
    }, 500);

    // Add smooth scroll to internal links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Add hover effects to cards
    const cards = document.querySelectorAll('.bg-white');
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
            this.style.boxShadow = '0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)';
            this.style.transition = 'all 0.3s ease';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = '';
        });
    });

    // Animate statistics on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateValue(entry.target);
            }
        });
    }, observerOptions);

    // Observe statistic elements
    document.querySelectorAll('.text-2xl.font-bold').forEach(el => {
        observer.observe(el);
    });

    function animateValue(element) {
        const text = element.textContent;
        const match = text.match(/\d+/);
        if (match) {
            const finalValue = parseInt(match[0]);
            const duration = 1000;
            const startTime = performance.now();
            
            function updateValue(currentTime) {
                const elapsed = currentTime - startTime;
                const progress = Math.min(elapsed / duration, 1);
                const currentValue = Math.floor(progress * finalValue);
                
                element.textContent = text.replace(/\d+/, currentValue);
                
                if (progress < 1) {
                    requestAnimationFrame(updateValue);
                }
            }
            
            requestAnimationFrame(updateValue);
        }
    }

    // Add ripple effect to buttons
    const buttons = document.querySelectorAll('button, a[class*="bg-"]');
    buttons.forEach(button => {
        button.addEventListener('click', function(e) {
            const ripple = document.createElement('span');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            
            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            ripple.classList.add('ripple');
            
            this.style.position = 'relative';
            this.style.overflow = 'hidden';
            this.appendChild(ripple);
            
            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });
});
</script>

<!-- Custom styles for enhanced effects -->
<style>
    .ripple {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.4);
        transform: scale(0);
        animation: ripple-animation 0.6s linear;
        pointer-events: none;
    }

    @keyframes ripple-animation {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }

    /* Smooth transitions for all interactive elements */
    * {
        transition: all 0.2s ease;
    }

    /* Custom scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
    }

    ::-webkit-scrollbar-track {
        background: #f1f5f9;
    }

    ::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    /* Progress bar animation */
    .progress-bar {
        transition: width 1s ease-out;
    }
</style>
@endsection