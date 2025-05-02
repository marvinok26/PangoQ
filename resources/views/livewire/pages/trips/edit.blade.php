{{-- resources/views/livewire/pages/trips/edit.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Trip') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('trips.update', $trip) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700">Trip Name</label>
                            <input type="text" name="title" id="title" value="{{ old('title', $trip->title) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-secondary-500 focus:ring-secondary-500 sm:text-sm">
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="destination" class="block text-sm font-medium text-gray-700">Destination</label>
                            <input type="text" name="destination" id="destination"
                                value="{{ old('destination', $trip->destination) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-secondary-500 focus:ring-secondary-500 sm:text-sm">
                            @error('destination')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700">Start
                                    Date</label>
                                <input type="date" name="start_date" id="start_date"
                                    value="{{ old('start_date', $trip->start_date->format('Y-m-d')) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-secondary-500 focus:ring-secondary-500 sm:text-sm">
                                @error('start_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                                <input type="date" name="end_date" id="end_date"
                                    value="{{ old('end_date', $trip->end_date->format('Y-m-d')) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-secondary-500 focus:ring-secondary-500 sm:text-sm">
                                @error('end_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Description
                                (Optional)</label>
                            <textarea name="description" id="description" rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-secondary-500 focus:ring-secondary-500 sm:text-sm">{{ old('description', $trip->description) }}</textarea>
                        </div>

                        <div>
                            <label for="budget" class="block text-sm font-medium text-gray-700">Budget
                                (Optional)</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">$</span>
                                </div>
                                <input type="number" name="budget" id="budget" step="0.01"
                                    value="{{ old('budget', $trip->budget) }}"
                                    class="pl-7 block w-full pr-12 rounded-md border-gray-300 shadow-sm focus:border-secondary-500 focus:ring-secondary-500 sm:text-sm"
                                    placeholder="0.00">
                            </div>
                        </div>

                        @if($trip->status !== 'cancelled')
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Trip Status</label>
                                <select name="status" id="status"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-secondary-500 focus:ring-secondary-500 sm:text-sm">
                                    <option value="planning" {{ old('status', $trip->status) === 'planning' ? 'selected' : '' }}>Planning</option>
                                    <option value="active" {{ old('status', $trip->status) === 'active' ? 'selected' : '' }}>
                                        Active</option>
                                    <option value="completed" {{ old('status', $trip->status) === 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ old('status', $trip->status) === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>
                        @endif

                        <div class="flex justify-between pt-5">
                            <a href="{{ route('trips.show', $trip) }}"
                                class="inline-flex justify-center rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-secondary-500 focus:ring-offset-2">
                                Cancel
                            </a>

                            <div class="flex space-x-3">
                                @if($trip->creator_id === auth()->id() && $trip->status !== 'cancelled')
                                    <button type="button" onclick="confirmDelete()"
                                        class="inline-flex justify-center rounded-md border border-red-300 bg-white py-2 px-4 text-sm font-medium text-red-700 shadow-sm hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                        Delete Trip
                                    </button>
                                @endif

                                <button type="submit"
                                    class="inline-flex justify-center rounded-md border border-transparent bg-secondary-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-secondary-700 focus:outline-none focus:ring-2 focus:ring-secondary-500 focus:ring-offset-2">
                                    Save Changes
                                </button>
                            </div>
                        </div>
                    </form>

                    @if($trip->creator_id === auth()->id() && $trip->status !== 'cancelled')
                        <form id="delete-form" action="{{ route('trips.destroy', $trip) }}" method="POST" class="hidden">
                            @csrf
                            @method('DELETE')
                        </form>

                        <script>
                            function confirmDelete() {
                                if (confirm('Are you sure you want to delete this trip? This action cannot be undone.')) {
                                    document.getElementById('delete-form').submit();
                                }
                            }
                        </script>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>