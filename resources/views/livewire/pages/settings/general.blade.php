{{-- resources/views/livewire/pages/settings/general.blade.php --}}
@extends('layouts.dashboard')

@section('title', 'General Settings - PangoQ')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-gray-100">
    <!-- Header Section -->
    <div class="bg-white border-b border-gray-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('dashboard') }}" class="flex items-center text-gray-600 hover:text-gray-900 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Back to Dashboard
                    </a>
                    <div class="h-6 w-px bg-gray-300"></div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                            <svg class="w-6 h-6 mr-3 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            General Settings
                        </h1>
                        <p class="text-sm text-gray-600">Customize your application preferences and display settings</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <div class="flex items-center space-x-2 text-sm">
                        <div class="w-3 h-3 bg-indigo-500 rounded-full animate-pulse"></div>
                        <span class="text-indigo-700 font-medium">Auto-Save Enabled</span>
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

        <!-- Settings Preview Card -->
        <div class="mb-8 bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-indigo-50 to-purple-50 px-8 py-6 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    Current Settings Preview
                </h2>
                <p class="text-sm text-gray-600 mt-1">See how your settings will look across the application</p>
            </div>

            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="text-center p-4 border border-gray-200 rounded-lg">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h4 class="font-medium text-gray-900">Current Time</h4>
                        <p class="text-sm text-gray-600 mt-1" id="preview-time">Loading...</p>
                        <p class="text-xs text-gray-500 mt-1" id="preview-timezone">{{ session('timezone', 'UTC') }}</p>
                    </div>

                    <div class="text-center p-4 border border-gray-200 rounded-lg">
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h4 class="font-medium text-gray-900">Today's Date</h4>
                        <p class="text-sm text-gray-600 mt-1" id="preview-date">Loading...</p>
                        <p class="text-xs text-gray-500 mt-1" id="preview-date-format">Format preview</p>
                    </div>

                    <div class="text-center p-4 border border-gray-200 rounded-lg">
                        <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                            </svg>
                        </div>
                        <h4 class="font-medium text-gray-900">Sample Price</h4>
                        <p class="text-sm text-gray-600 mt-1" id="preview-currency">$1,234.56</p>
                        <p class="text-xs text-gray-500 mt-1" id="preview-currency-code">{{ session('currency', 'USD') }}</p>
                    </div>

                    <div class="text-center p-4 border border-gray-200 rounded-lg">
                        <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <h4 class="font-medium text-gray-900">Sample Distance</h4>
                        <p class="text-sm text-gray-600 mt-1" id="preview-distance">25.4 km</p>
                        <p class="text-xs text-gray-500 mt-1" id="preview-distance-unit">Kilometers</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Left Column -->
            <div class="space-y-8">
                <!-- Regional & Language Settings -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-8 py-6 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Regional & Language
                                </h2>
                                <p class="text-sm text-gray-600 mt-1">Set your preferred language, timezone, and currency</p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('settings.general.update') }}" method="POST" id="regional-form" class="p-8">
                        @csrf
                        @method('PATCH')

                        <div class="space-y-6">
                            <!-- Language Selection -->
                            <div>
                                <label for="language" class="block text-sm font-semibold text-gray-900 mb-3">
                                    <svg class="w-4 h-4 inline mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l3-3m0 0l3-3m-3 3H3m18-12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Language
                                </label>
                                <div class="relative">
                                    <select id="language" name="language" 
                                        class="w-full px-4 py-3 pr-8 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors appearance-none bg-white">
                                        @if(isset($languages))
                                            @foreach($languages as $code => $name)
                                                <option value="{{ $code }}" {{ session('language', 'en') === $code ? 'selected' : '' }}>{{ $name }}</option>
                                            @endforeach
                                        @else
                                            <option value="en" {{ session('language', 'en') === 'en' ? 'selected' : '' }}>🇺🇸 English</option>
                                            <option value="es" {{ session('language') === 'es' ? 'selected' : '' }}>🇪🇸 Español</option>
                                            <option value="fr" {{ session('language') === 'fr' ? 'selected' : '' }}>🇫🇷 Français</option>
                                            <option value="de" {{ session('language') === 'de' ? 'selected' : '' }}>🇩🇪 Deutsch</option>
                                            <option value="it" {{ session('language') === 'it' ? 'selected' : '' }}>🇮🇹 Italiano</option>
                                            <option value="pt" {{ session('language') === 'pt' ? 'selected' : '' }}>🇵🇹 Português</option>
                                            <option value="ja" {{ session('language') === 'ja' ? 'selected' : '' }}>🇯🇵 日本語</option>
                                            <option value="ko" {{ session('language') === 'ko' ? 'selected' : '' }}>🇰🇷 한국어</option>
                                            <option value="zh" {{ session('language') === 'zh' ? 'selected' : '' }}>🇨🇳 中文</option>
                                            <option value="ar" {{ session('language') === 'ar' ? 'selected' : '' }}>🇸🇦 العربية</option>
                                            <option value="hi" {{ session('language') === 'hi' ? 'selected' : '' }}>🇮🇳 हिन्दी</option>
                                            <option value="sw" {{ session('language') === 'sw' ? 'selected' : '' }}>🇰🇪 Kiswahili</option>
                                        @endif
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </div>
                                </div>
                                <p class="mt-2 text-xs text-gray-500">Choose your preferred language for the interface</p>
                                @error('language')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Timezone Selection -->
                            <div>
                                <label for="timezone" class="block text-sm font-semibold text-gray-900 mb-3">
                                    <svg class="w-4 h-4 inline mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Timezone
                                </label>
                                <div class="relative">
                                    <select id="timezone" name="timezone" 
                                        class="w-full px-4 py-3 pr-8 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors appearance-none bg-white">
                                        @if(isset($timezones))
                                            @foreach($timezones as $key => $zone)
                                                <option value="{{ $key }}" {{ session('timezone', 'UTC') === $key ? 'selected' : '' }}>{{ $zone }}</option>
                                            @endforeach
                                        @else
                                            <option value="UTC" {{ session('timezone', 'UTC') === 'UTC' ? 'selected' : '' }}>UTC - Coordinated Universal Time</option>
                                            <option value="America/New_York" {{ session('timezone') === 'America/New_York' ? 'selected' : '' }}>EST - Eastern Standard Time</option>
                                            <option value="America/Chicago" {{ session('timezone') === 'America/Chicago' ? 'selected' : '' }}>CST - Central Standard Time</option>
                                            <option value="America/Denver" {{ session('timezone') === 'America/Denver' ? 'selected' : '' }}>MST - Mountain Standard Time</option>
                                            <option value="America/Los_Angeles" {{ session('timezone') === 'America/Los_Angeles' ? 'selected' : '' }}>PST - Pacific Standard Time</option>
                                            <option value="Europe/London" {{ session('timezone') === 'Europe/London' ? 'selected' : '' }}>GMT - Greenwich Mean Time</option>
                                            <option value="Europe/Paris" {{ session('timezone') === 'Europe/Paris' ? 'selected' : '' }}>CET - Central European Time</option>
                                            <option value="Europe/Berlin" {{ session('timezone') === 'Europe/Berlin' ? 'selected' : '' }}>CET - Central European Time (Berlin)</option>
                                            <option value="Asia/Tokyo" {{ session('timezone') === 'Asia/Tokyo' ? 'selected' : '' }}>JST - Japan Standard Time</option>
                                            <option value="Asia/Shanghai" {{ session('timezone') === 'Asia/Shanghai' ? 'selected' : '' }}>CST - China Standard Time</option>
                                            <option value="Asia/Kolkata" {{ session('timezone') === 'Asia/Kolkata' ? 'selected' : '' }}>IST - India Standard Time</option>
                                            <option value="Australia/Sydney" {{ session('timezone') === 'Australia/Sydney' ? 'selected' : '' }}>AEDT - Australian Eastern Daylight Time</option>
                                            <option value="Australia/Melbourne" {{ session('timezone') === 'Australia/Melbourne' ? 'selected' : '' }}>AEDT - Australian Eastern Daylight Time (Melbourne)</option>
                                            <option value="Australia/Perth" {{ session('timezone') === 'Australia/Perth' ? 'selected' : '' }}>AWST - Australian Western Standard Time</option>
                                            <option value="Africa/Nairobi" {{ session('timezone') === 'Africa/Nairobi' ? 'selected' : '' }}>EAT - East Africa Time</option>
                                            <option value="Africa/Lagos" {{ session('timezone') === 'Africa/Lagos' ? 'selected' : '' }}>WAT - West Africa Time</option>
                                            <option value="Africa/Cairo" {{ session('timezone') === 'Africa/Cairo' ? 'selected' : '' }}>EET - Eastern European Time</option>
                                        @endif
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </div>
                                </div>
                                <p class="mt-2 text-xs text-gray-500">Your local timezone for accurate time display</p>
                                @error('timezone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Currency Selection -->
                            <div>
                                <label for="currency" class="block text-sm font-semibold text-gray-900 mb-3">
                                    <svg class="w-4 h-4 inline mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                    </svg>
                                    Primary Currency
                                </label>
                                <div class="relative">
                                    <select id="currency" name="currency" 
                                        class="w-full px-4 py-3 pr-8 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors appearance-none bg-white">
                                        <option value="USD" {{ session('currency', 'USD') === 'USD' ? 'selected' : '' }}>🇺🇸 USD - United States Dollar</option>
                                        <option value="EUR" {{ session('currency') === 'EUR' ? 'selected' : '' }}>🇪🇺 EUR - Euro</option>
                                        <option value="GBP" {{ session('currency') === 'GBP' ? 'selected' : '' }}>🇬🇧 GBP - British Pound Sterling</option>
                                        <option value="AUD" {{ session('currency') === 'AUD' ? 'selected' : '' }}>🇦🇺 AUD - Australian Dollar</option>
                                        <option value="CAD" {{ session('currency') === 'CAD' ? 'selected' : '' }}>🇨🇦 CAD - Canadian Dollar</option>
                                        <option value="CHF" {{ session('currency') === 'CHF' ? 'selected' : '' }}>🇨🇭 CHF - Swiss Franc</option>
                                        <option value="JPY" {{ session('currency') === 'JPY' ? 'selected' : '' }}>🇯🇵 JPY - Japanese Yen</option>
                                        <option value="CNY" {{ session('currency') === 'CNY' ? 'selected' : '' }}>🇨🇳 CNY - Chinese Yuan</option>
                                        <option value="INR" {{ session('currency') === 'INR' ? 'selected' : '' }}>🇮🇳 INR - Indian Rupee</option>
                                        <option value="KES" {{ session('currency') === 'KES' ? 'selected' : '' }}>🇰🇪 KES - Kenyan Shilling</option>
                                        <option value="ZAR" {{ session('currency') === 'ZAR' ? 'selected' : '' }}>🇿🇦 ZAR - South African Rand</option>
                                        <option value="NGN" {{ session('currency') === 'NGN' ? 'selected' : '' }}>🇳🇬 NGN - Nigerian Naira</option>
                                        <option value="EGP" {{ session('currency') === 'EGP' ? 'selected' : '' }}>🇪🇬 EGP - Egyptian Pound</option>
                                        <option value="BRL" {{ session('currency') === 'BRL' ? 'selected' : '' }}>🇧🇷 BRL - Brazilian Real</option>
                                        <option value="MXN" {{ session('currency') === 'MXN' ? 'selected' : '' }}>🇲🇽 MXN - Mexican Peso</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </div>
                                </div>
                                <p class="mt-2 text-xs text-gray-500">All prices will be displayed in this currency</p>
                                @error('currency')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end">
                            <button type="submit" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-500 focus:ring-opacity-50 transition-all duration-200 shadow-lg hover:shadow-xl">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Save Regional Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-8">
                <!-- Display Preferences -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-8 py-6 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    Display Preferences
                                </h2>
                                <p class="text-sm text-gray-600 mt-1">Customize how information is displayed</p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('settings.general.update') }}" method="POST" id="display-form" class="p-8">
                        @csrf
                        @method('PATCH')

                        <div class="space-y-6">
                            <!-- Date Format -->
                            <div>
                                <label for="date_format" class="block text-sm font-semibold text-gray-900 mb-3">
                                    <svg class="w-4 h-4 inline mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    Date Format
                                </label>
                                <div class="relative">
                                    <select id="date_format" name="date_format" 
                                        class="w-full px-4 py-3 pr-8 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors appearance-none bg-white">
                                        <option value="MM/DD/YYYY" {{ session('date_format', 'MM/DD/YYYY') === 'MM/DD/YYYY' ? 'selected' : '' }}>📅 MM/DD/YYYY (12/31/2024)</option>
                                        <option value="DD/MM/YYYY" {{ session('date_format') === 'DD/MM/YYYY' ? 'selected' : '' }}>📅 DD/MM/YYYY (31/12/2024)</option>
                                        <option value="YYYY-MM-DD" {{ session('date_format') === 'YYYY-MM-DD' ? 'selected' : '' }}>📅 YYYY-MM-DD (2024-12-31)</option>
                                        <option value="DD MMM YYYY" {{ session('date_format') === 'DD MMM YYYY' ? 'selected' : '' }}>📅 DD MMM YYYY (31 Dec 2024)</option>
                                        <option value="MMM DD, YYYY" {{ session('date_format') === 'MMM DD, YYYY' ? 'selected' : '' }}>📅 MMM DD, YYYY (Dec 31, 2024)</option>
                                        <option value="DD MMMM YYYY" {{ session('date_format') === 'DD MMMM YYYY' ? 'selected' : '' }}>📅 DD MMMM YYYY (31 December 2024)</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </div>
                                </div>
                                <p class="mt-2 text-xs text-gray-500">How dates appear throughout the application</p>
                                @error('date_format')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Time Format -->
                            <div>
                                <label for="time_format" class="block text-sm font-semibold text-gray-900 mb-3">
                                    <svg class="w-4 h-4 inline mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Time Format
                                </label>
                                <div class="grid grid-cols-2 gap-3">
                                    <label class="relative cursor-pointer">
                                        <input type="radio" name="time_format" value="12h" {{ session('time_format', '12h') === '12h' ? 'checked' : '' }} 
                                            class="sr-only peer">
                                        <div class="w-full p-4 border-2 border-gray-300 rounded-lg text-center peer-checked:border-green-500 peer-checked:bg-green-50 transition-all hover:border-green-300">
                                            <div class="text-lg font-medium text-gray-900">🕐 12-hour</div>
                                            <div class="text-sm text-gray-600">1:30 PM</div>
                                        </div>
                                    </label>
                                    <label class="relative cursor-pointer">
                                        <input type="radio" name="time_format" value="24h" {{ session('time_format') === '24h' ? 'checked' : '' }} 
                                            class="sr-only peer">
                                        <div class="w-full p-4 border-2 border-gray-300 rounded-lg text-center peer-checked:border-green-500 peer-checked:bg-green-50 transition-all hover:border-green-300">
                                            <div class="text-lg font-medium text-gray-900">🕐 24-hour</div>
                                            <div class="text-sm text-gray-600">13:30</div>
                                        </div>
                                    </label>
                                </div>
                                <p class="mt-2 text-xs text-gray-500">Choose between 12-hour or 24-hour time display</p>
                                @error('time_format')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Distance Unit -->
                            <div>
                                <label for="distance_unit" class="block text-sm font-semibold text-gray-900 mb-3">
                                    <svg class="w-4 h-4 inline mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    Distance Unit
                                </label>
                                <div class="grid grid-cols-2 gap-3">
                                    <label class="relative cursor-pointer">
                                        <input type="radio" name="distance_unit" value="km" {{ session('distance_unit', 'km') === 'km' ? 'checked' : '' }} 
                                            class="sr-only peer">
                                        <div class="w-full p-4 border-2 border-gray-300 rounded-lg text-center peer-checked:border-green-500 peer-checked:bg-green-50 transition-all hover:border-green-300">
                                            <div class="text-lg font-medium text-gray-900">📏 Kilometers</div>
                                            <div class="text-sm text-gray-600">25.4 km</div>
                                        </div>
                                    </label>
                                    <label class="relative cursor-pointer">
                                        <input type="radio" name="distance_unit" value="mi" {{ session('distance_unit') === 'mi' ? 'checked' : '' }} 
                                            class="sr-only peer">
                                        <div class="w-full p-4 border-2 border-gray-300 rounded-lg text-center peer-checked:border-green-500 peer-checked:bg-green-50 transition-all hover:border-green-300">
                                            <div class="text-lg font-medium text-gray-900">📏 Miles</div>
                                            <div class="text-sm text-gray-600">15.8 mi</div>
                                        </div>
                                    </label>
                                </div>
                                <p class="mt-2 text-xs text-gray-500">Preferred unit for displaying distances</p>
                                @error('distance_unit')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Temperature Unit -->
                            <div>
                                <label for="temperature_unit" class="block text-sm font-semibold text-gray-900 mb-3">
                                    <svg class="w-4 h-4 inline mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h14a2 2 0 002-2v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                                    </svg>
                                    Temperature Unit
                                </label>
                                <div class="grid grid-cols-2 gap-3">
                                    <label class="relative cursor-pointer">
                                        <input type="radio" name="temperature_unit" value="celsius" {{ session('temperature_unit', 'celsius') === 'celsius' ? 'checked' : '' }} 
                                            class="sr-only peer">
                                        <div class="w-full p-4 border-2 border-gray-300 rounded-lg text-center peer-checked:border-green-500 peer-checked:bg-green-50 transition-all hover:border-green-300">
                                            <div class="text-lg font-medium text-gray-900">🌡️ Celsius</div>
                                            <div class="text-sm text-gray-600">24°C</div>
                                        </div>
                                    </label>
                                    <label class="relative cursor-pointer">
                                        <input type="radio" name="temperature_unit" value="fahrenheit" {{ session('temperature_unit') === 'fahrenheit' ? 'checked' : '' }} 
                                            class="sr-only peer">
                                        <div class="w-full p-4 border-2 border-gray-300 rounded-lg text-center peer-checked:border-green-500 peer-checked:bg-green-50 transition-all hover:border-green-300">
                                            <div class="text-lg font-medium text-gray-900">🌡️ Fahrenheit</div>
                                            <div class="text-sm text-gray-600">75°F</div>
                                        </div>
                                    </label>
                                </div>
                                <p class="mt-2 text-xs text-gray-500">Temperature display for weather information</p>
                                @error('temperature_unit')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end">
                            <button type="submit" class="inline-flex items-center px-6 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 focus:ring-4 focus:ring-green-500 focus:ring-opacity-50 transition-all duration-200 shadow-lg hover:shadow-xl">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Save Display Settings
                            </button>
                        </div>
                    </form>
                </div>

                
            </div>
        </div>

        <!-- Data & Privacy Section -->
        <div class="mt-8 bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-orange-50 to-yellow-50 px-8 py-6 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    Data & Privacy
                </h2>
                <p class="text-sm text-gray-600 mt-1">Manage your data preferences and privacy settings</p>
            </div>

            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-4">Data Collection</h3>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h5 class="font-medium text-gray-900">Analytics</h5>
                                    <p class="text-sm text-gray-600">Help improve the app with usage data</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="analytics_enabled" class="sr-only peer" checked>
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-orange-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-orange-600"></div>
                                </label>
                            </div>

                            <div class="flex items-center justify-between">
                                <div>
                                    <h5 class="font-medium text-gray-900">Crash Reports</h5>
                                    <p class="text-sm text-gray-600">Automatically send error reports</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="crash_reports" class="sr-only peer" checked>
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-orange-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-orange-600"></div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="font-semibold text-gray-900 mb-4">Data Management</h3>
                        <div class="space-y-3">
                            <button class="w-full flex items-center justify-center px-4 py-3 border border-orange-300 text-orange-700 bg-orange-50 rounded-lg hover:bg-orange-100 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Export My Data
                            </button>
                            <button class="w-full flex items-center justify-center px-4 py-3 border border-red-300 text-red-700 bg-red-50 rounded-lg hover:bg-red-100 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Clear All Data
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
// Auto-save functionality
let autoSaveTimeout;

function autoSaveSettings() {
    clearTimeout(autoSaveTimeout);
    autoSaveTimeout = setTimeout(() => {
        // Get form data from both forms
        const regionalForm = document.getElementById('regional-form');
        const displayForm = document.getElementById('display-form');
        
        if (regionalForm || displayForm) {
            showSaveStatus('saving');
            
            // Simulate saving (replace with actual implementation)
            setTimeout(() => {
                showSaveStatus('saved');
                updatePreview();
            }, 1500);
        }
    }, 2000);
}

function showSaveStatus(status) {
    let statusDiv = document.getElementById('save-status');
    if (!statusDiv) {
        statusDiv = document.createElement('div');
        statusDiv.id = 'save-status';
        statusDiv.className = 'fixed top-4 right-4 px-4 py-2 rounded-lg text-sm font-medium z-50 transition-all duration-300';
        document.body.appendChild(statusDiv);
    }
    
    switch(status) {
        case 'saving':
            statusDiv.className = 'fixed top-4 right-4 px-4 py-2 rounded-lg text-sm font-medium z-50 transition-all duration-300 bg-blue-100 text-blue-800 border border-blue-200';
            statusDiv.innerHTML = `
                <div class="flex items-center">
                    <svg class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Auto-saving settings...
                </div>
            `;
            break;
       case 'saved':
            statusDiv.className = 'fixed top-4 right-4 px-4 py-2 rounded-lg text-sm font-medium z-50 transition-all duration-300 bg-green-100 text-green-800 border border-green-200';
            statusDiv.innerHTML = `
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Settings saved successfully!
                </div>
            `;
            setTimeout(() => {
                statusDiv.style.opacity = '0';
                setTimeout(() => statusDiv.remove(), 300);
            }, 2000);
            break;
        case 'error':
            statusDiv.className = 'fixed top-4 right-4 px-4 py-2 rounded-lg text-sm font-medium z-50 transition-all duration-300 bg-red-100 text-red-800 border border-red-200';
            statusDiv.innerHTML = `
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Error saving settings
                </div>
            `;
            setTimeout(() => {
                statusDiv.style.opacity = '0';
                setTimeout(() => statusDiv.remove(), 300);
            }, 3000);
            break;
    }
}

// Update preview section
function updatePreview() {
    updateTimePreview();
    updateDatePreview();
    updateCurrencyPreview();
    updateDistancePreview();
}

function updateTimePreview() {
    const timeFormat = document.querySelector('input[name="time_format"]:checked')?.value || '12h';
    const timezone = document.getElementById('timezone')?.value || 'UTC';
    const now = new Date();
    
    let timeString;
    if (timeFormat === '24h') {
        timeString = now.toLocaleTimeString('en-GB', { 
            hour: '2-digit', 
            minute: '2-digit',
            timeZone: timezone 
        });
    } else {
        timeString = now.toLocaleTimeString('en-US', { 
            hour: 'numeric', 
            minute: '2-digit',
            hour12: true,
            timeZone: timezone 
        });
    }
    
    const timeElement = document.getElementById('preview-time');
    if (timeElement) {
        timeElement.textContent = timeString;
    }
    
    const timezoneElement = document.getElementById('preview-timezone');
    if (timezoneElement) {
        const tzDisplay = timezone.split('/').pop().replace('_', ' ');
        timezoneElement.textContent = tzDisplay;
    }
}

function updateDatePreview() {
    const dateFormat = document.getElementById('date_format')?.value || 'MM/DD/YYYY';
    const now = new Date();
    
    let dateString;
    switch(dateFormat) {
        case 'DD/MM/YYYY':
            dateString = now.toLocaleDateString('en-GB');
            break;
        case 'YYYY-MM-DD':
            dateString = now.toISOString().split('T')[0];
            break;
        case 'DD MMM YYYY':
            dateString = now.toLocaleDateString('en-GB', { 
                day: '2-digit', 
                month: 'short', 
                year: 'numeric' 
            });
            break;
        case 'MMM DD, YYYY':
            dateString = now.toLocaleDateString('en-US', { 
                month: 'short', 
                day: '2-digit', 
                year: 'numeric' 
            });
            break;
        case 'DD MMMM YYYY':
            dateString = now.toLocaleDateString('en-GB', { 
                day: '2-digit', 
                month: 'long', 
                year: 'numeric' 
            });
            break;
        default:
            dateString = now.toLocaleDateString('en-US');
    }
    
    const dateElement = document.getElementById('preview-date');
    if (dateElement) {
        dateElement.textContent = dateString;
    }
    
    const formatElement = document.getElementById('preview-date-format');
    if (formatElement) {
        formatElement.textContent = dateFormat;
    }
}

function updateCurrencyPreview() {
    const currency = document.getElementById('currency')?.value || 'USD';
    const amount = 1234.56;
    
    const currencySymbols = {
        'USD': '$',
        'EUR': '€',
        'GBP': '£',
        'AUD': 'A$',
        'CAD': 'C$',
        'CHF': 'CHF ',
        'JPY': '¥',
        'CNY': '¥',
        'INR': '₹',
        'KES': 'KSh ',
        'ZAR': 'R',
        'NGN': '₦',
        'EGP': 'E£',
        'BRL': 'R$',
        'MXN': '$'
    };
    
    const symbol = currencySymbols[currency] || currency + ' ';
    const formattedAmount = symbol + amount.toLocaleString();
    
    const currencyElement = document.getElementById('preview-currency');
    if (currencyElement) {
        currencyElement.textContent = formattedAmount;
    }
    
    const codeElement = document.getElementById('preview-currency-code');
    if (codeElement) {
        codeElement.textContent = currency;
    }
}

function updateDistancePreview() {
    const distanceUnit = document.querySelector('input[name="distance_unit"]:checked')?.value || 'km';
    const distance = 25.4;
    
    let displayDistance;
    let unitName;
    
    if (distanceUnit === 'mi') {
        displayDistance = (distance * 0.621371).toFixed(1);
        unitName = 'Miles';
    } else {
        displayDistance = distance.toFixed(1);
        unitName = 'Kilometers';
    }
    
    const distanceElement = document.getElementById('preview-distance');
    if (distanceElement) {
        distanceElement.textContent = displayDistance + ' ' + distanceUnit;
    }
    
    const unitElement = document.getElementById('preview-distance-unit');
    if (unitElement) {
        unitElement.textContent = unitName;
    }
}

// Save appearance settings
function saveAppearanceSettings() {
    const theme = document.querySelector('input[name="theme"]:checked')?.value || 'light';
    const reduceMotion = document.querySelector('input[name="reduce_motion"]')?.checked || false;
    const highContrast = document.querySelector('input[name="high_contrast"]')?.checked || false;
    const largeText = document.querySelector('input[name="large_text"]')?.checked || false;
    
    // Apply theme immediately for preview
    applyTheme(theme);
    
    // Apply accessibility settings
    if (reduceMotion) {
        document.body.classList.add('reduce-motion');
    } else {
        document.body.classList.remove('reduce-motion');
    }
    
    if (highContrast) {
        document.body.classList.add('high-contrast');
    } else {
        document.body.classList.remove('high-contrast');
    }
    
    if (largeText) {
        document.body.classList.add('large-text');
    } else {
        document.body.classList.remove('large-text');
    }
    
    showSaveStatus('saving');
    
    // Simulate API call
    setTimeout(() => {
        showSaveStatus('saved');
    }, 1000);
}

function applyTheme(theme) {
    const body = document.body;
    body.classList.remove('theme-light', 'theme-dark', 'theme-auto');
    
    if (theme === 'dark') {
        body.classList.add('theme-dark');
    } else if (theme === 'auto') {
        body.classList.add('theme-auto');
        // Check system preference
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            body.classList.add('theme-dark');
        }
    } else {
        body.classList.add('theme-light');
    }
}

