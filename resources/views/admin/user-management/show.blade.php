{{-- resources/views/admin/user-management/show.blade.php --}}

@extends('admin.layouts.app')

@section('title', 'User Details - ' . $user->name)
@section('page-title', 'User Details')
@section('page-description', 'Comprehensive view of user account, activities, and statistics.')

@php
    $breadcrumbs = [
        ['title' => 'Users', 'url' => route('admin.users.index')],
        ['title' => $user->name]
    ];
@endphp

@section('content')
<div class="h-full flex flex-col space-y-6">
    <!-- User Stats Cards - Fixed Height -->
    <div class="flex-shrink-0">
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-sm p-4 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-xs font-medium uppercase">Trips Created</p>
                        <p class="text-2xl font-bold">{{ $user->createdTrips->count() }}</p>
                    </div>
                    <svg class="w-8 h-8 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                    </svg>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-purple-500 to-pink-500 rounded-lg shadow-sm p-4 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-xs font-medium uppercase">Savings Wallets</p>
                        <p class="text-2xl font-bold">{{ $user->savingsWallets->count() }}</p>
                    </div>
                    <svg class="w-8 h-8 text-purple-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-emerald-500 to-teal-500 rounded-lg shadow-sm p-4 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-emerald-100 text-xs font-medium uppercase">Total Savings</p>
                        <p class="text-2xl font-bold">{{ $user->currency }}{{ number_format($user->total_savings ?? 0, 2) }}</p>
                    </div>
                    <svg class="w-8 h-8 text-emerald-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                    </svg>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-cyan-500 to-blue-500 rounded-lg shadow-sm p-4 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-cyan-100 text-xs font-medium uppercase">Transactions</p>
                        <p class="text-2xl font-bold">{{ $user->walletTransactions->count() }}</p>
                    </div>
                    <svg class="w-8 h-8 text-cyan-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid - Flexible Height -->
    <div class="flex-1 min-h-0">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 h-full">
            <!-- User Profile Sidebar - 1/4 -->
            <div class="lg:col-span-1">
                <div class="h-full flex flex-col space-y-4">
                    <!-- Profile Card -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 text-center flex-shrink-0">
                        @if($user->profile_photo_path)
                            <img class="mx-auto h-20 w-20 rounded-full object-cover" src="{{ $user->photo_url }}" alt="{{ $user->name }}">
                        @else
                            <div class="mx-auto h-20 w-20 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold text-xl">
                                {{ $user->initials }}
                            </div>
                        @endif
                        
                        <h3 class="mt-4 text-lg font-semibold text-gray-900">{{ $user->name }}</h3>
                        <p class="text-sm text-gray-600">{{ $user->email }}</p>
                        
                        <div class="mt-4 flex flex-wrap justify-center gap-2">
                            @php
                                $statusColors = [
                                    'active' => 'bg-green-100 text-green-800',
                                    'inactive' => 'bg-gray-100 text-gray-800',
                                    'suspended' => 'bg-red-100 text-red-800'
                                ];
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$user->account_status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($user->account_status) }}
                            </span>
                            @if($user->isAdmin())
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                    {{ ucfirst($user->admin_role) }}
                                </span>
                            @endif
                        </div>

                        @if($user->id !== auth()->id())
                            <button type="button" 
                                    onclick="openStatusModal('{{ $user->id }}', '{{ $user->name }}', '{{ $user->account_status }}')"
                                    class="mt-4 w-full bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-medium py-2 px-4 rounded-md transition-colors duration-200">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                Update Status
                            </button>
                        @endif
                    </div>

                    <!-- Account Information -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 flex-1 overflow-hidden">
                        <div class="px-4 py-3 border-b border-gray-200">
                            <h4 class="text-sm font-semibold text-gray-900">Account Information</h4>
                        </div>
                        <div class="p-4 overflow-y-auto">
                            <div class="space-y-3 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Account Number:</span>
                                    <code class="text-xs bg-gray-100 px-2 py-1 rounded">{{ $user->account_number }}</code>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Phone:</span>
                                    <span class="text-gray-900">{{ $user->phone_number ?? 'Not provided' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Date of Birth:</span>
                                    <span class="text-gray-900">{{ $user->date_of_birth ? $user->date_of_birth->format('M j, Y') : 'Not provided' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Gender:</span>
                                    <span class="text-gray-900">{{ $user->gender ? ucfirst($user->gender) : 'Not provided' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Nationality:</span>
                                    <span class="text-gray-900">{{ $user->nationality ?? 'Not provided' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Currency:</span>
                                    <span class="text-gray-900">{{ $user->currency }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Account Type:</span>
                                    <span class="text-gray-900">{{ ucfirst($user->account_type) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Payment Method:</span>
                                    <span class="text-gray-900">{{ ucfirst(str_replace('_', ' ', $user->preferred_payment_method)) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Daily Limit:</span>
                                    <span class="text-gray-900">{{ $user->currency }}{{ number_format($user->daily_transaction_limit ?? 0, 2) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Joined:</span>
                                    <span class="text-gray-900">{{ $user->created_at->format('M j, Y') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Last Activity:</span>
                                    <span class="text-gray-900">{{ $user->updated_at->format('M j, Y') }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Email Verified:</span>
                                    <div class="text-right">
                                        @if($user->email_verified_at)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">Verified</span>
                                            <div class="text-xs text-gray-500 mt-1">{{ $user->email_verified_at->format('M j, Y') }}</div>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">Not Verified</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Area - 3/4 -->
            <div class="lg:col-span-3">
                <div class="h-full flex flex-col space-y-4">
                    <!-- Recent Trips -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 flex-1">
                        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                            <h4 class="text-lg font-semibold text-gray-900">Recent Trips</h4>
                            @if($user->createdTrips->count() > 5)
                                <span class="text-sm text-gray-500">Showing 5 of {{ $user->createdTrips->count() }} trips</span>
                            @endif
                        </div>
                        <div class="flex-1 overflow-hidden">
                            @if($user->createdTrips->count() > 0)
                                <div class="overflow-x-auto h-full">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50 sticky top-0">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Trip</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Destination</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dates</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Budget</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($user->createdTrips->take(5) as $trip)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900">{{ $trip->title }}</div>
                                                    @if($trip->is_featured)
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">Featured</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $trip->destination }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $trip->start_date->format('M j') }} - {{ $trip->end_date->format('M j, Y') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @php
                                                        $tripStatusColors = [
                                                            'active' => 'bg-green-100 text-green-800',
                                                            'completed' => 'bg-blue-100 text-blue-800',
                                                            'cancelled' => 'bg-red-100 text-red-800',
                                                            'pending' => 'bg-yellow-100 text-yellow-800'
                                                        ];
                                                    @endphp
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $tripStatusColors[$trip->status] ?? 'bg-gray-100 text-gray-800' }}">
                                                        {{ ucfirst($trip->status) }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $trip->budget ? $user->currency . number_format($trip->budget, 2) : 'N/A' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    <a href="{{ route('admin.trips.show', $trip) }}" class="text-blue-600 hover:text-blue-900">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                        </svg>
                                                    </a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="flex items-center justify-center h-32">
                                    <div class="text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                                        </svg>
                                        <p class="mt-2 text-sm text-gray-500">No trips created yet.</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Bottom Grid: Wallets and Transactions -->
                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-4 flex-1">
                        <!-- Savings Wallets -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                                <h4 class="text-lg font-semibold text-gray-900">Savings Wallets</h4>
                                @if($user->savingsWallets->count() > 3)
                                    <span class="text-sm text-gray-500">Showing 3 of {{ $user->savingsWallets->count() }}</span>
                                @endif
                            </div>
                            <div class="p-6">
                                @if($user->savingsWallets->count() > 0)
                                    <div class="space-y-4">
                                        @foreach($user->savingsWallets->take(3) as $wallet)
                                        <div class="border border-gray-200 rounded-lg p-4">
                                            <div class="flex justify-between items-start mb-2">
                                                <h5 class="font-medium text-gray-900">{{ $wallet->name['en'] ?? $wallet->name }}</h5>
                                                @if($wallet->admin_flagged)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">Flagged</span>
                                                @endif
                                            </div>
                                            <div class="flex justify-between text-sm text-gray-600 mb-2">
                                                <span>Goal: {{ $wallet->currency }}{{ number_format($wallet->target_amount, 2) }}</span>
                                                <span>Current: {{ $wallet->currency }}{{ number_format($wallet->current_amount, 2) }}</span>
                                            </div>
                                            <div class="w-full bg-gray-200 rounded-full h-2">
                                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $wallet->progress_percentage }}%"></div>
                                            </div>
                                            <div class="flex justify-between items-center mt-2">
                                                <span class="text-xs text-gray-500">{{ $wallet->progress_percentage }}% complete</span>
                                                <a href="{{ route('admin.wallets.show', $wallet) }}" class="text-blue-600 hover:text-blue-900 text-xs">
                                                    View Details
                                                </a>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-8">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                        </svg>
                                        <p class="mt-2 text-sm text-gray-500">No savings wallets created yet.</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Recent Transactions -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                                <h4 class="text-lg font-semibold text-gray-900">Recent Transactions</h4>
                                @if($user->walletTransactions->count() > 5)
                                    <span class="text-sm text-gray-500">Showing 5 of {{ $user->walletTransactions->count() }}</span>
                                @endif
                            </div>
                            <div class="p-6">
                                @if($user->walletTransactions->count() > 0)
                                    <div class="space-y-3">
                                        @foreach($user->walletTransactions->take(5) as $transaction)
                                        <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                                            <div class="flex items-center space-x-3">
                                                <div class="flex-shrink-0">
                                                    @if($transaction->type === 'deposit')
                                                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                                            </svg>
                                                        </div>
                                                    @else
                                                        <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                                                            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 12H6"/>
                                                            </svg>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900">{{ ucfirst($transaction->type) }}</p>
                                                    <p class="text-xs text-gray-500">{{ $transaction->created_at->format('M j, Y g:i A') }}</p>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-sm font-medium text-gray-900">
                                                    {{ $user->currency }}{{ number_format($transaction->amount, 2) }}
                                                </p>
                                                @php
                                                    $transactionStatusColors = [
                                                        'completed' => 'bg-green-100 text-green-800',
                                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                                        'failed' => 'bg-red-100 text-red-800'
                                                    ];
                                                @endphp
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $transactionStatusColors[$transaction->status] ?? 'bg-gray-100 text-gray-800' }}">
                                                    {{ ucfirst($transaction->status) }}
                                                </span>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-8">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                        </svg>
                                        <p class="mt-2 text-sm text-gray-500">No transactions found.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Status Update Modal -->
<div x-data="{ open: false, userId: '', userName: '', currentStatus: '' }" 
     x-show="open" 
     @open-status-modal.window="open = true; userId = $event.detail.userId; userName = $event.detail.userName; currentStatus = $event.detail.currentStatus"
     x-transition:enter="ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-50 overflow-y-auto"
     style="display: none;">
    
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="open = false"></div>

        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
            
            <form id="statusForm" method="POST">
                @csrf
                @method('PATCH')
                
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" x-text="'Update User Status - ' + userName"></h3>
                            <div class="mt-4">
                                <label for="account_status" class="block text-sm font-medium text-gray-700">Account Status</label>
                                <select name="account_status" id="account_status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                    <option value="suspended">Suspended</option>
                                </select>
                                <p class="mt-2 text-sm text-gray-500">
                                    <strong>Active:</strong> User can use all features normally<br>
                                    <strong>Inactive:</strong> User account is disabled but not suspended<br>
                                    <strong>Suspended:</strong> User account is suspended due to violations
                                </p>
                                
                                <div class="mt-4">
                                    <label for="reason" class="block text-sm font-medium text-gray-700">Reason for Change (Optional)</label>
                                    <textarea name="reason" id="reason" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Explain why you're changing this user's status..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Update Status
                    </button>
                    <button type="button" @click="open = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openStatusModal(userId, userName, currentStatus) {
    // Update form action
    document.getElementById('statusForm').action = `/admin/users/${userId}/status`;
    
    // Set current status as selected
    document.getElementById('account_status').value = currentStatus;
    
    // Dispatch event to open modal
    window.dispatchEvent(new CustomEvent('open-status-modal', {
        detail: { userId, userName, currentStatus }
    }));
}
</script>
@endsection