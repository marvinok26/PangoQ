<?php

namespace App\Http\Controllers;

use App\Models\SavingsWallet;
use App\Models\Trip;
use App\Models\WalletTransaction;
use App\Services\SavingsService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class SavingsWalletController extends Controller
{
    protected $savingsService;

    public function __construct(SavingsService $savingsService)
    {
        $this->savingsService = $savingsService;
    }

    /**
     * Display the user's savings wallet overview.
     */
    public function index(): View
    {
        $user = Auth::user();
        
        // Get all wallets the user has access to
        $wallets = SavingsWallet::whereHas('trip.members', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->with('trip')
        ->get();
        
        // Calculate totals
        $totalBalance = $wallets->sum('current_amount');
        $totalTarget = $wallets->sum('target_amount');
        $progressPercentage = $totalTarget > 0 ? min(100, round(($totalBalance / $totalTarget) * 100)) : 0;
        
        return view('livewire.pages.savings.index', compact('wallets', 'totalBalance', 'totalTarget', 'progressPercentage'));
    }

    /**
     * Show the savings wallet for a specific trip.
     */
    public function show(Trip $trip): View
    {
        // Check if user is a member of this trip
        if (! Gate::allows('view', $trip)) {
            throw new AuthorizationException('You are not authorized to view this trip\'s wallet.');
        }
        
        // Get the wallet
        $wallet = $trip->savingsWallet;
        
        // If no wallet exists yet, return a view to create one
        if (!$wallet) {
            return view('livewire.pages.savings.start', compact('trip'));
        }
        
        // Get recent transactions
        $transactions = WalletTransaction::where('wallet_id', $wallet->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        return view('livewire.pages.savings.show', compact('trip', 'wallet', 'transactions'));
    }

    /**
     * Display the form to contribute to a wallet.
     */
    public function showContributeForm(): View
    {
        $user = Auth::user();
        
        // Get all wallets the user has access to
        $wallets = SavingsWallet::whereHas('trip.members', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->with('trip')
        ->get();
        
        // Get payment methods (if implemented)
        $paymentMethods = [
            'mpesa' => 'M-Pesa',
            'card' => 'Credit/Debit Card',
            'bank' => 'Bank Transfer'
        ];
        
        return view('livewire.pages.savings.contribute', compact('wallets', 'paymentMethods'));
    }

    /**
     * Process a contribution to a wallet.
     */
    public function contribute(Request $request)
    {
        // Validate request
        $validated = $request->validate([
            'wallet_id' => 'required|exists:savings_wallets,id',
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|string',
        ]);
        
        // Check if user is authorized to contribute to this wallet
        $wallet = SavingsWallet::findOrFail($validated['wallet_id']);
        
        if (! Gate::allows('contribute', $wallet)) {
            throw new AuthorizationException('You are not authorized to contribute to this wallet.');
        }
        
        // Process the contribution
        try {
            $this->savingsService->contribute(
                $wallet->id, 
                Auth::id(), 
                $validated['amount'], 
                $validated['payment_method']
            );
            
            return redirect()->route('wallet.index')
                ->with('success', 'Contribution of $' . $validated['amount'] . ' was successful.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Display the form to withdraw from a wallet.
     */
    public function showWithdrawForm(): View
    {
        $user = Auth::user();
        
        // Get all wallets the user has access to
        $wallets = SavingsWallet::whereHas('trip.members', function($query) use ($user) {
            $query->where('user_id', $user->id)
                ->where('role', 'organizer'); // Only organizers can withdraw
        })
        ->with('trip')
        ->get();
        
        return view('livewire.pages.savings.withdraw', compact('wallets'));
    }

    /**
     * Process a withdrawal from a wallet.
     */
    public function withdraw(Request $request)
    {
        // Validate request
        $validated = $request->validate([
            'wallet_id' => 'required|exists:savings_wallets,id',
            'amount' => 'required|numeric|min:1',
            'reason' => 'nullable|string',
        ]);
        
        // Check if user is authorized to withdraw from this wallet
        $wallet = SavingsWallet::findOrFail($validated['wallet_id']);
        
        if (! Gate::allows('withdraw', $wallet)) {
            throw new AuthorizationException('You are not authorized to withdraw from this wallet.');
        }
        
        // Process the withdrawal
        try {
            $this->savingsService->withdraw(
                $wallet->id, 
                Auth::id(), 
                $validated['amount']
            );
            
            return redirect()->route('wallet.index')
                ->with('success', 'Withdrawal of $' . $validated['amount'] . ' was successful.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Display transaction history.
     */
    public function transactions(): View
    {
        $user = Auth::user();
        
        // Get all transactions for wallets the user has access to
        $transactions = WalletTransaction::whereHas('wallet.trip.members', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->with(['wallet.trip', 'user'])
        ->orderBy('created_at', 'desc')
        ->paginate(15);
        
        return view('livewire.pages.savings.transactions', compact('transactions'));
    }

    /**
     * Set up a new savings wallet for a trip.
     */
    public function start(Request $request, Trip $trip)
    {
        // Check if user is authorized to set up a wallet for this trip
        if (! Gate::allows('update', $trip)) {
            throw new AuthorizationException('You are not authorized to set up a wallet for this trip.');
        }
        
        // Validate request
        $validated = $request->validate([
            'target_amount' => 'required|numeric|min:1',
            'target_date' => 'nullable|date|after:today',
            'contribution_frequency' => 'required|in:weekly,monthly',
        ]);
        
        // Create the wallet
        $wallet = new SavingsWallet();
        $wallet->trip_id = $trip->id;
        $wallet->name = $trip->title . ' Savings';
        $wallet->target_amount = $validated['target_amount'];
        $wallet->current_amount = 0;
        $wallet->target_date = $validated['target_date'] ?? null;
        $wallet->contribution_frequency = $validated['contribution_frequency'];
        $wallet->save();
        
        return redirect()->route('trips.savings', $trip)
            ->with('success', 'Savings wallet created successfully!');
    }
}