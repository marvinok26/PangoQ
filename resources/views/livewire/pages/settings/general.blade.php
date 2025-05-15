<!-- resources/views/livewire/pages/settings/general.blade.php -->
@extends('layouts.dashboard')

@section('title', 'General Settings - PangoQ')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-semibold text-gray-900">General Settings</h1>
        
        <div class="mt-6 md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1">
                <div class="px-4 sm:px-0">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">Preferences</h3>
                    <p class="mt-1 text-sm text-gray-600">
                        Configure your general application preferences.
                    </p>
                </div>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                <form action="{{ route('settings.general.update') }}" method="POST">
                    @csrf
                    @method('PATCH')
                    
                    <div class="shadow overflow-hidden sm:rounded-md">
                        <div class="px-4 py-5 bg-white sm:p-6">
                            <div class="grid grid-cols-6 gap-6">
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="language" class="block text-sm font-medium text-gray-700">Language</label>
                                    <select id="language" name="language" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                        @foreach($languages as $code => $name)
                                            <option value="{{ $code }}" {{ session('language') === $code ? 'selected' : '' }}>{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="timezone" class="block text-sm font-medium text-gray-700">Timezone</label>
                                    <select id="timezone" name="timezone" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                        @foreach($timezones as $key => $zone)
                                            <option value="{{ $key }}" {{ session('timezone') === $key ? 'selected' : '' }}>{{ $zone }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="currency" class="block text-sm font-medium text-gray-700">Currency</label>
                                    <select id="currency" name="currency" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                        <option value="USD" {{ session('currency') === 'USD' ? 'selected' : '' }}>USD - United States Dollar</option>
                                        <option value="EUR" {{ session('currency') === 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                                        <option value="GBP" {{ session('currency') === 'GBP' ? 'selected' : '' }}>GBP - British Pound</option>
                                        <option value="KES" {{ session('currency') === 'KES' ? 'selected' : '' }}>KES - Kenyan Shilling</option>
                                    </select>
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
        
        <!-- Date & Time Format Section -->
        <div class="mt-10 md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1">
                <div class="px-4 sm:px-0">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">Display Preferences</h3>
                    <p class="mt-1 text-sm text-gray-600">
                        Configure how dates, times, and other information are displayed.
                    </p>
                </div>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                <div class="shadow overflow-hidden sm:rounded-md">
                    <div class="px-4 py-5 bg-white sm:p-6">
                        <div class="grid grid-cols-6 gap-6">
                            <div class="col-span-6 sm:col-span-3">
                                <label for="date_format" class="block text-sm font-medium text-gray-700">Date Format</label>
                                <select id="date_format" name="date_format" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    <option value="MM/DD/YYYY">MM/DD/YYYY (12/31/2024)</option>
                                    <option value="DD/MM/YYYY">DD/MM/YYYY (31/12/2024)</option>
                                    <option value="YYYY-MM-DD">YYYY-MM-DD (2024-12-31)</option>
                                </select>
                            </div>
                            
                            <div class="col-span-6 sm:col-span-3">
                                <label for="time_format" class="block text-sm font-medium text-gray-700">Time Format</label>
                                <select id="time_format" name="time_format" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    <option value="12h">12-hour (1:30 PM)</option>
                                    <option value="24h">24-hour (13:30)</option>
                                </select>
                            </div>
                            
                            <div class="col-span-6 sm:col-span-3">
                                <label for="distance_unit" class="block text-sm font-medium text-gray-700">Distance Unit</label>
                                <select id="distance_unit" name="distance_unit" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    <option value="km">Kilometers (km)</option>
                                    <option value="mi">Miles (mi)</option>
                                </select>
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