// Export data functionality
function exportUserData() {
    showSaveStatus('saving');
    
    // Simulate export process
    setTimeout(() => {
        const data = {
            settings: {
                language: document.getElementById('language')?.value,
                timezone: document.getElementById('timezone')?.value,
                currency: document.getElementById('currency')?.value,
                dateFormat: document.getElementById('date_format')?.value,
                timeFormat: document.querySelector('input[name="time_format"]:checked')?.value,
                distanceUnit: document.querySelector('input[name="distance_unit"]:checked')?.value,
                temperatureUnit: document.querySelector('input[name="temperature_unit"]:checked')?.value
            },
            exportDate: new Date().toISOString()
        };
        
        const blob = new Blob([JSON.stringify(data, null, 2)], { type: 'application/json' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'pangoq-settings-export.json';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
        
        showSaveStatus('saved');
    }, 2000);
}

// Clear all data functionality
function clearAllData() {
    if (confirm('Are you sure you want to clear all your data? This action cannot be undone.')) {
        if (confirm('This will reset all your settings to default values. Continue?')) {
            showSaveStatus('saving');
            
            // Reset all form values
            setTimeout(() => {
                // Reset selects to default values
                document.getElementById('language').value = 'en';
                document.getElementById('timezone').value = 'UTC';
                document.getElementById('currency').value = 'USD';
                document.getElementById('date_format').value = 'MM/DD/YYYY';
                
                // Reset radio buttons
                document.querySelector('input[name="time_format"][value="12h"]').checked = true;
                document.querySelector('input[name="distance_unit"][value="km"]').checked = true;
                document.querySelector('input[name="temperature_unit"][value="celsius"]').checked = true;
                document.querySelector('input[name="theme"][value="light"]').checked = true;
                
                // Reset checkboxes
                document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
                    checkbox.checked = false;
                });
                
                // Update preview
                updatePreview();
                applyTheme('light');
                
                showSaveStatus('saved');
            }, 1500);
        }
    }
}

// Enhanced interactions
document.addEventListener('DOMContentLoaded', function() {
    // Auto-save on input changes
    const inputs = document.querySelectorAll('input, select');
    inputs.forEach(input => {
        input.addEventListener('change', function() {
            autoSaveSettings();
            updatePreview();
        });
        
        // Enhanced focus effects
        input.addEventListener('focus', function() {
            this.classList.add('ring-4', 'ring-opacity-25');
            if (this.classList.contains('focus:ring-blue-500')) {
                this.classList.add('ring-blue-500');
            } else if (this.classList.contains('focus:ring-green-500')) {
                this.classList.add('ring-green-500');
            } else if (this.classList.contains('focus:ring-purple-500')) {
                this.classList.add('ring-purple-500');
            }
        });
        
        input.addEventListener('blur', function() {
            this.classList.remove('ring-4', 'ring-opacity-25', 'ring-blue-500', 'ring-green-500', 'ring-purple-500');
        });
    });
    
    // Initialize preview
    updatePreview();
    
    // Update time every minute
    setInterval(updateTimePreview, 60000);
    
    // Theme radio button changes
    document.querySelectorAll('input[name="theme"]').forEach(radio => {
        radio.addEventListener('change', function() {
            applyTheme(this.value);
        });
    });
    
    // Accessibility toggle changes
    document.querySelectorAll('input[name^="reduce_"], input[name^="high_"], input[name^="large_"]').forEach(toggle => {
        toggle.addEventListener('change', function() {
            const className = this.name.replace('_', '-');
            if (this.checked) {
                document.body.classList.add(className);
            } else {
                document.body.classList.remove(className);
            }
        });
    });
    
    // Enhanced card animations on load
    const cards = document.querySelectorAll('.bg-white');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'all 0.6s ease-out';
        
        setTimeout(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
    
    // Radio button card interactions
    const radioCards = document.querySelectorAll('input[type="radio"] + div');
    radioCards.forEach(card => {
        card.addEventListener('click', function() {
            const radio = this.previousElementSibling;
            if (radio && radio.type === 'radio') {
                radio.checked = true;
                radio.dispatchEvent(new Event('change'));
            }
        });
    });
    
    // Form submission enhancements
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = `
                    <svg class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Saving...
                `;
            }
        });
    });
    
    // Data management button events
    document.addEventListener('click', function(e) {
        if (e.target.textContent?.includes('Export My Data')) {
            e.preventDefault();
            exportUserData();
        } else if (e.target.textContent?.includes('Clear All Data')) {
            e.preventDefault();
            clearAllData();
        }
    });
    
    // System theme change detection
    if (window.matchMedia) {
        const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
        mediaQuery.addEventListener('change', function(e) {
            const autoTheme = document.querySelector('input[name="theme"][value="auto"]');
            if (autoTheme && autoTheme.checked) {
                applyTheme('auto');
            }
        });
    }
    
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
});

// Add custom CSS for enhanced styling
const style = document.createElement('style');
style.textContent = `
    .reduce-motion * {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }
    
    .high-contrast {
        filter: contrast(1.5);
    }
    
    .large-text {
        font-size: 1.125em;
    }
    
    .theme-dark {
        background-color: #1a202c;
        color: #e2e8f0;
    }
    
    .theme-dark .bg-white {
        background-color: #2d3748;
        color: #e2e8f0;
    }
    
    .theme-dark .text-gray-900 {
        color: #e2e8f0;
    }
    
    .theme-dark .text-gray-600 {
        color: #a0aec0;
    }
    
    .theme-dark .border-gray-200 {
        border-color: #4a5568;
    }
    
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    
    .animate-spin {
        animation: spin 1s linear infinite;
    }
    
    input:focus, select:focus {
        transition: all 0.2s ease-in-out;
    }
    
    .peer:checked ~ div {
        transition: all 0.2s ease-in-out;
    }
`;
document.head.appendChild(style);
</script>

@endsection