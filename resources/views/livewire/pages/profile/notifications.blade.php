{{-- resources/views/livewire/pages/profile/notifications.blade.php --}}
@extends('layouts.dashboard')

@section('title', 'Notification Settings - PangoQ')

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
                        <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                            <svg class="w-6 h-6 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                            </svg>
                            Notification Preferences
                        </h1>
                        <p class="text-sm text-gray-600">Customize how and when you receive notifications</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <div class="flex items-center space-x-2 text-sm">
                        <div class="w-3 h-3 bg-blue-500 rounded-full animate-pulse"></div>
                        <span class="text-blue-700 font-medium">Notifications Active</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Success Messages -->
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

        <!-- Notification Overview -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium text-gray-600">Email Notifications</h3>
                        <p class="text-2xl font-bold text-blue-600 mt-1">12</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-2">Last 30 days</p>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium text-gray-600">Push Notifications</h3>
                        <p class="text-2xl font-bold text-green-600 mt-1">28</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-2">Last 30 days</p>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium text-gray-600">In-App Alerts</h3>
                        <p class="text-2xl font-bold text-purple-600 mt-1">45</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-2">Last 30 days</p>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium text-gray-600">Active Channels</h3>
                        <p class="text-2xl font-bold text-orange-600 mt-1">5</p>
                    </div>
                    <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/>
                        </svg>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-2">Email, Push, SMS, etc.</p>
            </div>
        </div>

        <!-- Notification Settings Form -->
        <form action="{{ route('profile.notifications.update') }}" method="POST" id="notification-form">
            @csrf
            @method('PATCH')

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Left Column -->
                <div class="space-y-8">
                    <!-- Trip Notifications -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-8 py-6 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Trip Notifications
                                    </h2>
                                    <p class="text-sm text-gray-600 mt-1">Stay updated on your travel plans and activities</p>
                                </div>
                                <button type="button" onclick="toggleSection('trip')" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    <span id="trip-toggle-text">Configure All</span>
                                </button>
                            </div>
                        </div>

                        <div class="p-8">
                            <div class="space-y-6">
                                <!-- Trip Updates -->
                                <div class="flex items-start justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                    <div class="flex items-start">
                                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-4 mt-1">
                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="font-semibold text-gray-900">Trip Updates</h4>
                                            <p class="text-sm text-gray-600 mt-1">Get notified when there are changes to your trips, including itinerary updates, booking confirmations, and status changes.</p>
                                            <div class="flex items-center space-x-4 mt-3 text-xs text-gray-500">
                                                <span class="flex items-center">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                                                    </svg>
                                                    Email & Push
                                                </span>
                                                <span>High Priority</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex flex-col items-end space-y-2">
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" name="trip_updates" class="sr-only peer" value="1" {{ old('trip_updates', true) ? 'checked' : '' }}>
                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                        </label>
                                        <span class="text-xs text-green-600 font-medium">Enabled</span>
                                    </div>
                                </div>

                                <!-- Trip Invitations -->
                                <div class="flex items-start justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                    <div class="flex items-start">
                                        <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center mr-4 mt-1">
                                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="font-semibold text-gray-900">Trip Invitations</h4>
                                            <p class="text-sm text-gray-600 mt-1">Receive notifications when friends invite you to join their trips or when someone wants to collaborate on planning.</p>
                                            <div class="flex items-center space-x-4 mt-3 text-xs text-gray-500">
                                                <span class="flex items-center">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                                                    </svg>
                                                    Email, Push & SMS
                                                </span>
                                                <span>Critical</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex flex-col items-end space-y-2">
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" name="trip_invitations" class="sr-only peer" value="1" {{ old('trip_invitations', true) ? 'checked' : '' }}>
                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                        </label>
                                        <span class="text-xs text-green-600 font-medium">Enabled</span>
                                    </div>
                                </div>

                                <!-- Trip Reminders -->
                                <div class="flex items-start justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                    <div class="flex items-start">
                                        <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center mr-4 mt-1">
                                            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="font-semibold text-gray-900">Trip Reminders</h4>
                                            <p class="text-sm text-gray-600 mt-1">Get helpful reminders about upcoming trips, packing lists, check-in times, and important deadlines.</p>
                                            <div class="flex items-center space-x-4 mt-3 text-xs text-gray-500">
                                                <span class="flex items-center">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                                                    </svg>
                                                    Email & Push
                                                </span>
                                                <span>Medium Priority</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex flex-col items-end space-y-2">
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" name="trip_reminders" class="sr-only peer" value="1" {{ old('trip_reminders', true) ? 'checked' : '' }}>
                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                        </label>
                                        <span class="text-xs text-green-600 font-medium">Enabled</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Financial Notifications -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-8 py-6 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                        </svg>
                                        Financial Notifications
                                    </h2>
                                    <p class="text-sm text-gray-600 mt-1">Stay informed about payments, savings, and financial activity</p>
                                </div>
                                <button type="button" onclick="toggleSection('financial')" class="text-green-600 hover:text-green-800 text-sm font-medium">
                                    <span id="financial-toggle-text">Configure All</span>
                                </button>
                            </div>
                        </div>

                        <div class="p-8">
                            <div class="space-y-6">
                                <!-- Payment Confirmations -->
                                <div class="flex items-start justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                    <div class="flex items-start">
                                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-4 mt-1">
                                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="font-semibold text-gray-900">Payment Confirmations</h4>
                                            <p class="text-sm text-gray-600 mt-1">Receive instant confirmations for all payments, deposits, and withdrawals for security and record-keeping.</p>
                                            <div class="flex items-center space-x-4 mt-3 text-xs text-gray-500">
                                                <span class="flex items-center">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                                                    </svg>
                                                    Email & SMS
                                                </span>
                                                <span>Critical</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex flex-col items-end space-y-2">
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" name="payment_notifications" class="sr-only peer" value="1" {{ old('payment_notifications', true) ? 'checked' : '' }}>
                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                        </label>
                                        <span class="text-xs text-green-600 font-medium">Enabled</span>
                                    </div>
                                </div>

                                <!-- Savings Reminders -->
                                <div class="flex items-start justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                    <div class="flex items-start">
                                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-4 mt-1">
                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="font-semibold text-gray-900">Savings Reminders</h4>
                                            <p class="text-sm text-gray-600 mt-1">Get gentle reminders to contribute to your savings goals and stay on track with your travel fund.</p>
                                            <div class="flex items-center space-x-4 mt-3 text-xs text-gray-500">
                                                <span class="flex items-center">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                                                    </svg>
                                                    Email & Push
                                                </span>
                                                <span>Weekly</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Start Time</label>
                                        <input type="time" name="quiet_hours_start" value="{{ old('quiet_hours_start', '22:00') }}" 
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">End Time</label>
                                        <input type="time" name="quiet_hours_end" value="{{ old('quiet_hours_end', '08:00') }}" 
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Time Zone</label>
                                    <select name="timezone" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                                        <option value="Australia/Sydney" {{ old('timezone', 'Australia/Sydney') == 'Australia/Sydney' ? 'selected' : '' }}>Australia/Sydney (AEDT)</option>
                                        <option value="Australia/Melbourne" {{ old('timezone') == 'Australia/Melbourne' ? 'selected' : '' }}>Australia/Melbourne (AEDT)</option>
                                        <option value="Australia/Perth" {{ old('timezone') == 'Australia/Perth' ? 'selected' : '' }}>Australia/Perth (AWST)</option>
                                        <option value="UTC" {{ old('timezone') == 'UTC' ? 'selected' : '' }}>UTC</option>
                                        <option value="America/New_York" {{ old('timezone') == 'America/New_York' ? 'selected' : '' }}>Eastern Time (ET)</option>
                                        <option value="America/Los_Angeles" {{ old('timezone') == 'America/Los_Angeles' ? 'selected' : '' }}>Pacific Time (PT)</option>
                                        <option value="Europe/London" {{ old('timezone') == 'Europe/London' ? 'selected' : '' }}>Greenwich Mean Time (GMT)</option>
                                    </select>
                                </div>

                                <div class="bg-teal-50 border border-teal-200 rounded-lg p-4">
                                    <div class="flex items-start">
                                        <svg class="w-5 h-5 text-teal-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <div class="text-sm text-teal-800">
                                            <p class="font-medium mb-1">About Quiet Hours</p>
                                            <p>During quiet hours, only critical notifications (like security alerts and payment confirmations) will be delivered. All other notifications will be delayed until quiet hours end.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Save Button -->
            <div class="mt-8 flex justify-between items-center">
                <div class="text-sm text-gray-500">
                    <p>Changes are saved automatically when you toggle settings</p>
                </div>
                <button type="submit" class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-medium rounded-lg hover:from-blue-700 hover:to-indigo-700 focus:ring-4 focus:ring-blue-500 focus:ring-opacity-50 transition-all duration-200 shadow-lg hover:shadow-xl">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Save All Preferences
                </button>
            </div>
        </form>

        <!-- Notification Test Section -->
        <div class="mt-8 bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-gray-50 to-slate-50 px-8 py-6 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    Test Notifications
                </h2>
                <p class="text-sm text-gray-600 mt-1">Send test notifications to verify your settings</p>
            </div>

            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <button type="button" onclick="sendTestNotification('email')" 
                            class="flex items-center justify-center px-4 py-3 border border-blue-300 text-blue-700 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Test Email
                    </button>
                    <button type="button" onclick="sendTestNotification('push')" 
                            class="flex items-center justify-center px-4 py-3 border border-green-300 text-green-700 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                        Test Push
                    </button>
                    <button type="button" onclick="sendTestNotification('sms')" 
                            class="flex items-center justify-center px-4 py-3 border border-purple-300 text-purple-700 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        Test SMS
                    </button>
                </div>

                <div id="test-notification-status" class="hidden mt-4 p-4 rounded-lg">
                    <p class="text-sm font-medium"></p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
// Auto-save functionality
let saveTimeout;

function autoSaveSettings() {
    clearTimeout(saveTimeout);
    saveTimeout = setTimeout(() => {
        const form = document.getElementById('notification-form');
        const formData = new FormData(form);
        
        // Show saving indicator
        showSaveStatus('saving');
        
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSaveStatus('saved');
            } else {
                showSaveStatus('error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showSaveStatus('error');
        });
    }, 1000);
}

function showSaveStatus(status) {
    const statusDiv = document.getElementById('save-status');
    if (!statusDiv) {
        // Create status indicator if it doesn't exist
        const indicator = document.createElement('div');
        indicator.id = 'save-status';
        indicator.className = 'fixed top-4 right-4 px-4 py-2 rounded-lg text-sm font-medium z-50 transition-all duration-300';
        document.body.appendChild(indicator);
    }
    
    const indicator = document.getElementById('save-status');
    
    switch(status) {
        case 'saving':
            indicator.className = 'fixed top-4 right-4 px-4 py-2 rounded-lg text-sm font-medium z-50 transition-all duration-300 bg-blue-100 text-blue-800 border border-blue-200';
            indicator.innerHTML = `
                <div class="flex items-center">
                    <svg class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Saving...
                </div>
            `;
            break;
        case 'saved':
            indicator.className = 'fixed top-4 right-4 px-4 py-2 rounded-lg text-sm font-medium z-50 transition-all duration-300 bg-green-100 text-green-800 border border-green-200';
            indicator.innerHTML = `
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Settings saved
                </div>
            `;
            setTimeout(() => {
                indicator.style.opacity = '0';
                setTimeout(() => indicator.remove(), 300);
            }, 2000);
            break;
        case 'error':
            indicator.className = 'fixed top-4 right-4 px-4 py-2 rounded-lg text-sm font-medium z-50 transition-all duration-300 bg-red-100 text-red-800 border border-red-200';
            indicator.innerHTML = `
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Error saving settings
                </div>
            `;
            setTimeout(() => {
                indicator.style.opacity = '0';
                setTimeout(() => indicator.remove(), 300);
            }, 3000);
            break;
    }
}

