{{-- resources/views/layouts/app.blade.php --}}

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'PangoQ') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine JS -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Livewire Styles -->
    @livewireStyles
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-50">
        <!-- Navigation -->
        <header class="bg-white/95 backdrop-blur-sm shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <a href="{{ route('home') }}">
                                <img src="{{ asset('images/logo.png') }}" alt="PangoQ Logo" class="h-10 w-auto">
                            </a>
                        </div>
                        <div class="hidden md:ml-8 md:flex md:space-x-8">
                            <a href="{{ route('features') }}" class="inline-flex items-center px-1 pt-1 text-sm font-medium {{ request()->routeIs('features*') ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-700 hover:text-blue-600 hover:border-b-2 hover:border-blue-400' }}">
                                Features
                            </a>
                            <a href="{{ route('pricing') }}" class="inline-flex items-center px-1 pt-1 text-sm font-medium {{ request()->routeIs('pricing*') ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-700 hover:text-blue-600 hover:border-b-2 hover:border-blue-400' }}">
                                Pricing
                            </a>
                            <a href="{{ route('support') }}" class="inline-flex items-center px-1 pt-1 text-sm font-medium {{ request()->routeIs('support*') ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-700 hover:text-blue-600 hover:border-b-2 hover:border-blue-400' }}">
                                Support
                            </a>
                        </div>
                    </div>
                    
                    <!-- Right navigation -->
                    <div class="flex items-center">
                        @auth
                            <!-- User Dropdown -->
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 p-1">
                                    <div class="h-8 w-8 rounded-full overflow-hidden shadow-sm border border-gray-200">
                                        <img src="{{ Auth::user()->photo_url }}" alt="{{ Auth::user()->name }}" class="h-full w-full object-cover">
                                    </div>
                                    <span class="ml-2 text-sm font-medium text-gray-700">{{ Auth::user()->name }}</span>
                                    <svg class="ml-1 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                                
                                <!-- Dropdown Menu -->
                                <div x-show="open" 
                                    @click.away="open = false"
                                    x-transition:enter="transition ease-out duration-100"
                                    x-transition:enter-start="transform opacity-0 scale-95"
                                    x-transition:enter-end="transform opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-75"
                                    x-transition:leave-start="transform opacity-100 scale-100"
                                    x-transition:leave-end="transform opacity-0 scale-95"
                                    class="absolute right-0 mt-2 w-48 py-2 bg-white rounded-md shadow-lg z-50"
                                    style="display: none;">
                                    <div class="px-4 py-3">
                                        <p class="text-sm font-medium text-gray-900 truncate">{{ Auth::user()->name }}</p>
                                        <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                                    </div>
                                    <div class="border-t border-gray-100"></div>
                                    <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Dashboard</a>
                                    <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">My Profile</a>
                                    <a href="{{ route('trips.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">My Trips</a>
                                    <div class="border-t border-gray-100 my-1"></div>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            Sign Out
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="ml-4 px-4 py-2 text-sm border border-blue-600 text-blue-600 rounded-md hover:bg-blue-50">
                                Log in
                            </a>
                            <a href="{{ route('register') }}" class="ml-4 px-4 py-2 text-sm bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                Sign up
                            </a>
                        @endauth
                    </div>
                    
                    <!-- Mobile menu button -->
                    <div class="flex items-center md:hidden">
                        <button x-data="{}" @click="$dispatch('toggle-menu')" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500">
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Mobile menu -->
            <div x-data="{ open: false }" @toggle-menu.window="open = !open" x-show="open"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 transform -translate-y-2"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 transform translate-y-0"
                x-transition:leave-end="opacity-0 transform -translate-y-2"
                class="md:hidden bg-white/95 backdrop-blur-sm shadow-md"
                style="display: none;">
                <div class="px-4 pt-2 pb-3 space-y-1">
                    <a href="{{ route('features') }}" class="block px-3 py-2 text-base font-medium {{ request()->routeIs('features*') ? 'text-blue-600 bg-blue-50' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-50' }}">
                        Features
                    </a>
                    <a href="{{ route('pricing') }}" class="block px-3 py-2 text-base font-medium {{ request()->routeIs('pricing*') ? 'text-blue-600 bg-blue-50' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-50' }}">
                        Pricing
                    </a>
                    <a href="{{ route('support') }}" class="block px-3 py-2 text-base font-medium {{ request()->routeIs('support*') ? 'text-blue-600 bg-blue-50' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-50' }}">
                        Support
                    </a>
                    
                    @auth
                        <!-- User profile section in mobile menu -->
                        <div class="pt-4 pb-3 border-t border-gray-200">
                            <div class="flex items-center px-3">
                                <div class="h-10 w-10 rounded-full overflow-hidden shadow-sm border border-gray-200">
                                    <img src="{{ Auth::user()->photo_url }}" alt="{{ Auth::user()->name }}" class="h-full w-full object-cover">
                                </div>
                                <div class="ml-3">
                                    <div class="text-base font-medium text-gray-800">{{ Auth::user()->name }}</div>
                                    <div class="text-sm font-medium text-gray-500">{{ Auth::user()->email }}</div>
                                </div>
                            </div>
                            <div class="mt-3 space-y-1">
                                <a href="{{ route('dashboard') }}" class="block px-3 py-2 text-base font-medium 
                                    {{ request()->routeIs('dashboard*') ? 'text-blue-600 bg-blue-50' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-50' }}">
                                    Dashboard
                                </a>
                                <a href="{{ route('profile.show') }}" class="block px-3 py-2 text-base font-medium 
                                    {{ request()->routeIs('profile*') ? 'text-blue-600 bg-blue-50' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-50' }}">
                                    My Profile
                                </a>
                                <a href="{{ route('trips.index') }}" class="block px-3 py-2 text-base font-medium 
                                    {{ request()->routeIs('trips*') ? 'text-blue-600 bg-blue-50' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-50' }}">
                                    My Trips
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-3 py-2 text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50">
                                        Sign Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="block px-3 py-2 mt-2 text-base font-medium 
                            {{ request()->routeIs('login*') ? 'text-blue-600 bg-blue-50' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-50' }}">
                            Log in
                        </a>
                        <a href="{{ route('register') }}" class="block px-3 py-2 text-base font-medium 
                            {{ request()->routeIs('register*') ? 'text-blue-600 bg-blue-50' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-50' }}">
                            Sign up
                        </a>
                    @endauth
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main>
            @yield('content')
        </main>
        
        <!-- Footer -->
        @include('components.footer')
    </div>
    
    <!-- Toast Notifications -->
    <div
        x-data="{ show: false, message: '', type: 'success' }"
        x-on:notify.window="show = true; message = $event.detail.message; type = $event.detail.type; setTimeout(() => { show = false }, 3000)"
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform translate-y-2"
        x-transition:enter-end="opacity-100 transform translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 transform translate-y-0"
        x-transition:leave-end="opacity-0 transform translate-y-2"
        class="fixed bottom-4 right-4 z-50 px-4 py-3 rounded-lg shadow-lg"
        :class="{ 'bg-green-500 text-white': type === 'success', 'bg-red-500 text-white': type === 'error', 'bg-blue-500 text-white': type === 'info' }"
        style="display: none;"
    >
        <div class="flex items-center">
            <svg x-show="type === 'success'" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            <svg x-show="type === 'error'" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
            <svg x-show="type === 'info'" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zm-1 9a1 1 0 102 0v-5a1 1 0 10-2 0v5z" clip-rule="evenodd" />
            </svg>
            <span x-text="message"></span>
        </div>
    </div>
    
    <!-- Livewire Scripts -->
    @livewireScripts
</body>
</html>