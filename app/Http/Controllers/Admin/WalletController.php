<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SavingsWallet;
use App\Models\WalletTransaction;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class WalletController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * Display wallets list
     */
    public function index(Request $request): View
    {
        $query = SavingsWallet::with(['user', 'trip']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%")
                             ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhereHas('trip', function($tripQuery) use ($search) {
                    $tripQuery->where('title', 'like', "%{$search}%");
                });
            });
        }

        // Filter by flagged status
        if ($request->filled('flagged')) {
            $query->where('admin_flagged', $request->flagged);
        }

        // Filter by currency
        if ($request->filled('currency')) {
            $query->where('currency', $request->currency);
        }

        $wallets = $query->latest()->paginate(15);

        return view('admin.wallets.index', compact('wallets'));
    }

    /**
     * Show wallet details
     */
    public function show(SavingsWallet $wallet): View
    {
        $wallet->load(['user', 'trip', 'transactions.user']);
        
        $activities = ActivityLog::forModel(SavingsWallet::class, $wallet->id)
            ->with('user')
            ->latest()
            ->take(20)
            ->get();

        return view('admin.wallets.show', compact('wallet', 'activities'));
    }

    /**
     * Toggle wallet flag
     */
    public function toggleFlag(Request $request, SavingsWallet $wallet): RedirectResponse
    {
        if ($wallet->admin_flagged) {
            $wallet->clearFlag();
            $message = 'Wallet flag cleared.';
        } else {
            $reason = $request->input('reason', 'Flagged for review');
            $wallet->flagForReview($reason);
            $message = 'Wallet flagged for review.';
        }

        return back()->with('success', $message);
    }

    /**
     * Display transactions
     */
    public function transactions(Request $request): View
    {
        $query = WalletTransaction::with(['user', 'wallet.trip']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('transaction_reference', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $transactions = $query->latest()->paginate(15);

        return view('admin.transactions.index', compact('transactions'));
    }
}