// Toggle section functionality
function toggleSection(sectionName) {
    const checkboxes = document.querySelectorAll(`input[name^="${sectionName}"]`);
    const allEnabled = Array.from(checkboxes).every(cb => cb.checked);
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = !allEnabled;
        updateToggleText(checkbox);
    });
    
    autoSaveSettings();
}

function updateToggleText(checkbox) {
    const status = checkbox.parentElement.parentElement.querySelector('.text-xs');
    if (status) {
        if (checkbox.checked) {
            status.textContent = 'Enabled';
            status.className = 'text-xs text-green-600 font-medium';
        } else {
            status.textContent = 'Disabled';
            status.className = 'text-xs text-gray-500 font-medium';
        }
    }
}

// Test notification functionality
function sendTestNotification(type) {
    const button = event.target.closest('button');
    const originalText = button.innerHTML;
    const statusDiv = document.getElementById('test-notification-status');
    
    // Show loading state
    button.disabled = true;
    button.innerHTML = `
        <svg class="animate-spin w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Sending...
    `;
    
    // Simulate test notification (replace with actual endpoint when available)
    setTimeout(() => {
        // Check if the route exists by attempting to generate it
        let testUrl;
        try {
            testUrl = '{{ route("profile.notifications.update") }}'; // Use existing route for now
        } catch (e) {
            // Fallback to simulate successful test
            statusDiv.className = 'mt-4 p-4 rounded-lg bg-green-50 border border-green-200';
            statusDiv.querySelector('p').className = 'text-sm font-medium text-green-800';
            statusDiv.querySelector('p').textContent = `✅ Test ${type} notification would be sent successfully! (Demo mode - implement route 'profile.notifications.test' for actual testing)`;
            statusDiv.classList.remove('hidden');
            
            setTimeout(() => {
                statusDiv.classList.add('hidden');
            }, 5000);
            return;
        }
        
        // For now, show success message (implement actual API call when route is ready)
        statusDiv.className = 'mt-4 p-4 rounded-lg bg-blue-50 border border-blue-200';
        statusDiv.querySelector('p').className = 'text-sm font-medium text-blue-800';
        statusDiv.querySelector('p').innerHTML = `
            <div class="flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Test ${type} notification ready to send. Add route 'profile.notifications.test' to enable actual testing.
            </div>
        `;
        statusDiv.classList.remove('hidden');
        
        setTimeout(() => {
            statusDiv.classList.add('hidden');
        }, 5000);
        
        // Uncomment below when you add the route 'profile.notifications.test'
        /*
        fetch(testUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ type: type, action: 'test' })
        })
        .then(response => response.json())
        .then(data => {
            statusDiv.className = 'mt-4 p-4 rounded-lg bg-green-50 border border-green-200';
            statusDiv.querySelector('p').className = 'text-sm font-medium text-green-800';
            statusDiv.querySelector('p').textContent = data.message || `Test ${type} notification sent successfully!`;
            statusDiv.classList.remove('hidden');
            
            setTimeout(() => {
                statusDiv.classList.add('hidden');
            }, 5000);
        })
        .catch(error => {
            statusDiv.className = 'mt-4 p-4 rounded-lg bg-red-50 border border-red-200';
            statusDiv.querySelector('p').className = 'text-sm font-medium text-red-800';
            statusDiv.querySelector('p').textContent = `Failed to send test ${type} notification. Please try again.`;
            statusDiv.classList.remove('hidden');
            
            setTimeout(() => {
                statusDiv.classList.add('hidden');
            }, 5000);
        });
        */
    }, 1500)
    .finally(() => {
        // Reset button
        button.disabled = false;
        button.innerHTML = originalText;
    });
}

// Enhanced toggle interactions
document.addEventListener('DOMContentLoaded', function() {
    // Add event listeners to all toggle switches
    const toggles = document.querySelectorAll('input[type="checkbox"]');
    toggles.forEach(toggle => {
        toggle.addEventListener('change', function() {
            updateToggleText(this);
            autoSaveSettings();
            
            // Add visual feedback
            this.parentElement.classList.add('scale-110');
            setTimeout(() => {
                this.parentElement.classList.remove('scale-110');
            }, 150);
        });
    });
    
    // Initialize toggle text states
    toggles.forEach(updateToggleText);
    
    // Enhanced form interactions
    const inputs = document.querySelectorAll('input, select');
    inputs.forEach(input => {
        input.addEventListener('change', autoSaveSettings);
        
        // Add focus effects
        input.addEventListener('focus', function() {
            this.classList.add('ring-4', 'ring-blue-500', 'ring-opacity-25');
        });
        
        input.addEventListener('blur', function() {
            this.classList.remove('ring-4', 'ring-blue-500', 'ring-opacity-25');
        });
    });
    
    // Animate stats cards on load
    const statsCards = document.querySelectorAll('.grid .bg-white');
    statsCards.forEach((card, index) => {
        setTimeout(() => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'all 0.6s ease-out';
            
            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 100);
        }, index * 100);
    });
    
    // Smooth scrolling for internal links
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
    
    // Add hover effects to notification cards
    const notificationCards = document.querySelectorAll('.hover\\:bg-gray-100');
    notificationCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateX(4px)';
            this.style.boxShadow = '0 4px 6px -1px rgba(0, 0, 0, 0.1)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateX(0)';
            this.style.boxShadow = 'none';
        });
    });
});

// Add custom styles for enhanced animations
const style = document.createElement('style');
style.textContent = `
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    .animate-spin {
        animation: spin 1s linear infinite;
    }
    .scale-110 {
        transform: scale(1.1);
        transition: transform 0.15s ease-in-out;
    }
    .hover\\:bg-gray-100 {
        transition: all 0.2s ease-in-out;
    }
`;
document.head.appendChild(style);
</script>

@endsection