{{-- resources/views/admin/financial/transactions/index.blade.php --}}

@extends('admin.layouts.app')

@section('title', 'Transactions Management')
@section('page-title', 'Wallet Transactions')

@section('content')
<!-- Search and Filter Section -->
<div class="mb-6 flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
    <!-- Search Form -->
    <div class="flex-1 max-w-md">
        <form method="GET" action="{{ route('admin.transactions.index') }}" class="flex space-x-2">
            <input type="text" 
                   name="search" 
                   class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm" 
                   placeholder="Search transactions..." 
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
        <a href="{{ route('admin.transactions.index') }}" 
           class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md border transition-colors duration-200 {{ !request()->hasAny(['type', 'status']) ? 'border-gray-800 bg-gray-800 text-white' : 'border-gray-300 text-gray-700 bg-white hover:bg-gray-50' }}">
            All Transactions
        </a>
        <a href="{{ route('admin.transactions.index', ['type' => 'deposit']) }}" 
           class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md border transition-colors duration-200 {{ request('type') === 'deposit' ? 'border-green-600 bg-green-600 text-white' : 'border-green-300 text-green-700 bg-green-50 hover:bg-green-100' }}">
            Deposits
        </a>
        <a href="{{ route('admin.transactions.index', ['type' => 'withdrawal']) }}" 
           class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md border transition-colors duration-200 {{ request('type') === 'withdrawal' ? 'border-yellow-600 bg-yellow-600 text-white' : 'border-yellow-300 text-yellow-700 bg-yellow-50 hover:bg-yellow-100' }}">
            Withdrawals
        </a>
        <a href="{{ route('admin.transactions.index', ['status' => 'failed']) }}" 
           class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md border transition-colors duration-200 {{ request('status') === 'failed' ? 'border-red-600 bg-red-600 text-white' : 'border-red-300 text-red-700 bg-red-50 hover:bg-red-100' }}">
            Failed
        </a>
    </div>
</div>

<!-- Transactions Table -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200">
    <div class="p-6">
        @if($transactions->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reference</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Wallet</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Method</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($transactions as $transaction)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-4 py-4 whitespace-nowrap">
                                <code class="text-xs bg-gray-100 px-2 py-1 rounded">{{ $transaction->transaction_reference ?? 'TXN-' . $transaction->id }}</code>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                @if($transaction->user)
                                    <div>
                                        <a href="{{ route('admin.users.show', $transaction->user) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                            {{ $transaction->user->name }}
                                        </a>
                                        <div class="text-xs text-gray-500">{{ $transaction->user->email }}</div>
                                    </div>
                                @else
                                    <span class="text-red-600 flex items-center text-sm">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                        </svg>
                                        No User
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                @if($transaction->wallet)
                                    <div>
                                        <a href="{{ route('admin.wallets.show', $transaction->wallet) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                            {{ is_array($transaction->wallet->name) ? ($transaction->wallet->name['en'] ?? 'Wallet') : $transaction->wallet->name }}
                                        </a>
                                        @if($transaction->wallet->trip)
                                            <div class="text-xs text-gray-500">
                                                <a href="{{ route('admin.trips.show', $transaction->wallet->trip) }}" class="text-gray-500 hover:text-gray-700">
                                                    {{ Str::limit($transaction->wallet->trip->title, 20) }}
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-red-600 flex items-center text-sm">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                        </svg>
                                        No Wallet
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $transaction->type === 'deposit' ? 'green' : 'yellow' }}-100 text-{{ $transaction->type === 'deposit' ? 'green' : 'yellow' }}-800">
                                    {{ ucfirst($transaction->type) }}
                                </span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                {{ format_currency($transaction->amount, $transaction->wallet->currency ?? 'USD') }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="{{ admin_status_badge($transaction->status) }}">
                                    {{ ucfirst($transaction->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $transaction->payment_method ? ucfirst(str_replace('_', ' ', $transaction->payment_method)) : 'N/A' }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ format_admin_date($transaction->created_at, 'M j, Y') }}</div>
                                <div class="text-xs text-gray-500">{{ format_admin_date($transaction->created_at, 'g:i A') }}</div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="flex space-x-2">
                                    <button type="button" 
                                            onclick="openDetailsModal('{{ $transaction->id }}')"
                                            class="inline-flex items-center p-1.5 border border-blue-300 rounded text-blue-600 hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </button>
                                    @if($transaction->status === 'pending')
                                        <button type="button" 
                                                onclick="openProcessModal('{{ $transaction->id }}')"
                                                class="inline-flex items-center p-1.5 border border-green-300 rounded text-green-600 hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6 flex justify-center">
                {{ $transactions->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
                <h4 class="mt-4 text-lg font-medium text-gray-900">No transactions found</h4>
                <p class="mt-2 text-gray-600">Try adjusting your search criteria.</p>
            </div>
        @endif
    </div>
</div>

<!-- Transaction Details Modal -->
<div id="detailsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-20 mx-auto p-5 border max-w-md shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <!-- Modal Header -->
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Transaction Details</h3>
                <button type="button" onclick="closeDetailsModal()" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <!-- Modal Content -->
            <div id="detailsContent" class="space-y-3">
                <!-- Content will be populated by JavaScript -->
            </div>
            
            <!-- Modal Actions -->
            <div class="mt-6 flex justify-end">
                <button type="button" onclick="closeDetailsModal()" class="inline-flex justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Process Transaction Modal -->
<div id="processModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-20 mx-auto p-5 border max-w-md shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <!-- Modal Header -->
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Process Transaction</h3>
                <button type="button" onclick="closeProcessModal()" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <!-- Modal Form -->
            <form id="processForm" method="POST" action="">
                @csrf
                @method('PATCH')
                
                <div class="mb-6">
                    <div id="transactionInfo" class="rounded-md bg-blue-50 p-4 mb-4">
                        <!-- Transaction info will be populated by JavaScript -->
                    </div>
                    
                    <div class="mb-4">
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status" id="status" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                            <option value="completed">Approve & Complete</option>
                            <option value="failed">Reject & Mark Failed</option>
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label for="admin_notes" class="block text-sm font-medium text-gray-700 mb-2">Admin Notes</label>
                        <textarea name="admin_notes" id="admin_notes" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500" rows="3" placeholder="Add processing notes..."></textarea>
                    </div>
                </div>
                
                <!-- Modal Actions -->
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeProcessModal()" class="inline-flex justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                        Cancel
                    </button>
                    <button type="submit" class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                        Process Transaction
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const transactions = {
    @foreach($transactions as $transaction)
        {{ $transaction->id }}: {
            id: {{ $transaction->id }},
            reference: @json($transaction->transaction_reference ?? 'TXN-' . $transaction->id),
            user_name: @json($transaction->user ? $transaction->user->name : 'N/A'),
            amount: @json(format_currency($transaction->amount, $transaction->wallet->currency ?? 'USD')),
            type: @json(ucfirst($transaction->type)),
            status: @json(ucfirst($transaction->status)),
            payment_method: @json($transaction->payment_method ?? 'N/A'),
            created_at: @json(format_admin_date($transaction->created_at)),
            admin_notes: @json($transaction->admin_notes),
            process_url: @json($transaction->status === 'pending' ? route('admin.transactions.process', $transaction) : null)
        }@if(!$loop->last),@endif
    @endforeach
};

function openDetailsModal(transactionId) {
    const transaction = transactions[transactionId];
    if (!transaction) return;
    
    const detailsContent = document.getElementById('detailsContent');
    detailsContent.innerHTML = `
        <div class="space-y-3">
            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                <span class="text-sm font-medium text-gray-500">Reference:</span>
                <code class="text-xs bg-gray-100 px-2 py-1 rounded">${transaction.reference}</code>
            </div>
            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                <span class="text-sm font-medium text-gray-500">User:</span>
                <span class="text-sm text-gray-900">${transaction.user_name}</span>
            </div>
            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                <span class="text-sm font-medium text-gray-500">Amount:</span>
                <span class="text-sm font-semibold text-gray-900">${transaction.amount}</span>
            </div>
            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                <span class="text-sm font-medium text-gray-500">Type:</span>
                <span class="text-sm text-gray-900">${transaction.type}</span>
            </div>
            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                <span class="text-sm font-medium text-gray-500">Status:</span>
                <span class="text-sm text-gray-900">${transaction.status}</span>
            </div>
            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                <span class="text-sm font-medium text-gray-500">Payment Method:</span>
                <span class="text-sm text-gray-900">${transaction.payment_method}</span>
            </div>
            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                <span class="text-sm font-medium text-gray-500">Created:</span>
                <span class="text-sm text-gray-900">${transaction.created_at}</span>
            </div>
            ${transaction.admin_notes ? `
                <div class="flex justify-between items-start py-2">
                    <span class="text-sm font-medium text-gray-500">Admin Notes:</span>
                    <span class="text-sm text-gray-900 text-right max-w-48">${transaction.admin_notes}</span>
                </div>
            ` : ''}
        </div>
    `;
    
    document.getElementById('detailsModal').classList.remove('hidden');
}

function closeDetailsModal() {
    document.getElementById('detailsModal').classList.add('hidden');
}

function openProcessModal(transactionId) {
    const transaction = transactions[transactionId];
    if (!transaction || !transaction.process_url) return;
    
    const transactionInfo = document.getElementById('transactionInfo');
    const processForm = document.getElementById('processForm');
    
    transactionInfo.innerHTML = `
        <div class="flex">
            <svg class="h-5 w-5 text-blue-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
            </svg>
            <div>
                <h3 class="text-sm font-medium text-blue-800">Transaction:</h3>
                <p class="text-sm text-blue-700">${transaction.amount} ${transaction.type}</p>
            </div>
        </div>
    `;
    
    processForm.action = transaction.process_url;
    document.getElementById('processModal').classList.remove('hidden');
}

function closeProcessModal() {
    document.getElementById('processModal').classList.add('hidden');
    document.getElementById('processForm').reset();
}

// Close modals when clicking outside
document.getElementById('detailsModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDetailsModal();
    }
});

document.getElementById('processModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeProcessModal();
    }
});

// Close modals with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        if (!document.getElementById('detailsModal').classList.contains('hidden')) {
            closeDetailsModal();
        }
        if (!document.getElementById('processModal').classList.contains('hidden')) {
            closeProcessModal();
        }
    }
});
</script>
@endpush