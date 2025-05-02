{{-- resources/views/layouts/page.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'PangoQ - Group Travel Planning Made Easy')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="font-sans antialiased">
    {{-- Colored header with transparent background for the hero section --}}
    @if(View::hasSection('hero'))
        @include('components.navbar')
        <div class="relative">
            @yield('hero')
        </div>
    @else
        {{-- Default navbar with white background for inner pages --}}
        <div class="bg-white shadow-sm">
            <div x-data="{ transparentNav: false }" x-init="transparentNav = false" 
                x-on:scroll.window="transparentNav = window.scrollY > 50">
                <div :class="{'bg-white shadow-md': !transparentNav, 'bg-white shadow': transparentNav}" 
                    class="transition-all duration-300">
                    @include('components.navbar')
                </div>
            </div>
        </div>
        
        {{-- Default page header for inner pages --}}
        <div class="bg-gray-50 py-16 md:py-24">
            <div class="container mx-auto px-4 lg:px-8">
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900">@yield('page-title', 'Page Title')</h1>
                @if(View::hasSection('page-subtitle'))
                    <p class="mt-2 text-lg text-gray-600">@yield('page-subtitle')</p>
                @endif
            </div>
        </div>
    @endif
    
    {{-- Main content --}}
    <main class="@if(!View::hasSection('hero')) py-12 @endif">
        @yield('content')
    </main>
    
    {{-- Footer --}}
    @include('components.footer')
    
    {{-- Login Popup for guests --}}
    @guest
        @if(!Request::is('login') && !Request::is('register') && !Request::is('forgot-password') && !Request::is('reset-password*'))
            @include('components.login-popup')
        @endif
    @endguest
    
    @stack('scripts')
    <script src="{{ asset('js/trip-planning-steps.js') }}" defer></script>
</body>
</html>