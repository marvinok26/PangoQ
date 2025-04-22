<?php

namespace App\Http\Controllers;

use App\Models\SavingsWallet;
use App\Models\Trip;
use App\Services\SavingsService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SavingsWalletController extends Controller
{
    protected SavingsService $savingsService;
    
    public function __construct(SavingsService $savingsService)
    {
        $this->middleware('tripmember');
        $this->savingsService = $savingsService;
    }
    
    public function show(Trip $trip): View
    {
        $wallet = $trip->savingsWallet;
        $transactions = $this->savingsService->getTransactionHistory($wallet, 10);
        $suggestedContribution = $this->savingsService->calculateSuggestedContribution($wallet);
        
        return view('livewire.pages.savings.wallet', compact('trip', 'wallet', 'transactions', 'suggestedContribution'));
    }
    
    public function edit(Trip $trip): View
    {
        $wallet = $trip->savingsWallet;
        
        return view('livewire.pages.savings.edit', compact('trip', 'wallet'));
    }
    
    public function update(Request $request, Trip $trip)
    {
        $wallet = $trip->savingsWallet;
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'target_amount' => 'required|numeric|min:0',
            'target_date' => 'required|date|after_or_equal:today',
            'contribution_frequency' => 'required|in:weekly,monthly',
        ]);
        
        $this->savingsService->updateWalletSettings($wallet, $validated);
        
        return redirect()->route('trips.savings.show', $trip)
            ->with('success', 'Savings wallet updated successfully!');
    }
    
    public function contribute(Request $request, Trip $trip)
    {
        $wallet = $trip->savingsWallet;
        
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'nullable|string|max:50',
        ]);
        
        $this->savingsService->deposit($wallet, auth()->id(), $validated['amount'], $validated['payment_method'] ?? null);
        
        return back()->with('success', 'Contribution added successfully!');
    }
    
    public function withdraw(Request $request, Trip $trip)
    {
        $wallet = $trip->savingsWallet;
        
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1|max:' . $wallet->current_amount,
        ]);
        
        try {
            $this->savingsService->withdraw($wallet, auth()->id(), $validated['amount']);
            return back()->with('success', 'Withdrawal processed successfully!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
    
    public function transactions(Trip $trip): View
    {
        $wallet = $trip->savingsWallet;
        $transactions = $this->savingsService->getTransactionHistory($wallet, 50);
        
        return view('livewire.pages.savings.transactions', compact('trip', 'wallet', 'transactions'));
    }
}