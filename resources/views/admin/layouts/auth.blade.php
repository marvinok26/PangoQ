{{-- resources/views/admin/layouts/auth.blade.php --}}

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Admin Login') - {{ config('app.name', 'Laravel') }}</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        
        /* Animated gradient background */
        .gradient-bg {
            background: linear-gradient(-45deg, #667eea, #764ba2, #f093fb, #f5576c, #4facfe);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
        }
        
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        /* Floating animation */
        .float {
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(2deg); }
        }
        
        /* Particle animation */
        .particle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            animation: particle 20s linear infinite;
        }
        
        @keyframes particle {
            0% {
                transform: translateY(100vh) rotate(0deg);
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                transform: translateY(-100vh) rotate(360deg);
                opacity: 0;
            }
        }
        
        /* Glass morphism effect */
        .glass {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .glass-dark {
            background: rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        /* Custom shadows */
        .shadow-glow {
            box-shadow: 0 0 40px rgba(255, 255, 255, 0.1);
        }
        
        /* Typing animation */
        .typing {
            overflow: hidden;
            border-right: 2px solid rgba(255, 255, 255, 0.7);
            white-space: nowrap;
            animation: typing 3.5s steps(40, end), blink-caret 0.75s step-end infinite;
        }
        
        @keyframes typing {
            from { width: 0; }
            to { width: 100%; }
        }
        
        @keyframes blink-caret {
            from, to { border-color: transparent; }
            50% { border-color: rgba(255, 255, 255, 0.7); }
        }
    </style>
</head>
<body class="gradient-bg min-h-screen overflow-hidden relative">
    <!-- Animated particles -->
    <div class="particle" style="left: 10%; width: 4px; height: 4px; animation-delay: 0s;"></div>
    <div class="particle" style="left: 20%; width: 6px; height: 6px; animation-delay: 2s;"></div>
    <div class="particle" style="left: 30%; width: 3px; height: 3px; animation-delay: 4s;"></div>
    <div class="particle" style="left: 40%; width: 5px; height: 5px; animation-delay: 6s;"></div>
    <div class="particle" style="left: 50%; width: 4px; height: 4px; animation-delay: 8s;"></div>
    <div class="particle" style="left: 60%; width: 6px; height: 6px; animation-delay: 10s;"></div>
    <div class="particle" style="left: 70%; width: 3px; height: 3px; animation-delay: 12s;"></div>
    <div class="particle" style="left: 80%; width: 5px; height: 5px; animation-delay: 14s;"></div>
    <div class="particle" style="left: 90%; width: 4px; height: 4px; animation-delay: 16s;"></div>

    <div class="min-h-screen flex">
        <!-- Left Panel - Branding & Info -->
        <div class="hidden lg:flex lg:w-1/2 xl:w-3/5 relative overflow-hidden">
            <!-- Background overlay -->
            <div class="absolute inset-0 bg-black/20"></div>
            
            <!-- Content -->
            <div class="relative z-10 flex flex-col justify-center px-12 xl:px-20">
                <!-- Logo & Brand -->
                <div class="mb-12">
                    <div class="flex items-center mb-6">
                        <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center mr-4 float">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-3xl xl:text-4xl font-bold text-white">{{ config('app.name', 'Admin Panel') }}</h1>
                            <p class="text-white/80 text-lg">Secure Administrative Portal</p>
                        </div>
                    </div>
                </div>

                <!-- Feature highlights -->
                <div class="space-y-8">
                    <div class="glass rounded-2xl p-6 shadow-glow">
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-blue-500/30 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-white mb-2">Advanced Security</h3>
                                <p class="text-white/70">Multi-layered protection with real-time threat monitoring and encrypted communications.</p>
                            </div>
                        </div>
                    </div>

                    <div class="glass rounded-2xl p-6 shadow-glow">
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-purple-500/30 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-white mb-2">Real-time Analytics</h3>
                                <p class="text-white/70">Comprehensive dashboard with live metrics, user insights, and performance tracking.</p>
                            </div>
                        </div>
                    </div>

                    <div class="glass rounded-2xl p-6 shadow-glow">
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-emerald-500/30 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-white mb-2">Lightning Fast</h3>
                                <p class="text-white/70">Optimized performance with instant load times and seamless user experience.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stats or additional info -->
                <div class="mt-12 pt-8 border-t border-white/20">
                    <div class="grid grid-cols-3 gap-8">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-white">99.9%</div>
                            <div class="text-white/70 text-sm">Uptime</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-white">24/7</div>
                            <div class="text-white/70 text-sm">Support</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-white">256-bit</div>
                            <div class="text-white/70 text-sm">Encryption</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Panel - Login Form -->
        <div class="w-full lg:w-1/2 xl:w-2/5 flex items-center justify-center p-8">
            <div class="w-full max-w-md">
                <!-- Mobile Logo (visible only on small screens) -->
                <div class="lg:hidden text-center mb-8">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-white/10 backdrop-blur-sm rounded-3xl mb-4 float">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <h1 class="text-2xl font-bold text-white">{{ config('app.name', 'Admin Panel') }}</h1>
                </div>

                <!-- Login Card -->
                <div class="glass-dark rounded-3xl p-8 shadow-glow">
                    <!-- Header -->
                    <div class="text-center mb-8">
                        <h2 class="text-3xl font-bold text-white mb-2">@yield('auth-title', 'Welcome Back')</h2>
                        <p class="text-white/70">Please sign in to your admin account</p>
                    </div>

                    <!-- Form Content -->
                    @yield('content')
                </div>

                <!-- Footer -->
                <div class="mt-8 text-center">
                    <p class="text-white/50 text-sm">
                        © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Success/Error Toast Container -->
    <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

    <script>
        // Add some interactive elements
        document.addEventListener('DOMContentLoaded', function() {
            // Add subtle mouse movement effect
            document.addEventListener('mousemove', function(e) {
                const particles = document.querySelectorAll('.particle');
                const x = e.clientX / window.innerWidth;
                const y = e.clientY / window.innerHeight;
                
                particles.forEach((particle, index) => {
                    const speed = (index + 1) * 0.5;
                    particle.style.transform += ` translate(${x * speed}px, ${y * speed}px)`;
                });
            });

            // Add typing effect to welcome message on desktop
            if (window.innerWidth >= 1024) {
                const welcomeText = document.querySelector('h2');
                if (welcomeText) {
                    welcomeText.classList.add('typing');
                }
            }
        });

        // Toast notification function
        function showToast(message, type = 'info') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            
            const bgColor = type === 'error' ? 'bg-red-500' : type === 'success' ? 'bg-green-500' : 'bg-blue-500';
            
            toast.className = `${bgColor} text-white px-6 py-3 rounded-lg shadow-lg transform transition-all duration-300 translate-x-full opacity-0`;
            toast.textContent = message;
            
            container.appendChild(toast);
            
            // Animate in
            setTimeout(() => {
                toast.classList.remove('translate-x-full', 'opacity-0');
            }, 100);
            
            // Animate out
            setTimeout(() => {
                toast.classList.add('translate-x-full', 'opacity-0');
                setTimeout(() => container.removeChild(toast), 300);
            }, 5000);
        }
    </script>
</body>
</html>