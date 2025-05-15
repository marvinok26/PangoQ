<!-- resources/views/livewire/pages/profile/notifications.blade.php -->
@extends('layouts.dashboard')

@section('title', 'Notification Settings - PangoQ')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-semibold text-gray-900">Notification Settings</h1>
        
        <div class="mt-6 md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1">
                <div class="px-4 sm:px-0">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">Email Notifications</h3>
                    <p class="mt-1 text-sm text-gray-600">
                        Select which notifications you'd like to receive via email.
                    </p>
                </div>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                <form action="{{ route('profile.notifications.update') }}" method="POST">
                    @csrf
                    @method('PATCH')
                    
                    <div class="shadow overflow-hidden sm:rounded-md">
                        <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                            <fieldset>
                                <legend class="text-base font-medium text-gray-900">Trip Notifications</legend>
                                <div class="mt-4 space-y-4">
                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input id="trip_updates" name="trip_updates" type="checkbox" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="trip_updates" class="font-medium text-gray-700">Trip Updates</label>
                                            <p class="text-gray-500">Receive notifications when there are changes to your trips.</p>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input id="trip_invitations" name="trip_invitations" type="checkbox" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded" checked>
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="trip_invitations" class="font-medium text-gray-700">Trip Invitations</label>
                                            <p class="text-gray-500">Receive notifications when you're invited to join a trip.</p>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input id="trip_reminders" name="trip_reminders" type="checkbox" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded" checked>
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="trip_reminders" class="font-medium text-gray-700">Trip Reminders</label>
                                            <p class="text-gray-500">Receive reminders about upcoming trips.</p>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            
                            <fieldset>
                                <legend class="text-base font-medium text-gray-900">Payment Notifications</legend>
                                <div class="mt-4 space-y-4">
                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input id="payment_notifications" name="payment_notifications" type="checkbox" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded" checked>
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="payment_notifications" class="font-medium text-gray-700">Payment Confirmation</label>
                                            <p class="text-gray-500">Receive confirmation for all payments and withdrawals.</p>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input id="savings_reminders" name="savings_reminders" type="checkbox" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded" checked>
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="savings_reminders" class="font-medium text-gray-700">Savings Reminders</label>
                                            <p class="text-gray-500">Receive reminders to contribute to your savings goals.</p>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            
                            <fieldset>
                                <legend class="text-base font-medium text-gray-900">Marketing</legend>
                                <div class="mt-4 space-y-4">
                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input id="marketing_emails" name="marketing_emails" type="checkbox" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="marketing_emails" class="font-medium text-gray-700">Marketing Emails</label>
                                            <p class="text-gray-500">Receive marketing emails about new features, promotions, and travel tips.</p>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Save Preferences
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection