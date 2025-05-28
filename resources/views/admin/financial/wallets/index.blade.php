{{-- resources/views/admin/financial/wallets/index.blade.php --}}

@extends('admin.layouts.app')

@section('title', 'Wallets Management')
@section('page-title', 'Savings Wallets')

@section('content')
<!-- Search and Filter Section -->
<div class="mb-6 flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
    <!-- Search Form -->
    <div class="flex-1 max-w-md">
        <form method="GET" action="{{ route('admin.wallets.index') }}" class="flex space-x-2">
            <input type="text" 
                   name="search" 
                   class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm" 
                   placeholder="Search wallets..." 
                   value="{{ request('search') }}">
            <button type="submit" class="inline-flex items-center px-3 py-2 border border-blue-300 text-sm font-medium rounded-md text-blue-700 bg-blue-50 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </button>
        </form>
    </div>
    
    <!-- Filter Buttons -->
    <div class="flex flex-wrap gap-2">
        <a href="{{ route('admin.wallets.index') }}" 
           class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md border transition-colors duration-200 {{ !request()->hasAny(['flagged', 'currency']) ? 'border-gray-800 bg-gray-800 text-white' : 'border-gray-300 text-gray-700 bg-white hover:bg-gray-50' }}">
            All Wallets
        </a>
        <a href="{{ route('admin.wallets.index', ['flagged' => '1']) }}" 
           class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md border transition-colors duration-200 {{ request('flagged') === '1' ? 'border-red-600 bg-red-600 text-white' : 'border-red-300 text-red-700 bg-red-50 hover:bg-red-100' }}">
            Flagged
        </a>
        <a href="{{ route('admin.wallets.index', ['currency' => 'USD']) }}" 
           class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md border transition-colors duration-200 {{ request('currency') === 'USD' ? 'border-blue-600 bg-blue-600 text-white' : 'border-blue-300 text-blue-700 bg-blue-50 hover:bg-blue-100' }}">
            USD
        </a>
        <a href="{{ route('admin.wallets.index', ['currency' => 'KES']) }}" 
           class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md border transition-colors duration-200 {{ request('currency') === 'KES' ? 'border-green-600 bg-green-600 text-white' : 'border-green-300 text-green-700 bg-green-50 hover:bg-green-100' }}">
            KES
        </a>
    </div>
</div>

<!-- Wallets Table -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200">
    <div class="p-6">
        @if($wallets->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Wallet</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Owner</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trip</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Goal</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Current</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progress</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Currency</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($wallets as $wallet)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ is_array($wallet->name) ? ($wallet->name['en'] ?? 'Wallet') : $wallet->name }}
                                        </div>
                                        @if($wallet->admin_flagged)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                                Flagged
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm">
                                @if($wallet->user)
                                    <a href="{{ route('admin.users.show', $wallet->user) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                        {{ $wallet->user->name }}
                                    </a>
                                @else
                                    <span class="text-red-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                        </svg>
                                        No User
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm">
                                @if($wallet->trip)
                                    <a href="{{ route('admin.trips.show', $wallet->trip) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                        {{ Str::limit($wallet->trip->title, 20) }}
                                    </a>
                                @else
                                    <span class="text-gray-500">No trip</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ format_currency($wallet->target_amount, $wallet->currency) }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ format_currency($wallet->current_amount, $wallet->currency) }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-2">
                                    <div class="flex-1 bg-gray-200 rounded-full h-2 w-24">
                                        <div class="h-2 rounded-full {{ $wallet->progress_percentage >= 100 ? 'bg-green-500' : ($wallet->progress_percentage >= 50 ? 'bg-blue-500' : 'bg-yellow-500') }}" 
                                             style="width: {{ min($wallet->progress_percentage, 100) }}%"></div>
                                    </div>
                                    <span class="text-xs text-gray-600 w-12 text-right">{{ $wallet->progress_percentage }}%</span>
                                </div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $wallet->currency }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                @if($wallet->admin_flagged)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Flagged
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Active
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ format_admin_date($wallet->created_at, 'M j, Y') }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm">
                                @if($wallet->user)
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.wallets.show', $wallet) }}" 
                                           class="inline-flex items-center p-1.5 border border-blue-300 rounded text-blue-600 hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>
                                        <button type="button" 
                                                onclick="openFlagModal('{{ $wallet->id }}')"
                                                class="inline-flex items-center p-1.5 border border-{{ $wallet->admin_flagged ? 'green' : 'yellow' }}-300 rounded text-{{ $wallet->admin_flagged ? 'green' : 'yellow' }}-600 hover:bg-{{ $wallet->admin_flagged ? 'green' : 'yellow' }}-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-{{ $wallet->admin_flagged ? 'green' : 'yellow' }}-500 transition-colors duration-200">
                                            <svg class="w-4 h-4" fill="{{ $wallet->admin_flagged ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/>
                                            </svg>
                                        </button>
                                    </div>
                                @else
                                    <span class="text-gray-500 text-xs">No actions available</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6 flex justify-center">
                {{ $wallets->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
                <h4 class="mt-4 text-lg font-medium text-gray-900">No wallets found</h4>
                <p class="mt-2 text-gray-600">Try adjusting your search criteria.</p>
            </div>
        @endif
    </div>
</div>

<!-- Flag Toggle Modal -->
<div id="flagModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-20 mx-auto p-5 border max-w-md shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <!-- Modal Header -->
            <div class="flex items-center justify-between mb-4">
                <h3 id="modalTitle" class="text-lg font-medium text-gray-900"></h3>
                <button type="button" onclick="closeFlagModal()" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <!-- Modal Form -->
            <form id="flagForm" method="POST" action="">
                @csrf
                @method('PATCH')
                
                <div id="modalContent" class="mb-6">
                    <!-- Content will be populated by JavaScript -->
                </div>
                
                <!-- Modal Actions -->
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeFlagModal()" class="inline-flex justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                        Cancel
                    </button>
                    <button id="submitButton" type="submit" class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white border border-transparent rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors duration-200">
                        <!-- Text and color will be set by JavaScript -->
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const wallets = {
    @foreach($wallets as $wallet)
        @if($wallet->user)
        {{ $wallet->id }}: {
            id: {{ $wallet->id }},
            name: @json(is_array($wallet->name) ? ($wallet->name['en'] ?? 'Wallet') : $wallet->name),
            admin_flagged: {{ $wallet->admin_flagged ? 'true' : 'false' }},
            admin_notes: @json($wallet->admin_notes),
            flag_url: @json(route('admin.wallets.toggle-flag', $wallet))
        }@if(!$loop->last),@endif
        @endif
    @endforeach
};

function openFlagModal(walletId) {
    const wallet = wallets[walletId];
    if (!wallet) return;
    
    const modal = document.getElementById('flagModal');
    const modalTitle = document.getElementById('modalTitle');
    const modalContent = document.getElementById('modalContent');
    const flagForm = document.getElementById('flagForm');
    const submitButton = document.getElementById('submitButton');
    
    // Set form action
    flagForm.action = wallet.flag_url;
    
    if (wallet.admin_flagged) {
        // Clear Flag
        modalTitle.textContent = `Clear Flag - ${wallet.name}`;
        modalContent.innerHTML = `
            <div class="rounded-md bg-yellow-50 p-4 mb-4">
                <div class="flex">
                    <svg class="h-5 w-5 text-yellow-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <h3 class="text-sm font-medium text-yellow-800">This wallet is currently flagged</h3>
                        <p class="text-sm text-yellow-700 mt-1">Are you sure you want to clear the flag?</p>
                    </div>
                </div>
            </div>
            ${wallet.admin_notes ? `
                <div class="rounded-md bg-blue-50 p-4">
                    <div class="flex">
                        <svg class="h-5 w-5 text-blue-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <h3 class="text-sm font-medium text-blue-800">Current Flag Reason:</h3>
                            <p class="text-sm text-blue-700 mt-1">${wallet.admin_notes}</p>
                        </div>
                    </div>
                </div>
            ` : ''}
        `;
        submitButton.textContent = 'Clear Flag';
        submitButton.className = 'inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200';
    } else {
        // Flag Wallet
        modalTitle.textContent = `Flag Wallet - ${wallet.name}`;
        modalContent.innerHTML = `
            <div class="rounded-md bg-red-50 p-4 mb-4">
                <div class="flex">
                    <svg class="h-5 w-5 text-red-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/>
                    </svg>
                    <div>
                        <h3 class="text-sm font-medium text-red-800">Flag this wallet</h3>
                        <p class="text-sm text-red-700 mt-1">This will mark the wallet for review.</p>
                    </div>
                </div>
            </div>
            <div class="mb-4">
                <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">Reason for Flagging</label>
                <textarea name="reason" id="reason" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-red-500 focus:border-red-500" rows="3" required placeholder="Explain why you're flagging this wallet..."></textarea>
            </div>
        `;
        submitButton.textContent = 'Flag Wallet';
        submitButton.className = 'inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200';
    }
    
    modal.classList.remove('hidden');
}

function closeFlagModal() {
    document.getElementById('flagModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('flagModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeFlagModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && !document.getElementById('flagModal').classList.contains('hidden')) {
        closeFlagModal();
    }
});
</script>
@endpush