{{-- resources/views/livewire/pages/savings/edit.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Savings Wallet') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-6 flex justify-between items-center">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Edit Savings Wallet</h1>
                            <p class="text-gray-600">Update your savings settings for {{ $trip->title }}</p>
                        </div>
                        <a href="{{ route('trips.savings.show', $trip) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Back to Wallet
                        </a>
                    </div>

                    <form method="POST" action="{{ route('trips.savings.update', $trip) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Wallet Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $wallet->name) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-secondary-500 focus:ring-secondary-500 sm:text-sm">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="target_amount" class="block text-sm font-medium text-gray-700">Target Amount</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">$</span>
                                </div>
                                <input type="number" name="target_amount" id="target_amount" step="0.01" min="0"
                                    value="{{ old('target_amount', $wallet->target_amount) }}"
                                    class="pl-7 block w-full pr-12 rounded-md border-gray-300 shadow-sm focus:border-secondary-500 focus:ring-secondary-500 sm:text-sm"
                                    placeholder="0.00">
                            </div>
                            @error('target_amount')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="target_date" class="block text-sm font-medium text-gray-700">Target Date</label>
                            <input type="date" name="target_date" id="target_date"
                                value="{{ old('target_date', $wallet->target_date->format('Y-m-d')) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-secondary-500 focus:ring-secondary-500 sm:text-sm">
                            @error('target_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="contribution_frequency" class="block text-sm font-medium text-gray-700">Contribution Frequency</label>
                            <select name="contribution_frequency" id="contribution_frequency"
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-secondary-500 focus:border-secondary-500 sm:text-sm rounded-md">
                                <option value="weekly" {{ old('contribution_frequency', $wallet->contribution_frequency) === 'weekly' ? 'selected' : '' }}>Weekly</option>
                                <option value="monthly" {{ old('contribution_frequency', $wallet->contribution_frequency) === 'monthly' ? 'selected' : '' }}>Monthly</option>
                            </select>
                            @error('contribution_frequency')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="pt-5 flex justify-between">
                            <a href="{{ route('trips.savings.show', $trip) }}"
                                class="inline-flex justify-center rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-secondary-500 focus:ring-offset-2">
                                Cancel
                            </a>

                            <button type="submit"
                                class="inline-flex justify-center rounded-md border border-transparent bg-secondary-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-secondary-700 focus:outline-none focus:ring-2 focus:ring-secondary-500 focus:ring-offset-2">
                                Save Changes
                            </button>
                        </div>
                    </form>

                    <div class="mt-10 pt-6 border-t border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Current Savings Status</h3>
                        
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div>
                                    <p class="text-sm text-gray-500">Target Amount</p>
                                    <p class="text-xl font-semibold">${{ number_format($wallet->target_amount, 2) }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Current Amount</p>
                                    <p class="text-xl font-semibold">${{ number_format($wallet->current_amount, 2) }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Progress</p>
                                    <p class="text-xl font-semibold">{{ $wallet->progress_percentage }}%</p>
                                </div>
                            </div>
                            
                            <div class="mt-4">
                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                    <div class="bg-green-600 h-2.5 rounded-full" style="width: {{ $wallet->progress_percentage }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>