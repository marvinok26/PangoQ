{{-- resources/views/livewire/pages/savings/wallet.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Savings Wallet') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-6 flex justify-between items-center">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">{{ $wallet->name }}</h1>
                            <p class="text-gray-600">Savings for {{ $trip->title }}</p>
                        </div>
                        <div class="flex space-x-3">
                            <a href="{{ route('trips.show', $trip) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                Back to Trip
                            </a>
                            
                            <a href="{{ route('trips.savings.edit', $trip) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                </svg>
                                Edit Settings
                            </a>
                        </div>
                    </div>

                    <!-- Savings Progress -->
                    <div class="bg-gray-50 p-6 rounded-lg mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Savings Progress</h3>
                        
                        <div class="flex justify-between items-center mb-2">
                            <div>
                                <p class="text-gray-600">Target Amount</p>
                                <p class="text-2xl font-bold">${{ number_format($wallet->target_amount, 2) }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Current Amount</p>
                                <p class="text-2xl font-bold">${{ number_format($wallet->current_amount, 2) }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Remaining</p>
                                <p class="text-2xl font-bold">${{ number_format($wallet->remaining_amount, 2) }}</p>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <div class="flex justify-between text-sm text-gray-600 mb-1">
                                <span>Progress</span>
                                <span>{{ $wallet->progress_percentage }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="bg-green-600 h-2.5 rounded-full" style="width: {{ $wallet->progress_percentage }}%"></div>
                            </div>
                        </div>
                        
                        <div class="flex justify-between items-center mt-6">
                            <div>
                                <p class="text-gray-600">Target Date</p>
                                <p class="text-lg font-medium">{{ $wallet->target_date->format('M j, Y') }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Contribution Frequency</p>
                                <p class="text-lg font-medium capitalize">{{ $wallet->contribution_frequency }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Suggested Contribution</p>
                                <p class="text-lg font-medium">${{ number_format($suggestedContribution, 2) }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Contribute Form -->
                    <div class="border-t border-gray-200 pt-6 mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Make a Contribution</h3>
                        
                        <form method="POST" action="{{ route('trips.savings.contribute', $trip) }}" class="space-y-4">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="amount" class="block text-sm font-medium text-gray-700">Amount</label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">$</span>
                                        </div>
                                        <input type="number" name="amount" id="amount" step="0.01" min="1" value="{{ $suggestedContribution }}"
                                            class="pl-7 block w-full pr-12 rounded-md border-gray-300 shadow-sm focus:border-secondary-500 focus:ring-secondary-500 sm:text-sm"
                                            placeholder="0.00">
                                    </div>
                                    @error('amount')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="payment_method" class="block text-sm font-medium text-gray-700">Payment Method</label>
                                    <select id="payment_method" name="payment_method" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-secondary-500 focus:ring-secondary-500 sm:text-sm">
                                        <option value="credit_card">Credit Card</option>
                                        <option value="bank_transfer">Bank Transfer</option>
                                        <option value="paypal">PayPal</option>
                                        <option value="m_pesa">M-PESA</option>
                                        <option value="cash">Cash</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="flex justify-end">
                                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-secondary-600 hover:bg-secondary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                    </svg>
                                    Make Contribution
                                </button>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Recent Transactions -->
                    <div class="border-t border-gray-200 pt-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Recent Transactions</h3>
                            <a href="{{ route('trips.savings.transactions', $trip) }}" class="text-sm font-medium text-secondary-600 hover:text-secondary-500">
                                View all transactions
                            </a>
                        </div>
                        
                        @if($transactions->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($transactions as $transaction)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $transaction->created_at->format('M j, Y g:i A') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $transaction->isDeposit() ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                        {{ ucfirst($transaction->type) }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    <span class="{{ $transaction->isDeposit() ? 'text-green-600' : 'text-yellow-600' }}">
                                                        {{ $transaction->isDeposit() ? '+' : '-' }}${{ number_format($transaction->amount, 2) }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $transaction->user->name }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        {{ ucfirst($transaction->status) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-8 text-gray-500">
                                No transactions yet.
                            </div>
                        @endif
                    </div>
                    
                    <!-- Withdrawal Section (Optional if you want to add withdrawal functionality) -->
                    @if($wallet->current_amount > 0)
                        <div class="border-t border-gray-200 pt-6 mt-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Withdraw Funds</h3>
                            
                            <form method="POST" action="{{ route('trips.savings.withdraw', $trip) }}" class="space-y-4">
                                @csrf
                                <div>
                                    <label for="withdraw_amount" class="block text-sm font-medium text-gray-700">Amount to Withdraw</label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">$</span>
                                        </div>
                                        <input type="number" name="amount" id="withdraw_amount" step="0.01" min="1" max="{{ $wallet->current_amount }}"
                                            class="pl-7 block w-full pr-12 rounded-md border-gray-300 shadow-sm focus:border-secondary-500 focus:ring-secondary-500 sm:text-sm"
                                            placeholder="0.00">
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500">Maximum amount: ${{ number_format($wallet->current_amount, 2) }}</p>
                                </div>
                                
                                <div class="flex justify-end">
                                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                        </svg>
                                        Withdraw Funds
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>