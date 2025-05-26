<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SavingsWallet;
use App\Models\WalletTransaction;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class WalletController extends Controller
{
    /**
     * Display wallets list
     */
    public function index(Request $request): View
    {
        $query = SavingsWallet::with(['user', 'trip', 'flaggedBy']);

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
                })
                ->orWhere('name', 'like', "%{$search}%");
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

        // Filter by amount range
        if ($request->filled('min_amount')) {
            $query->where('current_amount', '>=', $request->min_amount);
        }

        if ($request->filled('max_amount')) {
            $query->where('current_amount', '<=', $request->max_amount);
        }

        // Filter by progress percentage
        if ($request->filled('progress_filter')) {
            switch ($request->progress_filter) {
                case 'low':
                    $query->whereRaw('(current_amount / COALESCE(custom_goal, minimum_goal)) * 100 < 25');
                    break;
                case 'medium':
                    $query->whereRaw('(current_amount / COALESCE(custom_goal, minimum_goal)) * 100 BETWEEN 25 AND 75');
                    break;
                case 'high':
                    $query->whereRaw('(current_amount / COALESCE(custom_goal, minimum_goal)) * 100 > 75');
                    break;
                case 'completed':
                    $query->whereRaw('current_amount >= COALESCE(custom_goal, minimum_goal)');
                    break;
            }
        }

        // Sorting
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');
        
        $allowedSortFields = ['created_at', 'current_amount', 'minimum_goal', 'target_date'];
        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->latest();
        }

        $wallets = $query->paginate(15);

        // Filter out orphaned wallets and log them
        $wallets->getCollection()->each(function ($wallet) {
            if (!$wallet->user) {
                \Log::warning("Wallet ID {$wallet->id} has no associated user");
            }
        });

        // Get statistics for current filter
        $stats = [
            'total_wallets' => $query->count(),
            'flagged_wallets' => (clone $query)->where('admin_flagged', true)->count(),
            'total_amount' => $query->sum('current_amount'),
            'average_amount' => $query->avg('current_amount'),
            'completed_goals' => $query->whereRaw('current_amount >= COALESCE(custom_goal, minimum_goal)')->count(),
        ];

        return view('admin.financial.wallets.index', compact('wallets', 'stats'));
    }

    /**
     * Show wallet details
     */
    public function show(SavingsWallet $wallet): View
    {
        $wallet->load(['user', 'trip', 'transactions.user', 'flaggedBy']);

        // Check if wallet has a user
        if (!$wallet->user) {
            return redirect()->route('admin.wallets.index')
                           ->with('error', 'This wallet has no associated user.');
        }

        $activities = ActivityLog::forModel(SavingsWallet::class, $wallet->id)
            ->with('user')
            ->latest()
            ->take(20)
            ->get();

        // Get wallet statistics
        $stats = [
            'total_transactions' => $wallet->transactions->count(),
            'total_deposits' => $wallet->transactions->where('type', 'deposit')->where('status', 'completed')->sum('amount'),
            'total_withdrawals' => $wallet->transactions->where('type', 'withdrawal')->where('status', 'completed')->sum('amount'),
            'pending_transactions' => $wallet->transactions->where('status', 'pending')->count(),
            'failed_transactions' => $wallet->transactions->where('status', 'failed')->count(),
            'last_transaction_date' => $wallet->transactions->where('status', 'completed')->max('created_at'),
            'days_to_target' => $wallet->target_date ? $wallet->target_date->diffInDays(now()) : null,
        ];

        return view('admin.financial.wallets.show', compact('wallet', 'activities', 'stats'));
    }

    /**
     * Toggle wallet flag
     */
    public function toggleFlag(Request $request, SavingsWallet $wallet): RedirectResponse
    {
        if ($wallet->admin_flagged) {
            // Clear flag
            $wallet->clearFlag();
            $message = 'Wallet flag cleared successfully.';
        } else {
            // Flag wallet
            $request->validate([
                'reason' => 'nullable|string|max:255'
            ]);

            $reason = $request->input('reason', 'Flagged for review by admin');
            $wallet->flagForReview($reason);
            $message = 'Wallet flagged for review successfully.';
        }

        return back()->with('success', $message);
    }

    /**
     * Bulk actions for wallets
     */
    public function bulkAction(Request $request): RedirectResponse
    {
        $request->validate([
            'action' => 'required|in:flag,unflag,export',
            'wallet_ids' => 'required|array',
            'wallet_ids.*' => 'exists:savings_wallets,id',
            'reason' => 'nullable|string|max:255'
        ]);

        $wallets = SavingsWallet::whereIn('id', $request->wallet_ids);
        $count = $wallets->count();

        DB::beginTransaction();
        try {
            switch ($request->action) {
                case 'flag':
                    $reason = $request->input('reason', 'Bulk flagged by admin');
                    $wallets->each(function($wallet) use ($reason) {
                        if (!$wallet->admin_flagged) {
                            $wallet->flagForReview($reason);
                        }
                    });
                    $message = "{$count} wallets flagged successfully.";
                    break;

                case 'unflag':
                    $wallets->each(function($wallet) {
                        if ($wallet->admin_flagged) {
                            $wallet->clearFlag();
                        }
                    });
                    $message = "{$count} wallets unflagged successfully.";
                    break;

                case 'export':
                    // TODO: Implement wallet export functionality
                    $message = "Export functionality will be implemented soon.";
                    break;
            }

            ActivityLog::log('wallets_bulk_action', null, [
                'action' => $request->action,
                'wallet_ids' => $request->wallet_ids,
                'count' => $count,
                'reason' => $request->input('reason')
            ]);

            DB::commit();

            return back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Bulk action failed: ' . $e->getMessage());
        }
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
                  })
                  ->orWhereHas('wallet.trip', function($tripQuery) use ($search) {
                      $tripQuery->where('title', 'like', "%{$search}%");
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

        // Filter by payment method
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        // Filter by amount range
        if ($request->filled('min_amount')) {
            $query->where('amount', '>=', $request->min_amount);
        }

        if ($request->filled('max_amount')) {
            $query->where('amount', '<=', $request->max_amount);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $transactions = $query->latest()->paginate(20);

        // Get summary statistics for the current filter
        $stats = [
            'total_transactions' => $query->count(),
            'total_amount' => $query->where('status', 'completed')->sum('amount'),
            'pending_count' => $query->where('status', 'pending')->count(),
            'completed_count' => $query->where('status', 'completed')->count(),
            'failed_count' => $query->where('status', 'failed')->count(),
            'deposits_count' => $query->where('type', 'deposit')->count(),
            'withdrawals_count' => $query->where('type', 'withdrawal')->count(),
        ];

        return view('admin.financial.transactions.index', compact('transactions', 'stats'));
    }

    /**
     * Get wallet analytics
     */
    public function analytics(): View
    {
        $analytics = [
            'wallet_creation_trend' => SavingsWallet::selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->where('created_at', '>=', now()->subDays(30))
                ->groupBy('date')
                ->orderBy('date')
                ->get(),

            'savings_growth' => SavingsWallet::selectRaw('DATE(created_at) as date, SUM(current_amount) as total')
                ->where('created_at', '>=', now()->subDays(30))
                ->groupBy('date')
                ->orderBy('date')
                ->get(),

            'currency_distribution' => SavingsWallet::selectRaw('currency, COUNT(*) as count, SUM(current_amount) as total')
                ->groupBy('currency')
                ->get(),

            'completion_rates' => [
                'completed' => SavingsWallet::whereRaw('current_amount >= COALESCE(custom_goal, minimum_goal)')->count(),
                'in_progress' => SavingsWallet::whereRaw('current_amount < COALESCE(custom_goal, minimum_goal)')->count(),
                'percentage' => SavingsWallet::whereRaw('current_amount >= COALESCE(custom_goal, minimum_goal)')->count() / max(SavingsWallet::count(), 1) * 100,
            ],

            'average_savings' => SavingsWallet::avg('current_amount'),
            'total_savings' => SavingsWallet::sum('current_amount'),
            'flagged_percentage' => SavingsWallet::where('admin_flagged', true)->count() / max(SavingsWallet::count(), 1) * 100,
        ];

        return view('admin.financial.wallets.analytics', compact('analytics'));
    }
}