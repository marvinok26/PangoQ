{{-- resources/views/auth/verify-email.blade.php --}}
@extends('layouts.page')

@section('title', 'Verify Email - PangoQ')
    
@section('page-title', 'Verify your email')
@section('page-subtitle', 'We\'ve sent you a verification link')
    
@section('content')
<div class="container mx-auto px-4 lg:px-8 py-8">
    <div class="max-w-md mx-auto">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="p-8">
                <div class="text-center mb-6">
                    <div class="flex justify-center mb-4">
                        <div class="h-16 w-16 bg-green-100 rounded-full flex items-center justify-center">
                            <svg class="h-8 w-8 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">Thanks for signing up!</h2>
                    <p class="mt-2 text-sm text-gray-600">Please verify your email address to start enjoying all PangoQ features.</p>
                </div>

                <div class="mb-6 p-4 rounded-lg bg-blue-50 border border-blue-200">
                    <div class="flex">
                        <svg class="h-5 w-5 text-blue-600 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                        <p class="text-sm text-blue-700">
                            {{ __('Before proceeding, please check your email for a verification link. If you didn\'t receive the email, we\'ll gladly send you another.') }}
                        </p>
                    </div>
                </div>

                @if (session('status') == 'verification-link-sent')
                    <div class="mb-6 p-4 rounded-lg bg-green-50 border border-green-200">
                        <div class="flex">
                            <svg class="h-5 w-5 text-green-600 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span class="font-medium text-sm text-green-800">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </span>
                        </div>
                    </div>
                @endif

                <div class="flex flex-col sm:flex-row gap-4 sm:justify-between">
                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button type="submit" 
                                class="w-full sm:w-auto flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                            {{ __('Resend Verification Email') }}
                        </button>
                    </form>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" 
                                class="w-full sm:w-auto flex justify-center py-3 px-4 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                            {{ __('Log Out') }}
                        </button>
                    </form>
                </div>

                <div class="mt-8 border-t border-gray-200 pt-6">
                    <p class="text-sm text-gray-600 text-center">
                        Didn't receive an email? Check your spam folder or contact 
                        <a href="{{ route('support') }}" class="font-medium text-blue-600 hover:text-blue-500 hover:underline">customer support</a>
                        for assistance.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection