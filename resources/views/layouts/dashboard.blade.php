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
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen flex flex-col">
        <!-- Dashboard Navbar -->
        <nav class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <!-- Logo -->
                        <div class="flex-shrink-0 flex items-center">
                            <a href="{{ route('dashboard') }}" class="flex items-center">
                                <div class="h-8 w-8 bg-secondary-600 rounded-full"></div>
                                <span class="ml-2 text-xl font-bold text-secondary-600">pangoQ</span>
                            </a>
                        </div>

                        <!-- Navigation Links -->
                        <div class="hidden space-x-8 sm:ml-10 sm:flex">
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('dashboard') ? 'border-secondary-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium">
                                Dashboard
                            </a>
                            <a href="{{ route('trips.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('trips.*') ? 'border-secondary-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium">
                                My Trips
                            </a>
                            <a href="{{ route('trips.plan') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('trips.plan') ? 'border-secondary-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium">
                                Plan a Trip
                            </a>
                        </div>
                    </div>

                    <!-- Right Side -->
                    <div class="hidden sm:flex sm:items-center sm:ml-6">
                        <!-- Notifications Dropdown -->
                        <div class="ml-3 relative" x-data="{ open: false }">
                            <div>
                                <button @click="open = !open" class="text-gray-400 hover:text-gray-500 focus:outline-none focus:text-gray-500">
                                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                    </svg>
                                </button>
                            </div>

                            <div x-show="open" 
                                 @click.away="open = false"
                                 x-transition:enter="transition ease-out duration-100" 
                                 x-transition:enter-start="transform opacity-0 scale-95" 
                                 x-transition:enter-end="transform opacity-100 scale-100" 
                                 x-transition:leave="transition ease-in duration-75" 
                                 x-transition:leave-start="transform opacity-100 scale-100" 
                                 x-transition:leave-end="transform opacity-0 scale-95" 
                                 class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none" 
                                 role="menu" 
                                 aria-orientation="vertical" 
                                 aria-labelledby="user-menu-button" 
                                 tabindex="-1"
                                 style="display: none;">
                                <!-- Notifications go here -->
                                <a href="{{ route('notifications.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    View all notifications
                                </a>
                            </div>
                        </div>

                        <!-- Profile Dropdown -->
                        <div class="ml-3 relative" x-data="{ open: false }">
                            <div>
                                <button @click="open = !open" class="flex text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary-500" id="user-menu-button">
                                    <span class="sr-only">Open user menu</span>
                                    <div class="h-8 w-8 rounded-full bg-secondary-200 flex items-center justify-center text-secondary-700">
                                        {{ Auth::user()->initials ?? substr(Auth::user()->name, 0, 1) }}
                                    </div>
                                </button>
                            </div>

                            <div x-show="open" 
                                 @click.away="open = false"
                                 x-transition:enter="transition ease-out duration-100" 
                                 x-transition:enter-start="transform opacity-0 scale-95" 
                                 x-transition:enter-end="transform opacity-100 scale-100" 
                                 x-transition:leave="transition ease-in duration-75" 
                                 x-transition:leave-start="transform opacity-100 scale-100" 
                                 x-transition:leave-end="transform opacity-0 scale-95" 
                                 class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none" 
                                 role="menu" 
                                 aria-orientation="vertical" 
                                 aria-labelledby="user-menu-button" 
                                 tabindex="-1"
                                 style="display: none;">
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                    Your Profile
                                </a>
                                <a href="{{ route('trips.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                    My Trips
                                </a>
                                <a href="{{ route('notifications.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                    Notifications
                                </a>
                                
                                <!-- Authentication -->
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                        Log Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Mobile menu button -->
                    <div class="flex items-center sm:hidden">
                        <button type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-secondary-500" x-data="{}" @click="$dispatch('toggle-mobile-menu')">
                            <span class="sr-only">Open main menu</span>
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile menu -->
            <div x-data="{ open: false }" @toggle-mobile-menu.window="open = !open" x-show="open" class="sm:hidden" style="display: none;">
                <div class="pt-2 pb-3 space-y-1">
                    <a href="{{ route('dashboard') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('dashboard') ? 'border-secondary-500 text-secondary-700 bg-secondary-50' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800' }} text-base font-medium">
                        Dashboard
                    </a>
                    <a href="{{ route('trips.index') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('trips.*') ? 'border-secondary-500 text-secondary-700 bg-secondary-50' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800' }} text-base font-medium">
                        My Trips
                    </a>
                    <a href="{{ route('trips.plan') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('trips.plan') ? 'border-secondary-500 text-secondary-700 bg-secondary-50' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800' }} text-base font-medium">
                        Plan a Trip
                    </a>
                </div>

                <div class="pt-4 pb-3 border-t border-gray-200">
                    <div class="flex items-center px-4">
                        <div class="flex-shrink-0">
                            <div class="h-10 w-10 rounded-full bg-secondary-200 flex items-center justify-center text-secondary-700">
                                {{ Auth::user()->initials ?? substr(Auth::user()->name, 0, 1) }}
                            </div>
                        </div>
                        <div class="ml-3">
                            <div class="text-base font-medium text-gray-800">{{ Auth::user()->name }}</div>
                            <div class="text-sm font-medium text-gray-500">{{ Auth::user()->email }}</div>
                        </div>
                        <button class="ml-auto flex-shrink-0 bg-white p-1 rounded-full text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary-500">
                            <span class="sr-only">View notifications</span>
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                        </button>
                    </div>
                    <div class="mt-3 space-y-1">
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">
                            Your Profile
                        </a>
                        <a href="{{ route('trips.index') }}" class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">
                            My Trips
                        </a>
                        <a href="{{ route('notifications.index') }}" class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">
                            Notifications
                        </a>
                        
                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">
                                Log Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Header -->
        <header class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                <h1 class="text-lg font-semibold text-gray-900">
                    @yield('header', 'Dashboard')
                </h1>
            </div>
        </header>

        <!-- Page Content -->
        <main class="flex-grow">
            <div class="py-6">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    @yield('content')
                </div>
            </div>
        </main>
        
        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200 mt-auto">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <p class="text-center text-sm text-gray-500">
                    &copy; {{ date('Y') }} PangoQ. All rights reserved.
                </p>
            </div>
        </footer>
    </div>
</body>
</html>