{{-- resources/views/livewire/pages/trips/create.blade.php --}}

@extends('layouts.page')

@section('title', 'Plan Your Trip')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                @livewire('trips.create-trip')
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Optional: Add any trip planning specific JavaScript here
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Trip planning page loaded');
    });
</script>
@endpush