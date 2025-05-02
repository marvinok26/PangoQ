{{-- resources/views/livewire/pages/auth/login.blade.php --}}
<x-app-layout>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <div class="mb-6 text-center">
                <h2 class="text-2xl font-bold text-gray-900">Welcome back to PangoQ</h2>
                <p class="mt-2 text-sm text-gray-600">Sign in to continue planning your group trips</p>
            </div>

            <!-- Social Login Buttons -->
            <div class="space-y-3 mb-6">
                <a href="{{ route('auth.google') }}" class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary-500">
                    <svg class="h-5 w-5 mr-2" viewBox="0 0 24 24" width="24" height="24" xmlns="http://www.w3.org/2000/svg">
                        <g transform="matrix(1, 0, 0, 1, 27.009001, -39.238998)">
                            <path fill="#4285F4" d="M -3.264 51.509 C -3.264 50.719 -3.334 49.969 -3.454 49.239 L -14.754 49.239 L -14.754 53.749 L -8.284 53.749 C -8.574 55.229 -9.424 56.479 -10.684 57.329 L -10.684 60.329 L -6.824 60.329 C -4.564 58.239 -3.264 55.159 -3.264 51.509 Z"/>
                            <path fill="#34A853" d="M -14.754 63.239 C -11.514 63.239 -8.804 62.159 -6.824 60.329 L -10.684 57.329 C -11.764 58.049 -13.134 58.489 -14.754 58.489 C -17.884 58.489 -20.534 56.379 -21.484 53.529 L -25.464 53.529 L -25.464 56.619 C -23.494 60.539 -19.444 63.239 -14.754 63.239 Z"/>
                            <path fill="#FBBC05" d="M -21.484 53.529 C -21.734 52.809 -21.864 52.039 -21.864 51.239 C -21.864 50.439 -21.724 49.669 -21.484 48.949 L -21.484 45.859 L -25.464 45.859 C -26.284 47.479 -26.754 49.299 -26.754 51.239 C -26.754 53.179 -26.284 54.999 -25.464 56.619 L -21.484 53.529 Z"/>
                            <path fill="#EA4335" d="M -14.754 43.989 C -12.984 43.989 -11.404 44.599 -10.154 45.789 L -6.734 42.369 C -8.804 40.429 -11.514 39.239 -14.754 39.239 C -19.444 39.239 -23.494 41.939 -25.464 45.859 L -21.484 48.949 C -20.534 46.099 -17.884 43.989 -14.754 43.989 Z"/>
                        </g>
                    </svg>
                    Sign in with Google
                </a>
                
                <a href="{{ route('auth.facebook') }}" class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-white bg-[#3b5998] hover:bg-[#324b81] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#3b5998]">
                    <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" />
                    </svg>
                    Sign in with Facebook
                </a>
            </div>
            
            <div class="relative mb-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-2 bg-white text-gray-500">Or continue with email</span>
                </div>
            </div>

            <!-- Session Status -->
            <div>
                @if (session('status'))
                    <div class="mb-4 font-medium text-sm text-green-600">
                        {{ session('status') }}
                    </div>
                @endif
            </div>

            <!-- Validation Errors -->
            @if ($errors->any())
                <div class="mb-4">
                    <div class="font-medium text-red-600">
                        {{ __('Whoops! Something went wrong.') }}
                    </div>

                    <ul class="mt-3 list-disc list-inside text-sm text-red-600">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form wire:submit="login" class="space-y-4">
                <!-- Email Address -->
                <div>
                    <x-label for="email" :value="__('Email')" />

                    <x-input id="email" wire:model="form.email" class="block mt-1 w-full" type="email" name="email" required autofocus />
                </div>

                <!-- Password -->
                <div>
                    <div class="flex items-center justify-between">
                        <x-label for="password" :value="__('Password')" />
                        <a class="text-xs text-secondary-600 hover:text-secondary-500" href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                    </div>

                    <x-input id="password" wire:model="form.password" class="block mt-1 w-full"
                             type="password"
                             name="password"
                             required autocomplete="current-password" />
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" wire:model="form.remember" type="checkbox" class="rounded border-gray-300 text-secondary-600 shadow-sm focus:border-secondary-300 focus:ring focus:ring-secondary-200 focus:ring-opacity-50" name="remember">
                        <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                    </label>
                </div>

                <div>
                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-secondary-600 hover:bg-secondary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary-500">
                        {{ __('Log in') }}
                    </button>
                </div>
            </form>

            <div class="mt-6 text-center text-sm">
                <p class="text-gray-600">
                    Don't have an account? 
                    <a href="{{ route('register') }}" class="font-medium text-secondary-600 hover:text-secondary-500">
                        Sign up
                    </a>
                </p>
                <p class="mt-2 text-xs text-gray-500">
                    By signing in, you agree to our 
                    <a href="{{ route('terms') }}" class="text-secondary-600 hover:text-secondary-500">Terms of Service</a> and 
                    <a href="{{ route('privacy') }}" class="text-secondary-600 hover:text-secondary-500">Privacy Policy</a>.
                </p>
            </div>
        </div>
    </div>
</x-app-layout>