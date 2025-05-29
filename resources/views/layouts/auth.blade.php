{{-- resources/views/layouts/auth.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'PangoQ - Travel & Events Made Easy')</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    @vite('resources/css/app.css')
    
    <!-- Scripts -->
    @vite('resources/js/app.js')
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        
        .floating-animation {
            animation: float 6s ease-in-out infinite;
        }
        
        .floating-animation-delayed {
            animation: float 6s ease-in-out infinite;
            animation-delay: -3s;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        .auth-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            background-image: 
                radial-gradient(circle at 20% 50%, rgba(120, 119, 198, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 40% 80%, rgba(120, 119, 198, 0.2) 0%, transparent 50%);
        }
    </style>
</head>
<body class="min-h-screen bg-gray-50 auth-bg">
    <!-- Floating Travel Icons -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
        <!-- Plane Icon -->
        <svg class="absolute floating-animation w-16 h-16 top-20 left-10 text-white opacity-10" fill="currentColor" viewBox="0 0 24 24">
            <path d="M21 16v-2l-8-5V3.5c0-.83-.67-1.5-1.5-1.5S10 2.67 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/>
        </svg>
        
        <!-- Map Pin Icon -->
        <svg class="absolute floating-animation-delayed w-12 h-12 top-40 right-20 text-white opacity-10" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
        </svg>
        
        <!-- Compass Icon -->
        <svg class="absolute floating-animation w-14 h-14 bottom-32 left-16 text-white opacity-10" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M12,4A8,8 0 0,1 20,12A8,8 0 0,1 12,20A8,8 0 0,1 4,12A8,8 0 0,1 12,4M12,6A6,6 0 0,0 6,12A6,6 0 0,0 12,18A6,6 0 0,0 18,12A6,6 0 0,0 12,6M14,8L18,10L16,14L10,16L8,10L14,8Z"/>
        </svg>
        
        <!-- Camera Icon -->
        <svg class="absolute floating-animation-delayed w-10 h-10 bottom-20 right-32 text-white opacity-10" fill="currentColor" viewBox="0 0 24 24">
            <path d="M4,4H7L9,2H15L17,4H20A2,2 0 0,1 22,6V18A2,2 0 0,1 20,20H4A2,2 0 0,1 2,18V6A2,2 0 0,1 4,4M12,7A5,5 0 0,0 7,12A5,5 0 0,0 12,17A5,5 0 0,0 17,12A5,5 0 0,0 12,7M12,9A3,3 0 0,1 15,12A3,3 0 0,1 12,15A3,3 0 0,1 9,12A3,3 0 0,1 12,9Z"/>
        </svg>
        
        <!-- Globe Icon -->
        <svg class="absolute floating-animation w-18 h-18 top-60 right-10 text-white opacity-10" fill="currentColor" viewBox="0 0 24 24">
            <path d="M17.9,17.39C17.64,16.59 16.89,16 16,16H15V13A1,1 0 0,0 14,12H8V10H10A1,1 0 0,0 11,9V7H13A2,2 0 0,0 15,5V4.59C17.93,5.77 20,8.64 20,12C20,14.08 19.2,15.97 17.9,17.39M11,19.93C7.05,19.44 4,16.08 4,12C4,11.38 4.08,10.78 4.21,10.21L9,15V16A2,2 0 0,0 11,18M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2Z"/>
        </svg>
    </div>

    <!-- Main Container -->
    <div class="min-h-screen flex relative z-10">
        <!-- Left Side - Branding & Info -->
        <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden">
            <!-- Background Image with Overlay -->
            <div class="absolute inset-0 bg-gradient-to-br from-purple-900 via-blue-900 to-indigo-900"></div>
            <div class="absolute inset-0 bg-black bg-opacity-20"></div>
            
            <!-- Decorative Elements -->
            <div class="absolute top-20 left-20 w-32 h-32 bg-yellow-400 rounded-full opacity-20 blur-xl"></div>
            <div class="absolute bottom-40 right-20 w-40 h-40 bg-blue-400 rounded-full opacity-20 blur-xl"></div>
            <div class="absolute top-1/2 left-10 w-24 h-24 bg-purple-400 rounded-full opacity-20 blur-xl"></div>
            
            <!-- Content -->
            <div class="relative z-10 flex flex-col justify-center px-12 text-white">
                <!-- Logo -->
                <div class="mb-12">
                    <a href="{{ route('home') }}" class="flex items-center group">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 mr-4">
                                <img src="{{ asset('images/logo.png') }}" alt="PangoQ Logo" class="h-12 w-auto group-hover:scale-105 transition-transform duration-200" 
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <!-- Fallback logo if image fails to load -->
                                <div class="w-14 h-14 bg-gradient-to-br from-yellow-400 to-yellow-500 rounded-2xl items-center justify-center shadow-lg hidden">
                                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                
                <!-- Hero Content -->
                <div class="max-w-lg">
                    <h1 class="text-5xl font-bold mb-6 leading-tight">
                        @yield('hero-title', 'Discover Amazing Destinations')
                    </h1>
                    <p class="text-xl text-blue-100 mb-12 leading-relaxed">
                        @yield('hero-subtitle', 'Join thousands of travelers who trust PangoQ to plan their perfect adventures around the world.')
                    </p>
                    
                    <!-- Features -->
                    <div class="space-y-6">
                        <div class="flex items-center group">
                            <div class="w-12 h-12 bg-yellow-500 bg-opacity-20 rounded-full flex items-center justify-center mr-4 group-hover:bg-opacity-30 transition-colors duration-200">
                                <svg class="w-6 h-6 text-yellow-300" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                </svg>
                            </div>
                            <span class="text-lg text-blue-100">Curated travel experiences</span>
                        </div>
                        <div class="flex items-center group">
                            <div class="w-12 h-12 bg-blue-500 bg-opacity-20 rounded-full flex items-center justify-center mr-4 group-hover:bg-opacity-30 transition-colors duration-200">
                                <svg class="w-6 h-6 text-blue-300" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12,1L9,9L1,12L9,15L12,23L15,15L23,12L15,9L12,1Z"/>
                                </svg>
                            </div>
                            <span class="text-lg text-blue-100">Exclusive event access</span>
                        </div>
                        <div class="flex items-center group">
                            <div class="w-12 h-12 bg-green-500 bg-opacity-20 rounded-full flex items-center justify-center mr-4 group-hover:bg-opacity-30 transition-colors duration-200">
                                <svg class="w-6 h-6 text-green-300" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12,2A10,10 0 0,1 22,12A10,10 0 0,1 12,22A10,10 0 0,1 2,12A10,10 0 0,1 12,2M11,16.5L18,9.5L16.59,8.09L11,13.67L7.91,10.59L6.5,12L11,16.5Z"/>
                                </svg>
                            </div>
                            <span class="text-lg text-blue-100">24/7 travel support</span>
                        </div>
                    </div>
                    
                    <!-- Testimonial -->
                    <div class="mt-16 p-6 bg-white bg-opacity-10 backdrop-blur-sm rounded-2xl border border-white border-opacity-20">
                        <div class="flex items-center mb-4">
                            <div class="flex space-x-1">
                                @for($i = 0; $i < 5; $i++)
                                    <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                    </svg>
                                @endfor
                            </div>
                            <span class="ml-3 text-sm text-blue-200">4.9/5 from 10k+ travelers</span>
                        </div>
                        <p class="text-gray-500 italic">"PangoQ made our dream vacation a reality. The planning was seamless and the experiences were unforgettable!"</p>
                        <p class="text-sm text-blue-200 mt-2">- Sarah & Mike, Adventure Travelers</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Side - Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-white bg-opacity-95 backdrop-blur-sm">
            <div class="w-full max-w-md">
                @yield('content')
            </div>
        </div>
    </div>
    
    <!-- Back to Home Link -->
    <div class="fixed top-6 right-6 z-20">
        <a href="{{ route('home') }}" class="inline-flex items-center px-4 py-2 bg-white bg-opacity-20 backdrop-blur-sm text-white rounded-full hover:bg-opacity-30 transition-all duration-200 border border-white border-opacity-20">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Home
        </a>
    </div>
</body>
</html>