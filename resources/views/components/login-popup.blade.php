{{-- resources/views/components/login-popup.blade.php --}}
<div 
    x-data="{ 
        show: false,
        stepCount: 0,
        incrementStep() {
            this.stepCount++;
            if (this.stepCount >= 2 && !localStorage.getItem('login_popup_dismissed')) {
                this.show = true;
            }
        }
    }"
    @navigate-step.window="incrementStep()"
    @show-login-popup.window="show = true"
    class="relative z-50"
>
    <div x-show="show" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

    <div x-show="show" class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div 
                x-show="show"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md sm:p-6"
            >
                <!-- Close button -->
                <div class="absolute top-0 right-0 pt-4 pr-4">
                    <button 
                        type="button" 
                        @click="show = false; localStorage.setItem('login_popup_dismissed', 'true')" 
                        class="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-secondary-500 focus:ring-offset-2"
                    >
                        <span class="sr-only">Close</span>
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div>
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-secondary-100">
                        <svg class="h-6 w-6 text-secondary-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-5">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">Save your progress</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                Sign in to save your trip details and continue planning later.
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="mt-5 sm:mt-6 space-y-3">
                    <!-- Social login options -->
                    <a href="{{ route('auth.google') }}" class="inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-500 shadow-sm hover:bg-gray-50">
                        <svg class="mr-2 -ml-1 h-5 w-5" viewBox="0 0 24 24" width="24" height="24" xmlns="http://www.w3.org/2000/svg">
                            <g transform="matrix(1, 0, 0, 1, 27.009001, -39.238998)">
                                <path fill="#4285F4" d="M -3.264 51.509 C -3.264 50.719 -3.334 49.969 -3.454 49.239 L -14.754 49.239 L -14.754 53.749 L -8.284 53.749 C -8.574 55.229 -9.424 56.479 -10.684 57.329 L -10.684 60.329 L -6.824 60.329 C -4.564 58.239 -3.264 55.159 -3.264 51.509 Z"/>
                                <path fill="#34A853" d="M -14.754 63.239 C -11.514 63.239 -8.804 62.159 -6.824 60.329 L -10.684 57.329 C -11.764 58.049 -13.134 58.489 -14.754 58.489 C -17.884 58.489 -20.534 56.379 -21.484 53.529 L -25.464 53.529 L -25.464 56.619 C -23.494 60.539 -19.444 63.239 -14.754 63.239 Z"/>
                                <path fill="#FBBC05" d="M -21.484 53.529 C -21.734 52.809 -21.864 52.039 -21.864 51.239 C -21.864 50.439 -21.724 49.669 -21.484 48.949 L -21.484 45.859 L -25.464 45.859 C -26.284 47.479 -26.754 49.299 -26.754 51.239 C -26.754 53.179 -26.284 54.999 -25.464 56.619 L -21.484 53.529 Z"/>
                                <path fill="#EA4335" d="M -14.754 43.989 C -12.984 43.989 -11.404 44.599 -10.154 45.789 L -6.734 42.369 C -8.804 40.429 -11.514 39.239 -14.754 39.239 C -19.444 39.239 -23.494 41.939 -25.464 45.859 L -21.484 48.949 C -20.534 46.099 -17.884 43.989 -14.754 43.989 Z"/>
                            </g>
                        </svg>
                        Sign in with Google
                    </a>
                    
                    <a href="{{ route('auth.facebook') }}" class="inline-flex w-full justify-center rounded-md border border-transparent bg-[#3b5998] px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-[#324b81]">
                        <svg class="mr-2 -ml-1 h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" />
                        </svg>
                        Sign in with Facebook
                    </a>
                    
                    <!-- Email login link -->
                    <a href="{{ route('login') }}" class="inline-flex w-full justify-center rounded-md border border-transparent bg-secondary-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-secondary-700">
                        Sign in with email
                    </a>
                    
                    <!-- Create account link -->
                    <a href="{{ route('register') }}" class="inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50">
                        Create an account
                    </a>
                    
                    <!-- Continue without account -->
                    <button 
                        type="button" 
                        @click="show = false; localStorage.setItem('login_popup_dismissed', 'true')" 
                        class="inline-flex w-full justify-center text-sm text-gray-500 hover:text-gray-700"
                    >
                        Continue without an account
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>