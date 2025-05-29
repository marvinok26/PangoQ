{{-- resources/views/auth/login.blade.php --}}
@extends('layouts.auth')

@section('title', 'Sign In - PangoQ')

@section('hero-title', 'Welcome Back to Your Journey')
@section('hero-subtitle', 'Continue exploring amazing destinations and planning unforgettable experiences with PangoQ.')

@section('content')
    <div class="space-y-8">
        <!-- Header -->
        <div class="text-center">
            <h2 class="text-3xl font-bold text-gray-900 mb-2">Welcome back</h2>
            <p class="text-gray-600">Sign in to continue your travel planning journey</p>
        </div>

        <!-- Social Login Buttons -->
        <div class="space-y-4">
            <a href="{{ route('auth.redirect', 'google') }}"
                class="w-full flex items-center justify-center px-6 py-3 border-2 border-gray-200 rounded-xl shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#166fe5] transition-all duration-200 group">
                <svg class="h-5 w-5 mr-3 group-hover:scale-105 transition-transform duration-200" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <g transform="matrix(1, 0, 0, 1, 27.009001, -39.238998)">
                        <path fill="#4285F4" d="M -3.264 51.509 C -3.264 50.719 -3.334 49.969 -3.454 49.239 L -14.754 49.239 L -14.754 53.749 L -8.284 53.749 C -8.574 55.229 -9.424 56.479 -10.684 57.329 L -10.684 60.329 L -6.824 60.329 C -4.564 58.239 -3.264 55.159 -3.264 51.509 Z" />
                        <path fill="#34A853" d="M -14.754 63.239 C -11.514 63.239 -8.804 62.159 -6.824 60.329 L -10.684 57.329 C -11.764 58.049 -13.134 58.489 -14.754 58.489 C -17.884 58.489 -20.534 56.379 -21.484 53.529 L -25.464 53.529 L -25.464 56.619 C -23.494 60.539 -19.444 63.239 -14.754 63.239 Z" />
                        <path fill="#FBBC05" d="M -21.484 53.529 C -21.734 52.809 -21.864 52.039 -21.864 51.239 C -21.864 50.439 -21.724 49.669 -21.484 48.949 L -21.484 45.859 L -25.464 45.859 C -26.284 47.479 -26.754 49.299 -26.754 51.239 C -26.754 53.179 -26.284 54.999 -25.464 56.619 L -21.484 53.529 Z" />
                        <path fill="#EA4335" d="M -14.754 43.989 C -12.984 43.989 -11.404 44.599 -10.154 45.789 L -6.734 42.369 C -8.804 40.429 -11.514 39.239 -14.754 39.239 C -19.444 39.239 -23.494 41.939 -25.464 45.859 L -21.484 48.949 C -20.534 46.099 -17.884 43.989 -14.754 43.989 Z" />
                    </g>
                </svg>
                Continue with Google
            </a>

            <a href="{{ route('auth.redirect', 'facebook') }}"
                class="w-full flex items-center justify-center px-6 py-3 rounded-xl shadow-sm text-sm font-medium text-white bg-[#1877f2] hover:bg-[#166fe5] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#1877f2] transition-all duration-200 group">
                <svg class="h-5 w-5 mr-3 group-hover:scale-105 transition-transform duration-200" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" />
                </svg>
                Continue with Facebook
            </a>
        </div>

        <!-- Divider -->
        <div class="relative">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-300"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-4 bg-white text-gray-500 font-medium">Or sign in with email</span>
            </div>
        </div>

        <!-- Status Messages -->
        @if (session('status'))
            <div class="p-4 rounded-xl bg-secondary-50 border border-secondary-200">
                <div class="flex">
                    <svg class="h-5 w-5 text-secondary-600 mr-3 mt-0.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <span class="font-medium text-sm text-secondary-800">{{ session('status') }}</span>
                </div>
            </div>
        @endif

        @if(session('message') || isset($hasTripData) && $hasTripData)
            <div class="p-4 rounded-xl bg-accent-50 border border-accent-200">
                <div class="flex">
                    <svg class="h-5 w-5 text-accent-600 mr-3 mt-0.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zm-1 9a1 1 0 01-1-1v-4a1 1 0 112 0v4a1 1 0 01-1 1z" clip-rule="evenodd" />
                    </svg>
                    <span class="font-medium text-sm text-accent-800">
                        {{ session('message') ?: 'Sign in to save your trip planning progress. Your selections will be preserved after login.' }}
                    </span>
                </div>
            </div>
        @endif

        <!-- Validation Errors -->
        @if ($errors->any())
            <div class="p-4 rounded-xl bg-red-50 border border-red-200">
                <div class="flex items-start">
                    <svg class="h-5 w-5 text-red-600 mr-3 mt-0.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    <div>
                        <p class="font-medium text-sm text-red-800 mb-2">Please fix the following errors:</p>
                        <ul class="list-disc list-inside text-sm text-red-600 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <!-- Login Form -->
        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            <!-- Email Address -->
            <div class="space-y-2">
                <label for="email" class="block text-sm font-semibold text-gray-700">Email address</label>
                <div class="relative">
                    <input id="email"
                        class="appearance-none block w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#166fe5] focus:border-[#166fe5] hover:border-gray-300 transition-all duration-200 @error('email') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                        type="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        placeholder="Enter your email address" 
                        required 
                        autofocus />
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Password -->
            <div class="space-y-2">
                <div class="flex items-center justify-between">
                    <label for="password" class="block text-sm font-semibold text-gray-700">Password</label>
                    @if(Route::has('password.request'))
                        <a class="text-sm font-medium text-accent-600 hover:text-[#166fe5] hover:underline transition-colors duration-200"
                            href="{{ route('password.request') }}">
                            Forgot password?
                        </a>
                    @endif
                </div>
                <div class="relative">
                    <input id="password"
                        class="appearance-none block w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#166fe5] focus:border-[#166fe5] hover:border-gray-300 transition-all duration-200 @error('password') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                        type="password" 
                        name="password" 
                        placeholder="Enter your password" 
                        required 
                        autocomplete="current-password" />
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>
<!-- Remember Me -->
            <div class="flex items-center">
                <input id="remember_me" 
                    type="checkbox"
                    class="h-4 w-4 text-accent-600 focus:ring-[#166fe5] border-gray-300 rounded transition-colors duration-200"
                    name="remember">
                <label for="remember_me" class="ml-3 block text-sm text-gray-700 cursor-pointer">
                    Remember me for 30 days
                </label>
            </div>

            <!-- Submit Button -->
            <button type="submit"
                class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-xl shadow-lg text-sm font-semibold text-black hover:text-white bg-gradient-to-r from-accent-600 to-accent-700 hover:from-[#3a79cc] hover:to-[#161e29] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#166fe5] transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98]">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Sign in to your account
            </button>
        </form>

        <!-- Footer Links -->
        <div class="space-y-4">
            <div class="text-center">
                <p class="text-gray-600 text-sm">
                    Don't have an account?
                    <a href="{{ route('register') }}"
                        class="font-semibold text-accent-600 hover:text-[#166fe5] hover:underline transition-colors duration-200">
                        Create a free account
                    </a>
                </p>
            </div>
            
            <!-- Trust Indicators -->
            <div class="pt-6 border-t border-gray-200">
                <div class="flex items-center justify-center space-x-6 text-xs text-gray-500">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-1 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                        </svg>
                        <span>Secure Login</span>
                    </div>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-1 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>Privacy Protected</span>
                    </div>
                </div>
                
                <div class="text-center mt-4">
                    <p class="text-xs text-gray-500">
                        By signing in, you agree to our
                        <a href="{{ route('terms') }}" class="text-accent-600 hover:text-[#166fe5] hover:underline">Terms of Service</a> 
                        and 
                        <a href="{{ route('privacy') }}" class="text-accent-600 hover:text-[#166fe5] hover:underline">Privacy Policy</a>
                    </p>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="mt-8 pt-6 border-t border-gray-200">
            <div class="grid grid-cols-3 gap-4 text-center">
                <div>
                    <div class="text-2xl font-bold text-accent-600">50K+</div>
                    <div class="text-xs text-gray-500">Happy Travelers</div>
                </div>
                <div>
                    <div class="text-2xl font-bold text-secondary-600">200+</div>
                    <div class="text-xs text-gray-500">Destinations</div>
                </div>
                <div>
                    <div class="text-2xl font-bold text-yellow-500">4.9★</div>
                    <div class="text-xs text-gray-500">User Rating</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Add CSRF token to forms that don't have it
            document.querySelectorAll('form').forEach(form => {
                if (!form.querySelector('input[name="_token"]')) {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = '_token';
                    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                    input.value = token || '{{ csrf_token() }}';
                    form.appendChild(input);
                }
            });

            // Enhanced form validation with better UX
            const form = document.querySelector('form[action="{{ route('login') }}"]');
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            const submitButton = form.querySelector('button[type="submit"]');

            // Real-time validation feedback
            function validateEmail(email) {
                const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return re.test(email);
            }

            function updateFieldState(input, isValid) {
                const parent = input.parentElement;
                if (isValid) {
                    input.classList.remove('border-red-300', 'focus:border-red-500', 'focus:ring-red-500');
                    input.classList.add('border-green-300', 'focus:border-green-500', 'focus:ring-green-500');
                } else {
                    input.classList.remove('border-green-300', 'focus:border-green-500', 'focus:ring-green-500');
                    input.classList.add('border-red-300', 'focus:border-red-500', 'focus:ring-red-500');
                }
            }

            // Email validation
            emailInput.addEventListener('blur', function() {
                if (this.value && !validateEmail(this.value)) {
                    updateFieldState(this, false);
                } else if (this.value) {
                    updateFieldState(this, true);
                }
            });

            // Password validation
            passwordInput.addEventListener('input', function() {
                if (this.value.length >= 6) {
                    updateFieldState(this, true);
                } else if (this.value.length > 0) {
                    updateFieldState(this, false);
                }
            });

            // Form submission with loading state
            form.addEventListener('submit', function(e) {
                submitButton.disabled = true;
                submitButton.innerHTML = `
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Signing in...
                `;
                
                // Re-enable button after 10 seconds as fallback
                setTimeout(() => {
                    if (submitButton.disabled) {
                        submitButton.disabled = false;
                        submitButton.innerHTML = `
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Sign in to your account
                        `;
                    }
                }, 10000);
            });

            // Social login buttons hover effects
            document.querySelectorAll('a[href*="auth.redirect"]').forEach(button => {
                button.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-1px)';
                });
                
                button.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });

            // Auto-hide alerts after 5 seconds
            document.querySelectorAll('[class*="bg-red-50"], [class*="bg-green-50"], [class*="bg-blue-50"]').forEach(alert => {
                setTimeout(() => {
                    alert.style.transition = 'opacity 0.5s ease-out';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                }, 5000);
            });
        });
    </script>
@endsection