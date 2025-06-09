{{-- resources/views/layouts/dashboard.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Dashboard - PangoQ')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Livewire Styles -->
    @livewireStyles
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="h-screen flex flex-col overflow-hidden">
        <!-- Top Navigation - Fixed -->
        <header class="bg-white/95 backdrop-blur-sm border-b border-gray-200 shadow-sm flex-shrink-0 z-30">
            <div class="px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <!-- Logo and Mobile Menu Button -->
                    <div class="flex items-center">
                        <!-- Mobile menu button -->
                        <button class="lg:hidden -ml-2 mr-2 p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 transition-colors" 
                                x-data="{}" @click="$dispatch('toggle-sidebar')">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>
                        
                        <div class="flex items-center">
                <div class="flex-shrink-0">
                    <a href="/">
                        <img src="{{ asset('images/logo.png') }}" alt="PangoQ Logo" class="h-10 w-auto">
                    </a>
                </div>
            </div>
                    </div>
                    
                    <!-- Right side items -->
                    <div class="flex items-center space-x-4">
                        <!-- Notifications -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="p-2 rounded-lg text-gray-400 hover:text-gray-500 hover:bg-gray-100 relative transition-colors">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                </svg>
                                <span class="absolute top-1 right-1 block h-2 w-2 rounded-full bg-red-500"></span>
                            </button>
                            
                            <!-- Notifications Dropdown -->
                            <div x-show="open" @click.away="open = false" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 transform scale-95"
                                 x-transition:enter-end="opacity-100 transform scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="opacity-100 transform scale-100"
                                 x-transition:leave-end="opacity-0 transform scale-95"
                                 class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-lg ring-1 ring-black ring-opacity-5 z-50"
                                 style="display: none;">
                                <div class="p-4">
                                    <div class="flex items-center justify-between mb-4">
                                        <h3 class="text-sm font-medium text-gray-900">Notifications</h3>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">3</span>
                                    </div>
                                    <div class="space-y-3">
                                        <div class="flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-50 cursor-pointer">
                                            <div class="flex-shrink-0">
                                                <div class="h-8 w-8 bg-blue-100 rounded-full flex items-center justify-center">
                                                    <svg class="h-4 w-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900">Payment received</p>
                                                <p class="text-xs text-gray-500">John contributed $200 to Tokyo Trip</p>
                                                <p class="text-xs text-gray-400 mt-1">2 hours ago</p>
                                            </div>
                                        </div>
                                        <div class="flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-50 cursor-pointer">
                                            <div class="flex-shrink-0">
                                                <div class="h-8 w-8 bg-green-100 rounded-full flex items-center justify-center">
                                                    <svg class="h-4 w-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900">Trip fully funded!</p>
                                                <p class="text-xs text-gray-500">Your Paris Adventure is ready to book</p>
                                                <p class="text-xs text-gray-400 mt-1">1 day ago</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-4 pt-4 border-t border-gray-100">
                                        <a href="#" class="block text-sm font-medium text-blue-600 hover:text-blue-500 text-center">
                                            View all notifications
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- User Dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center text-sm rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 p-1">
                                <div class="flex items-center space-x-3">
                                    <div class="h-8 w-8 rounded-full overflow-hidden ring-2 ring-gray-100">
                                        <img src="{{ Auth::user()->photo_url ?? 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80' }}" 
                                             alt="{{ Auth::user()->name ?? 'User' }}" 
                                             class="h-full w-full object-cover">
                                    </div>
                                    <div class="hidden md:block">
                                        <span class="text-sm font-medium text-gray-700">{{ Auth::user()->name ?? 'John Doe' }}</span>
                                        <svg class="ml-2 h-4 w-4 text-gray-400 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </div>
                                </div>
                            </button>
                            
                            <!-- User Dropdown Menu -->
                            <div x-show="open" @click.away="open = false"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 transform scale-95"
                                 x-transition:enter-end="opacity-100 transform scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="opacity-100 transform scale-100"
                                 x-transition:leave-end="opacity-0 transform scale-95"
                                 class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg ring-1 ring-black ring-opacity-5 z-50"
                                 style="display: none;">
                                <div class="p-3">
                                    <div class="flex items-center space-x-3 p-3 rounded-lg">
                                        <div class="h-10 w-10 rounded-full overflow-hidden">
                                            <img src="{{ Auth::user()->photo_url ?? 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80' }}" 
                                                 alt="{{ Auth::user()->name ?? 'User' }}" 
                                                 class="h-full w-full object-cover">
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate">{{ Auth::user()->name ?? 'John Doe' }}</p>
                                            <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email ?? 'john@example.com' }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="border-t border-gray-100 py-1">
                                    <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 rounded-lg mx-2">
                                        <svg class="mr-3 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"/>
                                        </svg>
                                        Dashboard
                                    </a>
                                    <a href="{{ route('profile.show') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 rounded-lg mx-2">
                                        <svg class="mr-3 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        My Profile
                                    </a>
                                    <a href="{{ route('settings.general') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 rounded-lg mx-2">
                                        <svg class="mr-3 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        Settings
                                    </a>
                                </div>
                                <div class="border-t border-gray-100 py-1">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="flex items-center w-full px-4 py-3 text-sm text-red-700 hover:bg-red-50 rounded-lg mx-2">
                                            <svg class="mr-3 h-4 w-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                            </svg>
                                            Sign Out
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Layout Container -->
        <div class="flex flex-1 overflow-hidden">
            <!-- Sidebar - Fixed Height -->
            <div class="hidden lg:flex lg:flex-shrink-0">
                <div class="flex flex-col w-64 bg-white border-r border-gray-200 h-full">
                    <!-- Sidebar Content - Scrollable -->
                    <div class="flex flex-col flex-1 min-h-0">
                        <div class="flex-1 flex flex-col pt-6 pb-4 overflow-y-auto">
                            <nav class="mt-2 flex-1 px-4 space-y-2">
                                <a href="{{ route('dashboard') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-lg' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                    <svg class="mr-3 h-5 w-5 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"/>
                                    </svg>
                                    Dashboard
                                </a>
                                
                                <a href="{{ route('trips.index') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('trips.index') ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-lg' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                    <svg class="mr-3 h-5 w-5 {{ request()->routeIs('trips.index') ? 'text-white' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    My Trips
                                </a>
                                
                                <a href="{{ route('wallet.index') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('wallet.*') ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-lg' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                    <svg class="mr-3 h-5 w-5 {{ request()->routeIs('wallet.*') ? 'text-white' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                    </svg>
                                    Savings Wallet
                                </a>
                                
                                <a href="{{ route('trips.plan') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('trips.plan') ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-lg' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                    <svg class="mr-3 h-5 w-5 {{ request()->routeIs('trips.plan') ? 'text-white' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                    Plan a Trip
                                </a>
                                
                                <!-- Divider -->
                                <div class="border-t border-gray-200 my-4"></div>
                                
                                <a href="{{ route('profile.show') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('profile.*') ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-lg' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                    <svg class="mr-3 h-5 w-5 {{ request()->routeIs('profile.*') ? 'text-white' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    Profile
                                </a>
                                
                                <a href="{{ route('settings.general') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('settings.*') ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-lg' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                    <svg class="mr-3 h-5 w-5 {{ request()->routeIs('settings.*') ? 'text-white' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    Settings
                                </a>
                            </nav>
                        </div>
                        
                        <!-- Support section at bottom of sidebar -->
                        <div class="flex-shrink-0 p-4">
                            <div class="bg-gradient-to-br from-blue-50 to-indigo-100 rounded-xl p-4">
                                <h4 class="text-sm font-medium text-gray-900 mb-2">Need Help?</h4>
                                <p class="text-xs text-gray-600 mb-3">Get support from our team</p>
                                <a href="#" class="inline-flex items-center text-xs font-medium text-blue-600 hover:text-blue-500">
                                    Contact Support
                                    <svg class="ml-1 h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Area - Scrollable -->
            <main class="flex-1 relative overflow-y-auto focus:outline-none">
                @yield('content')
            </main>
        </div>
    </div>
    
   <!-- Mobile Sidebar Overlay -->
    <div x-data="{ open: false }" x-on:keydown.escape.window="open = false" @toggle-sidebar.window="open = !open">
        <!-- Mobile sidebar overlay -->
        <div x-show="open" 
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-40 lg:hidden"
             style="display: none;">
            <div class="fixed inset-0 bg-gray-600 bg-opacity-75" @click="open = false"></div>
            
            <!-- Mobile sidebar -->
            <div x-show="open"
                 x-transition:enter="transition ease-in-out duration-300 transform"
                 x-transition:enter-start="-translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transition ease-in-out duration-300 transform"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="-translate-x-full"
                 class="relative flex-1 flex flex-col max-w-xs w-full bg-white h-full">
                
                <!-- Close button -->
                <div class="absolute top-0 right-0 -mr-12 pt-4">
                    <button @click="open = false" class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                
                <!-- Mobile sidebar content -->
                <div class="flex-1 h-0 pt-5 pb-4 overflow-y-auto">
                    <!-- Logo -->
                    <div class="flex-shrink-0 flex items-center px-4 mb-8">
                        <div class="h-8 w-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-sm">P</span>
                        </div>
                        <span class="ml-3 font-bold text-xl text-gray-900">PangoQ</span>
                    </div>
                    
                    <!-- Mobile Navigation -->
                    <nav class="px-2 space-y-1">
                        <a href="{{ route('dashboard') }}" class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('dashboard') ? 'bg-blue-100 text-blue-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <svg class="mr-4 h-6 w-6 {{ request()->routeIs('dashboard') ? 'text-blue-500' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"/>
                            </svg>
                            Dashboard
                        </a>
                        
                        <a href="{{ route('trips.index') }}" class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('trips.index') ? 'bg-blue-100 text-blue-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <svg class="mr-4 h-6 w-6 {{ request()->routeIs('trips.index') ? 'text-blue-500' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            My Trips
                        </a>
                        
                        <a href="{{ route('wallet.index') }}" class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('wallet.*') ? 'bg-blue-100 text-blue-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <svg class="mr-4 h-6 w-6 {{ request()->routeIs('wallet.*') ? 'text-blue-500' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                            Savings Wallet
                        </a>
                        
                        <a href="{{ route('trips.plan') }}" class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('trips.plan') ? 'bg-blue-100 text-blue-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <svg class="mr-4 h-6 w-6 {{ request()->routeIs('trips.plan') ? 'text-blue-500' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Plan a Trip
                        </a>
                        
                        <!-- Divider -->
                        <div class="border-t border-gray-200 my-4"></div>
                        
                        <a href="{{ route('profile.show') }}" class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('profile.*') ? 'bg-blue-100 text-blue-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <svg class="mr-4 h-6 w-6 {{ request()->routeIs('profile.*') ? 'text-blue-500' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Profile
                        </a>
                        
                        <a href="{{ route('settings.general') }}" class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('settings.*') ? 'bg-blue-100 text-blue-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <svg class="mr-4 h-6 w-6 {{ request()->routeIs('settings.*') ? 'text-blue-500' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Settings
                        </a>
                    </nav>
                </div>
                
                <!-- Mobile user info at bottom -->
                <div class="flex-shrink-0 flex border-t border-gray-200 p-4">
                    <div class="flex items-center w-full">
                        <div class="h-10 w-10 rounded-full overflow-hidden">
                            <img src="{{ Auth::user()->photo_url ?? 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80' }}" 
                                 alt="{{ Auth::user()->name ?? 'User' }}" 
                                 class="h-full w-full object-cover">
                        </div>
                        <div class="ml-3 flex-1">
                            <p class="text-base font-medium text-gray-700">{{ Auth::user()->name ?? 'John Doe' }}</p>
                            <p class="text-sm font-medium text-gray-500">{{ Auth::user()->email ?? 'john@example.com' }}</p>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="ml-2 p-2 text-gray-400 hover:text-red-500 transition-colors">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Toast Notifications -->
    <div x-data="{ show: false, message: '', type: 'success' }"
         x-on:notify.window="show = true; message = $event.detail.message; type = $event.detail.type; setTimeout(() => { show = false }, 4000)"
         x-show="show"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-2 sm:translate-y-0 sm:translate-x-2"
         x-transition:enter-end="opacity-100 transform translate-y-0 sm:translate-x-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform translate-y-0 sm:translate-x-0"
         x-transition:leave-end="opacity-0 transform translate-y-2 sm:translate-y-0 sm:translate-x-2"
         class="fixed bottom-4 right-4 z-50 px-6 py-4 rounded-xl shadow-lg backdrop-blur-sm max-w-sm"
         :class="{ 
             'bg-green-500/90 text-white': type === 'success', 
             'bg-red-500/90 text-white': type === 'error', 
             'bg-blue-500/90 text-white': type === 'info',
             'bg-yellow-500/90 text-white': type === 'warning'
         }"
         style="display: none;">
        <div class="flex items-center">
            <svg x-show="type === 'success'" class="h-5 w-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <svg x-show="type === 'error'" class="h-5 w-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
            </svg>
            <svg x-show="type === 'info'" class="h-5 w-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <svg x-show="type === 'warning'" class="h-5 w-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
            </svg>
            <div class="flex-1">
                <p x-text="message" class="text-sm font-medium"></p>
            </div>
            <button @click="show = false" class="ml-4 flex-shrink-0">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>
    
    <!-- Livewire Scripts -->
    @livewireScripts
    
    <!-- Enhanced JavaScript for dashboard functionality -->
    <script>
        // Enhanced notification system
        window.notify = function(message, type = 'success') {
            window.dispatchEvent(new CustomEvent('notify', {
                detail: { message, type }
            }));
        };
        
        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl/Cmd + K for search
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                const searchInput = document.querySelector('input[type="text"]');
                if (searchInput) {
                    searchInput.focus();
                }
            }
            
            // Alt + D for dashboard
            if (e.altKey && e.key === 'd') {
                e.preventDefault();
                window.location.href = '{{ route("dashboard") }}';
            }
            
            // Alt + T for trips
            if (e.altKey && e.key === 't') {
                e.preventDefault();
                window.location.href = '{{ route("trips.index") }}';
            }
            
            // Alt + W for wallet
            if (e.altKey && e.key === 'w') {
                e.preventDefault();
                window.location.href = '{{ route("wallet.index") }}';
            }
            
            // Alt + N for new trip
            if (e.altKey && e.key === 'n') {
                e.preventDefault();
                window.location.href = '{{ route("trips.plan") }}';
            }
        });
        
        // Auto-close mobile menu on route change
        document.addEventListener('livewire:navigated', function() {
            // Close mobile sidebar if open
            const mobileSidebarEvent = new KeyboardEvent('keydown', { key: 'Escape' });
            window.dispatchEvent(mobileSidebarEvent);
        });
        
        // Smooth scroll behavior for sidebar
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.querySelector('.lg\\:flex .overflow-y-auto');
            if (sidebar) {
                let ticking = false;
                
                function updateScrollShadow() {
                    const scrollTop = sidebar.scrollTop;
                    const scrollHeight = sidebar.scrollHeight;
                    const clientHeight = sidebar.clientHeight;
                    
                    // Add shadow at top when scrolled
                    sidebar.style.boxShadow = scrollTop > 0 
                        ? 'inset 0 7px 9px -7px rgba(0,0,0,0.1)' 
                        : 'none';
                    
                    // Add shadow at bottom when not at bottom
                    const isAtBottom = scrollTop + clientHeight >= scrollHeight - 5;
                    if (!isAtBottom && scrollHeight > clientHeight) {
                        sidebar.style.boxShadow += ', inset 0 -7px 9px -7px rgba(0,0,0,0.1)';
                    }
                    
                    ticking = false;
                }
                
                sidebar.addEventListener('scroll', function() {
                    if (!ticking) {
                        requestAnimationFrame(updateScrollShadow);
                        ticking = true;
                    }
                });
                
                // Initial call
                updateScrollShadow();
            }
        });
        
        // Prevent body scroll when mobile sidebar is open
        let isBodyScrollDisabled = false;
        
        window.addEventListener('toggle-sidebar', function() {
            if (isBodyScrollDisabled) {
                document.body.style.overflow = '';
                isBodyScrollDisabled = false;
            } else {
                document.body.style.overflow = 'hidden';
                isBodyScrollDisabled = true;
            }
        });
        
        // Re-enable body scroll when closing sidebar with escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && isBodyScrollDisabled) {
                document.body.style.overflow = '';
                isBodyScrollDisabled = false;
            }
        });
        
        // Handle window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 1024 && isBodyScrollDisabled) {
                document.body.style.overflow = '';
                isBodyScrollDisabled = false;
            }
        });
    </script>
</body>
</html>