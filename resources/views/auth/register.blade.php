{{-- resources/views/auth/register.blade.php --}}
@extends('layouts.auth')

@section('title', 'Join PangoQ - Create Account')

@section('hero-title', 'Start Your Adventure Today')
@section('hero-subtitle', 'Join thousands of travelers who discover amazing destinations and create unforgettable memories with PangoQ.')

@section('content')
    <div class="space-y-8">
        <!-- Header -->
        <div class="text-center">
            <h2 class="text-3xl font-bold text-gray-900 mb-2">Create your account</h2>
            <p class="text-gray-600">Join thousands of travel enthusiasts worldwide</p>
        </div>

        <!-- Social Registration Buttons -->
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
                Sign up with Google
            </a>

            <a href="{{ route('auth.redirect', 'facebook') }}"
                class="w-full flex items-center justify-center px-6 py-3 rounded-xl shadow-sm text-sm font-medium text-white bg-[#1877f2] hover:bg-[#166fe5] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#1877f2] transition-all duration-200 group">
                <svg class="h-5 w-5 mr-3 group-hover:scale-105 transition-transform duration-200" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" />
                </svg>
                Sign up with Facebook
            </a>
        </div>

        <!-- Divider -->
        <div class="relative">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-300"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-4 bg-white text-gray-500 font-medium">Or create account with email</span>
            </div>
        </div>

        <!-- Status Messages -->
        @if(session('message') || isset($hasTripData) && $hasTripData)
            <div class="p-4 rounded-xl bg-accent-50 border border-accent-200">
                <div class="flex">
                    <svg class="h-5 w-5 text-accent-600 mr-3 mt-0.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zm-1 9a1 1 0 01-1-1v-4a1 1 0 112 0v4a1 1 0 01-1 1z" clip-rule="evenodd" />
                    </svg>
                    <span class="font-medium text-sm text-accent-800">
                        {{ session('message') ?: 'Create an account to save your trip planning progress. Your selections will be preserved after registration.' }}
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

        <!-- Registration Form -->
        <form method="POST" action="{{ route('register') }}" class="space-y-6">
            @csrf

            <!-- Name Field -->
            <div class="space-y-2">
                <label for="name" class="block text-sm font-semibold text-gray-700">Full Name</label>
                <div class="relative">
                    <input id="name"
                        class="appearance-none block w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#166fe5] focus:border-[#166fe5] hover:border-gray-300 transition-all duration-200 @error('name') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                        type="text" 
                        name="name" 
                        value="{{ old('name') }}" 
                        placeholder="Enter your full name" 
                        required 
                        autofocus />
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Email Field -->
            <div class="space-y-2">
                <label for="email" class="block text-sm font-semibold text-gray-700">Email address</label>
                <div class="relative">
                    <input id="email"
                        class="appearance-none block w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#166fe5] focus:border-[#166fe5] hover:border-gray-300 transition-all duration-200 @error('email') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                        type="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        placeholder="Enter your email address"
                        required />
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Password Field -->
            <div class="space-y-2">
                <label for="password" class="block text-sm font-semibold text-gray-700">Password</label>
                <div class="relative">
                    <input id="password"
                        class="appearance-none block w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#166fe5] focus:border-[#166fe5] hover:border-gray-300 transition-all duration-200 @error('password') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                        type="password" 
                        name="password" 
                        placeholder="Create a strong password" 
                        required 
                        autocomplete="new-password" />
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
                <!-- Password Strength Indicator -->
                <div id="password-strength" class="hidden mt-2">
                    <div class="flex space-x-1">
                        <div class="flex-1 h-1 rounded-full bg-gray-200">
                            <div id="strength-bar" class="h-1 rounded-full transition-all duration-300"></div>
                        </div>
                    </div>
                    <p id="strength-text" class="text-xs mt-1"></p>
                </div>
            </div>

            <!-- Confirm Password Field -->
            <div class="space-y-2">
                <label for="password_confirmation" class="block text-sm font-semibold text-gray-700">Confirm Password</label>
                <div class="relative">
                    <input id="password_confirmation"
                        class="appearance-none block w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#166fe5] focus:border-[#166fe5] hover:border-gray-300 transition-all duration-200 @error('password_confirmation') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                        type="password" 
                        name="password_confirmation" 
                        placeholder="Confirm your password" 
                        required />
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Terms Agreement -->
            <div class="flex items-start">
                <input id="agree_terms" 
                    type="checkbox"
                    class="mt-1 h-4 w-4 text-accent-600 focus:ring-[#166fe5] border-gray-300 rounded transition-colors duration-200"
                    name="agree_terms"
                    required>
                <label for="agree_terms" class="ml-3 text-sm text-gray-700 cursor-pointer">
                    I agree to the 
                    <a href="{{ route('pages.terms') }}" class="font-medium text-accent-600 hover:text-[#166fe5] hover:underline transition-colors duration-200" target="_blank">Terms of Service</a>
                    and 
                    <a href="{{ route('pages.privacy') }}" class="font-medium text-accent-600 hover:text-[#166fe5] hover:underline transition-colors duration-200" target="_blank">Privacy Policy</a>
                </label>
            </div>

            <!-- Marketing Consent -->
            <div class="flex items-start">
                <input id="marketing_consent" 
                    type="checkbox"
                    class="mt-1 h-4 w-4 text-accent-600 focus:ring-[#166fe5] border-gray-300 rounded transition-colors duration-200"
                    name="marketing_consent">
                <label for="marketing_consent" class="ml-3 text-sm text-gray-700 cursor-pointer">
                    I'd like to receive travel tips, destination guides, and special offers via email
                </label>
            </div>

            <!-- Submit Button -->
            <button type="submit"
                class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-xl shadow-lg text-sm font-semibold text-black hover:text-white bg-gradient-to-r from-accent-600 to-accent-700 hover:from-[#3a79cc] hover:to-[#161e29] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#166fe5] transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98]">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Create your free account
            </button>
        </form>

       <!-- Footer Links -->
        <div class="space-y-6">
            <!-- Login Link -->
            <div class="text-center">
                <p class="text-gray-600">
                    Already have an account?
                    <a href="{{ route('login') }}"
                        class="font-semibold text-accent-600 hover:text-[#166fe5] hover:underline transition-colors duration-200">
                        Sign in here
                    </a>
                </p>
            </div>
            
            <!-- Benefits -->
            <div class="bg-gradient-to-r from-accent-50 to-secondary-50 rounded-xl p-4">
                <h4 class="font-semibold text-gray-900 mb-3">Why join PangoQ?</h4>
                <div class="space-y-2 text-sm text-gray-600">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2 text-secondary-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Save and organize your favorite destinations
                    </div>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2 text-secondary-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Get personalized travel recommendations
                    </div>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2 text-secondary-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Access exclusive deals and early event tickets
                    </div>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2 text-secondary-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Connect with fellow travelers and share experiences
                    </div>
                </div>
            </div>
            
            <!-- Trust Indicators -->
            <div class="flex items-center justify-center space-x-6 text-xs text-gray-500">
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-1 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    SSL Secured
                </div>
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-1 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Data Protected
                </div>
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-1 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"/>
                    </svg>
                    GDPR Compliant
                </div>
            </div>
            
            <!-- Social Proof -->
            <div class="text-center bg-gray-50 rounded-xl p-4">
                <div class="flex items-center justify-center space-x-1 mb-2">
                    @for($i = 0; $i < 5; $i++)
                        <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                    @endfor
                    <span class="ml-2 text-sm font-medium text-gray-700">4.9/5</span>
                </div>
                <p class="text-sm text-gray-600 mb-1">
                    <span class="font-semibold text-gray-900">50,000+</span> travelers have joined us
                </p>
                <p class="text-xs text-gray-500">
                    "The best travel planning platform I've ever used!" - Recent review
                </p>
            </div>
            
            <!-- Terms Footer -->
            <div class="text-center text-xs text-gray-500 leading-relaxed">
                By creating an account, you agree to our data processing practices as described in our Privacy Policy.
                <br>
                Free to join • No spam • Unsubscribe anytime
            </div>
        </div>
    </div>

    <!-- Enhanced JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Form elements
            const registerForm = document.querySelector('form');
            const submitButton = registerForm.querySelector('button[type="submit"]');
            const originalButtonContent = submitButton.innerHTML;
            
            // Input elements
            const nameInput = document.getElementById('name');
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            const confirmPasswordInput = document.getElementById('password_confirmation');
            const agreeTermsCheckbox = document.getElementById('agree_terms');
            
            // Password strength elements
            const passwordStrengthDiv = document.getElementById('password-strength');
            const strengthBar = document.getElementById('strength-bar');
            const strengthText = document.getElementById('strength-text');
            
            // Form submission handling
            registerForm.addEventListener('submit', function() {
                submitButton.disabled = true;
                submitButton.innerHTML = `
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Creating your account...
                `;
            });
            
            // Validation functions
            function validateEmail(email) {
                const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return re.test(email);
            }
            
            function validateName(name) {
                return name.trim().length >= 2 && /^[a-zA-Z\s]+$/.test(name.trim());
            }
            
            function checkPasswordStrength(password) {
                let score = 0;
                let feedback = [];
                
                if (password.length >= 8) score += 1;
                else feedback.push('at least 8 characters');
                
                if (/[a-z]/.test(password)) score += 1;
                else feedback.push('lowercase letter');
                
                if (/[A-Z]/.test(password)) score += 1;
                else feedback.push('uppercase letter');
                
                if (/[0-9]/.test(password)) score += 1;
                else feedback.push('number');
                
                if (/[^a-zA-Z0-9]/.test(password)) score += 1;
                else feedback.push('special character');
                
                return { score, feedback };
            }
            
            function showFieldError(field, message) {
                const existingError = field.parentNode.querySelector('.field-error');
                if (existingError) existingError.remove();
                
                field.classList.add('border-red-300', 'focus:border-red-500', 'focus:ring-red-500');
                field.classList.remove('border-gray-200', 'focus:border-[#166fe5]', 'focus:ring-[#166fe5]');
                
                const errorDiv = document.createElement('div');
                errorDiv.className = 'field-error text-red-600 text-sm mt-1';
                errorDiv.textContent = message;
                field.parentNode.appendChild(errorDiv);
            }
            
            function clearFieldError(field) {
                const existingError = field.parentNode.querySelector('.field-error');
                if (existingError) existingError.remove();
                
                field.classList.remove('border-red-300', 'focus:border-red-500', 'focus:ring-red-500');
                field.classList.add('border-gray-200', 'focus:border-[#166fe5]', 'focus:ring-[#166fe5]');
            }
            
            // Name validation
            nameInput.addEventListener('blur', function() {
                if (this.value && !validateName(this.value)) {
                    showFieldError(this, 'Please enter a valid full name (letters and spaces only)');
                } else {
                    clearFieldError(this);
                }
            });
            
            // Email validation
            emailInput.addEventListener('blur', function() {
                if (this.value && !validateEmail(this.value)) {
                    showFieldError(this, 'Please enter a valid email address');
                } else {
                    clearFieldError(this);
                }
            });
            
            // Password strength checking
            passwordInput.addEventListener('input', function() {
                const password = this.value;
                clearFieldError(this);
                
                if (password.length > 0) {
                    passwordStrengthDiv.classList.remove('hidden');
                    const { score, feedback } = checkPasswordStrength(password);
                    
                    // Update strength bar
                    const percentage = (score / 5) * 100;
                    strengthBar.style.width = percentage + '%';
                    
                    // Update colors and text
                    if (score <= 2) {
                        strengthBar.className = 'h-1 rounded-full transition-all duration-300 bg-red-500';
                        strengthText.textContent = 'Weak - Add ' + feedback.slice(0, 2).join(', ');
                        strengthText.className = 'text-xs mt-1 text-red-600';
                    } else if (score <= 3) {
                        strengthBar.className = 'h-1 rounded-full transition-all duration-300 bg-yellow-500';
                        strengthText.textContent = 'Fair - Add ' + feedback.slice(0, 1).join(', ');
                        strengthText.className = 'text-xs mt-1 text-yellow-600';
                    } else if (score <= 4) {
                        strengthBar.className = 'h-1 rounded-full transition-all duration-300 bg-blue-500';
                        strengthText.textContent = 'Good password';
                        strengthText.className = 'text-xs mt-1 text-blue-600';
                    } else {
                        strengthBar.className = 'h-1 rounded-full transition-all duration-300 bg-green-500';
                        strengthText.textContent = 'Strong password!';
                        strengthText.className = 'text-xs mt-1 text-green-600';
                    }
                } else {
                    passwordStrengthDiv.classList.add('hidden');
                }
            });
            
            // Password confirmation validation
            confirmPasswordInput.addEventListener('blur', function() {
                if (this.value && this.value !== passwordInput.value) {
                    showFieldError(this, 'Passwords do not match');
                } else {
                    clearFieldError(this);
                }
            });
            
            // Clear errors on input
            [nameInput, emailInput, passwordInput, confirmPasswordInput].forEach(input => {
                input.addEventListener('input', function() {
                    clearFieldError(this);
                });
            });
            
            // CSRF token handling
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                if (!form.querySelector('input[name="_token"]')) {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = '_token';
                    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                    input.value = token || '{{ csrf_token() }}';
                    form.appendChild(input);
                }
            });
            
            // Auto-focus progression
            nameInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter' && this.value.length >= 2) {
                    e.preventDefault();
                    emailInput.focus();
                }
            });
            
            emailInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter' && validateEmail(this.value)) {
                    e.preventDefault();
                    passwordInput.focus();
                }
            });
            
            passwordInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter' && this.value.length >= 6) {
                    e.preventDefault();
                    confirmPasswordInput.focus();
                }
            });
        });
    </script>
@endsection