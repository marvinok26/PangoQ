{{-- resources/views/auth/login.blade.php --}}
@extends('layouts.page')

@section('title', 'Login - PangoQ')

@section('page-title', 'Welcome back')
@section('page-subtitle', 'Sign in to continue your travel planning journey')

@section('content')
    <div class="container mx-auto px-4 lg:px-8 py-8">
        <div class="max-w-md mx-auto">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="p-8">
                    <div class="text-center mb-8">
                        <h2 class="text-2xl font-bold text-gray-900">Sign in to your account</h2>
                        <p class="mt-2 text-sm text-gray-600">Continue your travel planning journey</p>
                    </div>

                    <!-- Social Login Buttons -->
                    <div class="space-y-3 mb-8">
                        <a href="{{ route('auth.redirect', 'google') }}"
                            class="w-full flex items-center justify-center px-4 py-3 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                            <svg class="h-5 w-5 mr-2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <g transform="matrix(1, 0, 0, 1, 27.009001, -39.238998)">
                                    <path fill="#4285F4"
                                        d="M -3.264 51.509 C -3.264 50.719 -3.334 49.969 -3.454 49.239 L -14.754 49.239 L -14.754 53.749 L -8.284 53.749 C -8.574 55.229 -9.424 56.479 -10.684 57.329 L -10.684 60.329 L -6.824 60.329 C -4.564 58.239 -3.264 55.159 -3.264 51.509 Z" />
                                    <path fill="#34A853"
                                        d="M -14.754 63.239 C -11.514 63.239 -8.804 62.159 -6.824 60.329 L -10.684 57.329 C -11.764 58.049 -13.134 58.489 -14.754 58.489 C -17.884 58.489 -20.534 56.379 -21.484 53.529 L -25.464 53.529 L -25.464 56.619 C -23.494 60.539 -19.444 63.239 -14.754 63.239 Z" />
                                    <path fill="#FBBC05"
                                        d="M -21.484 53.529 C -21.734 52.809 -21.864 52.039 -21.864 51.239 C -21.864 50.439 -21.724 49.669 -21.484 48.949 L -21.484 45.859 L -25.464 45.859 C -26.284 47.479 -26.754 49.299 -26.754 51.239 C -26.754 53.179 -26.284 54.999 -25.464 56.619 L -21.484 53.529 Z" />
                                    <path fill="#EA4335"
                                        d="M -14.754 43.989 C -12.984 43.989 -11.404 44.599 -10.154 45.789 L -6.734 42.369 C -8.804 40.429 -11.514 39.239 -14.754 39.239 C -19.444 39.239 -23.494 41.939 -25.464 45.859 L -21.484 48.949 C -20.534 46.099 -17.884 43.989 -14.754 43.989 Z" />
                                </g>
                            </svg>
                            Continue with Google
                        </a>

                        <a href="{{ route('auth.redirect', 'facebook') }}"
                            class="w-full flex items-center justify-center px-4 py-3 rounded-lg shadow-sm text-sm font-medium text-white bg-[#3b5998] hover:bg-[#324b81] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#3b5998] transition duration-150 ease-in-out">
                            <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path fill-rule="evenodd"
                                    d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"
                                    clip-rule="evenodd" />
                            </svg>
                            Continue with Facebook
                        </a>
                    </div>

                    <div class="relative my-8">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-4 bg-white text-gray-500 font-medium">Or sign in with email</span>
                        </div>
                    </div>

                    <!-- Session Status -->
                    @if (session('status'))
                        <div class="mb-4 p-4 rounded-lg bg-green-50 border border-green-200">
                            <div class="flex">
                                <svg class="h-5 w-5 text-green-600 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                    fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span class="font-medium text-sm text-green-800">{{ session('status') }}</span>
                            </div>
                        </div>
                    @endif

@if(session('message') || isset($hasTripData) && $hasTripData || session('status'))
    <div class="mb-4 p-4 rounded-lg {{ session('status') ? 'bg-green-50 border-green-200' : 'bg-blue-50 border-blue-200' }}">
        <div class="flex">
            <svg class="h-5 w-5 {{ session('status') ? 'text-green-600' : 'text-blue-600' }} mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zm-1 9a1 1 0 01-1-1v-4a1 1 0 112 0v4a1 1 0 01-1 1z" clip-rule="evenodd" />
            </svg>
            <span class="font-medium text-sm {{ session('status') ? 'text-green-800' : 'text-blue-800' }}">
                {{ session('status') ?: (session('message') ?: 'Sign in to save your trip planning progress. Your selections will be preserved after login.') }}
            </span>
        </div>
    </div>
@endif

                    <!-- Validation Errors -->
                    @if ($errors->any())
                        <div class="mb-4 p-4 rounded-lg bg-red-50 border border-red-200">
                            <div class="flex">
                                <svg class="h-5 w-5 text-red-600 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                    fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                <div>
                                    <p class="font-medium text-sm text-red-800">{{ __('Whoops! Something went wrong.') }}</p>
                                    <ul class="mt-1 list-disc list-inside text-sm text-red-600">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="space-y-6">
                        @csrf

                        <!-- Email Address -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email address</label>
                            <input id="email"
                                class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" required
                                autofocus />
                        </div>

                        <!-- Password -->
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                                @if(Route::has('password.request'))
                                    <a class="text-xs font-medium text-blue-600 hover:text-blue-500"
                                        href="{{ route('password.request') }}">
                                        {{ __('Forgot your password?') }}
                                    </a>
                                @else
                                    <a class="text-xs font-medium text-blue-600 hover:text-blue-500" href="#">
                                        {{ __('Forgot your password?') }}
                                    </a>
                                @endif
                            </div>
                            <input id="password"
                                class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                type="password" name="password" placeholder="••••••••" required
                                autocomplete="current-password" />
                        </div>

                        <!-- Remember Me -->
                        <div class="flex items-center justify-start">
                            <div class="flex items-center">
                                <input id="remember_me" type="checkbox"
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                    name="remember">
                                <label for="remember_me" class="ml-2 block text-sm text-gray-700">
                                    {{ __('Remember me') }}
                                </label>
                            </div>
                        </div>

                        <div>
                            <button type="submit"
                                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                                {{ __('Sign in') }}
                            </button>
                        </div>
                    </form>



                    <div class="mt-8 text-center text-sm">
                        <p class="text-gray-600">
                            Don't have an account?
                            <a href="{{ route('register') }}"
                                class="font-medium text-blue-600 hover:text-blue-500 hover:underline">
                                Create an account
                            </a>
                        </p>
                        <p class="mt-3 text-xs text-gray-500">
                            By signing in, you agree to our
                            <a href="{{ route('terms') }}" class="text-blue-600 hover:text-blue-500 hover:underline">Terms
                                of Service</a> and
                            <a href="{{ route('privacy') }}"
                                class="text-blue-600 hover:text-blue-500 hover:underline">Privacy Policy</a>.
                        </p>
                    </div>
                </div>
            </div>


        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Find all forms on the page
                document.querySelectorAll('form').forEach(form => {
                    // Check if form already has a CSRF token
                    if (!form.querySelector('input[name="_token"]')) {
                        // Create a new hidden input field for the CSRF token
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = '_token';
                        // Get the token from the meta tag if available
                        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                        // Otherwise, use the PHP generated token (will be replaced by Blade)
                        input.value = token || '{{ csrf_token() }}';
                        // Add the input field to the form
                        form.appendChild(input);
                        console.log('Added CSRF token to form');
                    }
                });
            });
        </script>
    </div>
@endsection