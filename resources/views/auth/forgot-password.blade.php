{{-- resources/views/auth/forgot-password.blade.php --}}
@extends('layouts.page')

@section('title', 'Reset Password - PangoQ')
    
@section('page-title', 'Reset your password')
@section('page-subtitle', 'We\'ll send you a link to reset your password')
    
@section('content')
<div class="container mx-auto px-4 lg:px-8 py-8">
    <div class="max-w-md mx-auto">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="p-8">
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-bold text-gray-900">Forgot your password?</h2>
                    <p class="mt-2 text-sm text-gray-600">No problem. Just let us know your email address and we'll email you a password reset link.</p>
                </div>

                <!-- Session Status -->
                @if (session('status'))
                    <div class="mb-6 p-4 rounded-lg bg-green-50 border border-green-200">
                        <div class="flex">
                            <svg class="h-5 w-5 text-green-600 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span class="font-medium text-sm text-green-800">{{ session('status') }}</span>
                        </div>
                    </div>
                @endif

                <!-- Validation Errors -->
                @if ($errors->any())
                    <div class="mb-6 p-4 rounded-lg bg-red-50 border border-red-200">
                        <div class="flex">
                            <svg class="h-5 w-5 text-red-600 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            <div>
                                <ul class="mt-1 list-disc list-inside text-sm text-red-600">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email address</label>
                        <input id="email" 
                               class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                               type="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               placeholder="you@example.com"
                               required 
                               autofocus />
                    </div>

                    <div>
                        <button type="submit" 
                                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                            {{ __('Email Password Reset Link') }}
                        </button>
                    </div>
                </form>

                <div class="mt-8 text-center text-sm">
                    <p class="text-gray-600">
                        <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-500 hover:underline">
                            <span class="inline-flex items-center">
                                <svg class="mr-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                                </svg>
                                Back to login
                            </span>
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection