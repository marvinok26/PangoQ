<!-- resources/views/livewire/pages/settings/privacy.blade.php -->
@extends('layouts.dashboard')

@section('title', 'Privacy Settings - PangoQ')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-semibold text-gray-900">Privacy Settings</h1>
        
        <div class="mt-6 md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1">
                <div class="px-4 sm:px-0">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">Visibility</h3>
                    <p class="mt-1 text-sm text-gray-600">
                        Control who can see your profile and trip information.
                    </p>
                </div>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                <form action="{{ route('settings.privacy.update') }}" method="POST">
                    @csrf
                    @method('PATCH')
                    
                    <div class="shadow overflow-hidden sm:rounded-md">
                        <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                            <fieldset>
                                <legend class="text-base font-medium text-gray-900">Profile Visibility</legend>
                                <div class="mt-4">
                                    <div class="space-y-4">
                                        <div class="flex items-center">
                                            <input id="profile_public" name="profile_visibility" type="radio" value="public" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300">
                                            <label for="profile_public" class="ml-3 block text-sm font-medium text-gray-700">
                                                Public
                                                <p class="text-gray-500 text-xs">Anyone can see your profile information.</p>
                                            </label>
                                        </div>
                                        <div class="flex items-center">
                                            <input id="profile_friends" name="profile_visibility" type="radio" value="friends" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300" checked>
                                            <label for="profile_friends" class="ml-3 block text-sm font-medium text-gray-700">
                                                Friends Only
                                                <p class="text-gray-500 text-xs">Only people you've traveled with can see your profile.</p>
                                            </label>
                                        </div>
                                        <div class="flex items-center">
                                            <input id="profile_private" name="profile_visibility" type="radio" value="private" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300">
                                            <label for="profile_private" class="ml-3 block text-sm font-medium text-gray-700">
                                                Private
                                                <p class="text-gray-500 text-xs">Only you can see your profile information.</p>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            
                            <div class="space-y-4">
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="show_activity" name="show_activity" type="checkbox" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded" checked>
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="show_activity" class="font-medium text-gray-700">Show Activity</label>
                                        <p class="text-gray-500">Allow others to see your activity in the app.</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="show_trips" name="show_trips" type="checkbox" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded" checked>
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="show_trips" class="font-medium text-gray-700">Show Trips</label>
                                        <p class="text-gray-500">Allow others to see your trips and travel history.</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="allow_friend_requests" name="allow_friend_requests" type="checkbox" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded" checked>
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="allow_friend_requests" class="font-medium text-gray-700">Friend Requests</label>
                                        <p class="text-gray-500">Allow others to send you friend requests.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Save
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="hidden sm:block" aria-hidden="true">
            <div class="py-5">
                <div class="border-t border-gray-200"></div>
            </div>
        </div>
        
        <!-- Data Privacy Section -->
        <div class="mt-10 md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1">
                <div class="px-4 sm:px-0">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">Data & Privacy</h3>
                    <p class="mt-1 text-sm text-gray-600">
                        Manage your data and download your information.
                    </p>
                </div>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                <div class="shadow overflow-hidden sm:rounded-md">
                    <div class="px-4 py-5 bg-white sm:p-6">
                        <div class="space-y-6">
                            <div>
                                <h4 class="text-base font-medium text-gray-900">Download Your Data</h4>
                                <p class="mt-1 text-sm text-gray-600">
                                    Request a download of your personal data including profile information, trips, and activity.
                                </p>
                                <div class="mt-3">
                                    <button type="button" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5 text-gray-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                            <polyline points="7 10 12 15 17 10"></polyline>
                                            <line x1="12" y1="15" x2="12" y2="3"></line>
                                        </svg>
                                        Request Data Download
                                    </button>
                                </div>
                            </div>
                            
                            <div>
                                <h4 class="text-base font-medium text-gray-900">Third-Party Data Sharing</h4>
                                <p class="mt-1 text-sm text-gray-600">
                                    Control how your data is shared with third-party services.
                                </p>
                                <div class="mt-3 space-y-4">
                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input id="analytics_consent" name="analytics_consent" type="checkbox" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded" checked>
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="analytics_consent" class="font-medium text-gray-700">Analytics Consent</label>
                                            <p class="text-gray-500">Allow us to collect anonymous usage data to improve our services.</p>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input id="marketing_consent" name="marketing_consent" type="checkbox" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="marketing_consent" class="font-medium text-gray-700">Marketing Consent</label>
                                            <p class="text-gray-500">Allow us to share your data with marketing partners.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Save
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection