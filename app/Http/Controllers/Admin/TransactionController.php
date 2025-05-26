<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WalletTransaction;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TransactionController extends Controller
{
    /**
     * Display transactions list
     */
    public function index(Request $request): View
    {
        $query = WalletTransaction::with(['user', 'wallet.trip']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('transaction_reference', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%")
                               ->orWhere('account_number', 'like', "%{$search}%");
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

        // Filter by currency
        if ($request->filled('currency')) {
            $query->whereHas('wallet', function($walletQuery) use ($request) {
                $walletQuery->where('currency', $request->currency);
            });
        }

        // Filter by high value transactions
        if ($request->filled('high_value')) {
            $query->where('amount', '>', 5000);
        }

        // Sorting
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');
        
        $allowedSortFields = ['created_at', 'amount', 'status', 'type', 'processed_at'];
        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->latest();
        }

        $transactions = $query->paginate(20);

        // Get summary statistics for the current filter
        $baseQuery = clone $query;
        $stats = [
            'total_transactions' => $baseQuery->count(),
            'total_amount' => $baseQuery->where('status', 'completed')->sum('amount'),
            'pending_count' => $baseQuery->where('status', 'pending')->count(),
            'completed_count' => $baseQuery->where('status', 'completed')->count(),
            'failed_count' => $baseQuery->where('status', 'failed')->count(),
            'cancelled_count' => $baseQuery->where('status', 'cancelled')->count(),
            'deposits_count' => $baseQuery->where('type', 'deposit')->count(),
            'withdrawals_count' => $baseQuery->where('type', 'withdrawal')->count(),
            'deposits_amount' => $baseQuery->where('type', 'deposit')->where('status', 'completed')->sum('amount'),
            'withdrawals_amount' => $baseQuery->where('type', 'withdrawal')->where('status', 'completed')->sum('amount'),
            'average_amount' => $baseQuery->where('status', 'completed')->avg('amount'),
            'today_count' => $baseQuery->whereDate('created_at', today())->count(),
            'today_amount' => $baseQuery->whereDate('created_at', today())->where('status', 'completed')->sum('amount'),
        ];

        // Get unique payment methods for filter
        $paymentMethods = WalletTransaction::distinct()
            ->whereNotNull('payment_method')
            ->pluck('payment_method')
            ->filter()
            ->sort();

        return view('admin.financial.transactions.index', compact('transactions', 'stats', 'paymentMethods'));
    }

    /**
     * Show transaction details
     */
    public function show(WalletTransaction $transaction): View
    {
        $transaction->load(['user', 'wallet.trip', 'wallet.user']);
        
        $activities = ActivityLog::forModel(WalletTransaction::class, $transaction->id)
            ->with('user')
            ->latest()
            ->take(20)
            ->get();

        // Get related transactions (same user, same wallet)
        $relatedTransactions = WalletTransaction::where('wallet_id', $transaction->wallet_id)
            ->where('id', '!=', $transaction->id)
            ->with(['user'])
            ->latest()
            ->take(10)
            ->get();

        // Get user's recent transactions
        $userTransactions = WalletTransaction::where('user_id', $transaction->user_id)
            ->where('id', '!=', $transaction->id)
            ->with(['wallet.trip'])
            ->latest()
            ->take(10)
            ->get();

        // Calculate transaction statistics for this user
        $userStats = [
            'total_transactions' => WalletTransaction::where('user_id', $transaction->user_id)->count(),
            'completed_transactions' => WalletTransaction::where('user_id', $transaction->user_id)
                ->where('status', 'completed')->count(),
            'total_deposited' => WalletTransaction::where('user_id', $transaction->user_id)
                ->where('type', 'deposit')->where('status', 'completed')->sum('amount'),
            'total_withdrawn' => WalletTransaction::where('user_id', $transaction->user_id)
                ->where('type', 'withdrawal')->where('status', 'completed')->sum('amount'),
            'failed_transactions' => WalletTransaction::where('user_id', $transaction->user_id)
                ->where('status', 'failed')->count(),
        ];

        return view('admin.financial.transactions.show', compact(
            'transaction', 
            'activities', 
            'relatedTransactions', 
            'userTransactions',
            'userStats'
        ));
    }

    /**
     * Update transaction status
     */
    public function updateStatus(Request $request, WalletTransaction $transaction): RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:pending,completed,failed,cancelled',
            'admin_notes' => 'nullable|string|max:1000',
            'reason' => 'nullable|string|max:255'
        ]);

        $oldStatus = $transaction->status;

        DB::beginTransaction();
        try {
            $transaction->update([
                'status' => $request->status,
                'admin_notes' => $request->admin_notes,
                'processed_at' => now(),
                'processed_by' => auth()->id()
            ]);

            // If marking as completed, update wallet balance
            if ($request->status === 'completed' && $oldStatus !== 'completed') {
                $wallet = $transaction->wallet;
                if ($wallet) {
                    if ($transaction->type === 'deposit') {
                        $wallet->increment('current_amount', $transaction->amount);
                    } elseif ($transaction->type === 'withdrawal') {
                        // Check if wallet has sufficient balance
                        if ($wallet->current_amount >= $transaction->amount) {
                            $wallet->decrement('current_amount', $transaction->amount);
                        } else {
                            throw new \Exception('Insufficient wallet balance for withdrawal');
                        }
                    }
                }
            }

            // If reverting from completed, revert wallet balance
            if ($oldStatus === 'completed' && $request->status !== 'completed') {
                $wallet = $transaction->wallet;
                if ($wallet) {
                    if ($transaction->type === 'deposit') {
                        $wallet->decrement('current_amount', $transaction->amount);
                    } elseif ($transaction->type === 'withdrawal') {
                        $wallet->increment('current_amount', $transaction->amount);
                    }
                }
            }

            ActivityLog::log('transaction_status_updated', $transaction, [
                'old_status' => $oldStatus,
                'new_status' => $request->status,
                'admin_notes' => $request->admin_notes,
                'reason' => $request->reason,
                'amount' => $transaction->amount,
                'type' => $transaction->type,
                'wallet_id' => $transaction->wallet_id,
                'user_id' => $transaction->user_id
            ]);

            DB::commit();
            
            return back()->with('success', 'Transaction status updated successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Failed to update transaction status: ' . $e->getMessage());
        }
    }

    /**
     * Bulk update transaction statuses
     */
    public function bulkUpdateStatus(Request $request): RedirectResponse
    {
        $request->validate([
            'action' => 'required|in:approve,reject,cancel',
            'transaction_ids' => 'required|array',
            'transaction_ids.*' => 'exists:wallet_transactions,id',
            'admin_notes' => 'nullable|string|max:1000'
        ]);

        $transactions = WalletTransaction::with('wallet')->whereIn('id', $request->transaction_ids)->get();
        $count = $transactions->count();

        $statusMap = [
            'approve' => 'completed',
            'reject' => 'failed',
            'cancel' => 'cancelled'
        ];

        $newStatus = $statusMap[$request->action];

        DB::beginTransaction();
        try {
            $successCount = 0;
            $errorMessages = [];

            foreach ($transactions as $transaction) {
                try {
                    $oldStatus = $transaction->status;
                    
                    $transaction->update([
                        'status' => $newStatus,
                        'admin_notes' => $request->admin_notes,
                        'processed_at' => now(),
                        'processed_by' => auth()->id()
                    ]);

                    // Update wallet balance if approving
                    if ($request->action === 'approve' && $oldStatus !== 'completed') {
                        $wallet = $transaction->wallet;
                        if ($wallet) {
                            if ($transaction->type === 'deposit') {
                                $wallet->increment('current_amount', $transaction->amount);
                            } elseif ($transaction->type === 'withdrawal') {
                                if ($wallet->current_amount >= $transaction->amount) {
                                    $wallet->decrement('current_amount', $transaction->amount);
                                } else {
                                    $errorMessages[] = "Transaction {$transaction->id}: Insufficient wallet balance";
                                    continue;
                                }
                            }
                        }
                    }

                    $successCount++;

                } catch (\Exception $e) {
                    $errorMessages[] = "Transaction {$transaction->id}: " . $e->getMessage();
                }
            }

            ActivityLog::log('transactions_bulk_status_update', null, [
                'action' => $request->action,
                'new_status' => $newStatus,
                'transaction_ids' => $request->transaction_ids,
                'success_count' => $successCount,
                'total_count' => $count,
                'admin_notes' => $request->admin_notes,
                'errors' => $errorMessages
            ]);

            DB::commit();

            $message = "Successfully {$request->action}d {$successCount} out of {$count} transactions.";
            
            if (!empty($errorMessages)) {
                $message .= " Errors: " . implode(', ', $errorMessages);
                return back()->with('warning', $message);
            }

            return back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Bulk update failed: ' . $e->getMessage());
        }
    }

    /**
     * Export transactions
     */
    public function export(Request $request): RedirectResponse
    {
        $request->validate([
            'format' => 'required|in:csv,excel',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'include_user_data' => 'boolean',
            'status_filter' => 'nullable|in:pending,completed,failed,cancelled',
            'type_filter' => 'nullable|in:deposit,withdrawal'
        ]);

        // TODO: Implement export functionality
        // This would typically generate a CSV or Excel file with filtered transactions
        
        ActivityLog::log('transactions_export_requested', null, [
            'format' => $request->format,
            'date_from' => $request->date_from,
            'date_to' => $request->date_to,
            'include_user_data' => $request->include_user_data,
            'status_filter' => $request->status_filter,
            'type_filter' => $request->type_filter,
            'requested_by' => auth()->user()->name,
            'filters_applied' => $request->except(['format', '_token'])
        ]);

        return back()->with('info', 'Export functionality will be implemented soon. Your request has been logged.');
    }

    /**
     * Flag suspicious transactions
     */
    public function suspicious(Request $request): View
    {
        $period = $request->input('period', 7); // days

        // Large amount transactions
        $largeTransactions = WalletTransaction::where('amount', '>', 5000)
            ->where('created_at', '>=', now()->subDays($period))
            ->with(['user', 'wallet.trip'])
            ->latest()
            ->get();

        // Multiple transactions from same user in short time
        $rapidTransactions = WalletTransaction::selectRaw('user_id, COUNT(*) as count, SUM(amount) as total')
            ->where('created_at', '>=', now()->subHours(24))
            ->groupBy('user_id')
            ->havingRaw('COUNT(*) > 5')
            ->with('user')
            ->orderByDesc('count')
            ->get();

        // Failed transactions pattern
        $failedPatterns = WalletTransaction::selectRaw('user_id, COUNT(*) as failed_count')
            ->where('status', 'failed')
            ->where('created_at', '>=', now()->subDays($period))
            ->groupBy('user_id')
            ->havingRaw('COUNT(*) > 3')
            ->with('user')
            ->orderByDesc('failed_count')
            ->get();

        // Round number transactions (potentially suspicious)
        $roundAmountTransactions = WalletTransaction::whereRaw('amount % 1000 = 0')
            ->where('amount', '>', 1000)
            ->where('created_at', '>=', now()->subDays($period))
            ->with(['user', 'wallet.trip'])
            ->latest()
            ->take(50)
            ->get();

        // Unusual time transactions (outside business hours)
        $unusualTimeTransactions = WalletTransaction::whereRaw('HOUR(created_at) < 6 OR HOUR(created_at) > 22')
            ->where('amount', '>', 1000)
            ->where('created_at', '>=', now()->subDays($period))
            ->with(['user', 'wallet.trip'])
            ->latest()
            ->take(50)
            ->get();

        // Same amount transactions (potential automated behavior)
        $sameAmountTransactions = WalletTransaction::selectRaw('user_id, amount, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays($period))
            ->groupBy(['user_id', 'amount'])
            ->havingRaw('COUNT(*) > 3')
            ->with('user')
            ->orderByDesc('count')
            ->get();

        $suspiciousData = [
            'large_transactions' => $largeTransactions,
            'rapid_transactions' => $rapidTransactions,
            'failed_patterns' => $failedPatterns,
            'round_amount_transactions' => $roundAmountTransactions,
            'unusual_time_transactions' => $unusualTimeTransactions,
            'same_amount_transactions' => $sameAmountTransactions,
            'period' => $period
        ];

        return view('admin.financial.transactions.suspicious', compact('suspiciousData'));
    }

    /**
     * Get transaction summary for specific time period
     */
    public function summary(Request $request): View
    {
        $request->validate([
            'period' => 'required|in:today,yesterday,this_week,last_week,this_month,last_month,custom',
            'custom_from' => 'nullable|required_if:period,custom|date',
            'custom_to' => 'nullable|required_if:period,custom|date|after_or_equal:custom_from'
        ]);

        if ($request->period === 'custom') {
            $dateRange = [
                'from' => Carbon::parse($request->custom_from)->startOfDay(),
                'to' => Carbon::parse($request->custom_to)->endOfDay()
            ];
        } else {
            $dateRange = $this->getDateRange($request->period);
        }
        
        $summary = [
            'period' => $request->period,
            'date_from' => $dateRange['from'],
            'date_to' => $dateRange['to'],
            'total_transactions' => WalletTransaction::whereBetween('created_at', [$dateRange['from'], $dateRange['to']])->count(),
            'completed_transactions' => WalletTransaction::whereBetween('created_at', [$dateRange['from'], $dateRange['to']])
                ->where('status', 'completed')->count(),
            'total_amount' => WalletTransaction::whereBetween('created_at', [$dateRange['from'], $dateRange['to']])
                ->where('status', 'completed')->sum('amount'),
            'deposits_count' => WalletTransaction::whereBetween('created_at', [$dateRange['from'], $dateRange['to']])
                ->where('type', 'deposit')->where('status', 'completed')->count(),
            'deposits_amount' => WalletTransaction::whereBetween('created_at', [$dateRange['from'], $dateRange['to']])
                ->where('type', 'deposit')->where('status', 'completed')->sum('amount'),
            'withdrawals_count' => WalletTransaction::whereBetween('created_at', [$dateRange['from'], $dateRange['to']])
                ->where('type', 'withdrawal')->where('status', 'completed')->count(),
            'withdrawals_amount' => WalletTransaction::whereBetween('created_at', [$dateRange['from'], $dateRange['to']])
                ->where('type', 'withdrawal')->where('status', 'completed')->sum('amount'),
            'pending_count' => WalletTransaction::whereBetween('created_at', [$dateRange['from'], $dateRange['to']])
                ->where('status', 'pending')->count(),
            'failed_count' => WalletTransaction::whereBetween('created_at', [$dateRange['from'], $dateRange['to']])
                ->where('status', 'failed')->count(),
            'cancelled_count' => WalletTransaction::whereBetween('created_at', [$dateRange['from'], $dateRange['to']])
                ->where('status', 'cancelled')->count(),
            'unique_users' => WalletTransaction::whereBetween('created_at', [$dateRange['from'], $dateRange['to']])
                ->distinct('user_id')->count('user_id'),
            'average_transaction' => WalletTransaction::whereBetween('created_at', [$dateRange['from'], $dateRange['to']])
                ->where('status', 'completed')->avg('amount'),
            'largest_transaction' => WalletTransaction::whereBetween('created_at', [$dateRange['from'], $dateRange['to']])
                ->where('status', 'completed')->max('amount'),
        ];

        // Calculate success rate
        $summary['success_rate'] = $summary['total_transactions'] > 0 
            ? round(($summary['completed_transactions'] / $summary['total_transactions']) * 100, 2) 
            : 0;

        // Calculate net flow (deposits - withdrawals)
        $summary['net_flow'] = $summary['deposits_amount'] - $summary['withdrawals_amount'];

        return view('admin.financial.transactions.summary', compact('summary'));
    }

    /**
     * Helper method to get date ranges
     */
    private function getDateRange(string $period): array
    {
        switch ($period) {
            case 'today':
                return [
                    'from' => now()->startOfDay(),
                    'to' => now()->endOfDay()
                ];
            case 'yesterday':
                return [
                    'from' => now()->subDay()->startOfDay(),
                    'to' => now()->subDay()->endOfDay()
                ];
            case 'this_week':
                return [
                    'from' => now()->startOfWeek(),
                    'to' => now()->endOfWeek()
                ];
            case 'last_week':
                return [
                    'from' => now()->subWeek()->startOfWeek(),
                    'to' => now()->subWeek()->endOfWeek()
                ];
            case 'this_month':
                return [
                    'from' => now()->startOfMonth(),
                    'to' => now()->endOfMonth()
                ];
            case 'last_month':
                return [
                    'from' => now()->subMonth()->startOfMonth(),
                    'to' => now()->subMonth()->endOfMonth()
                ];
            default:
                return [
                    'from' => now()->startOfDay(),
                    'to' => now()->endOfDay()
                ];
        }
    }

    /**
     * Manual transaction creation (admin only)
     */
    public function create(): View
    {
        return view('admin.financial.transactions.create');
    }

    /**
     * Store manually created transaction
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'wallet_id' => 'required|exists:savings_wallets,id',
            'type' => 'required|in:deposit,withdrawal',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|string|max:255',
            'transaction_reference' => 'nullable|string|max:255|unique:wallet_transactions',
            'admin_notes' => 'nullable|string|max:1000',
            'auto_complete' => 'boolean'
        ]);

        DB::beginTransaction();
        try {
            $transaction = WalletTransaction::create([
                'user_id' => $request->user_id,
                'wallet_id' => $request->wallet_id,
                'type' => $request->type,
                'amount' => $request->amount,
                'payment_method' => $request->payment_method,
                'transaction_reference' => $request->transaction_reference ?: 'ADM-' . strtoupper(uniqid()),
                'status' => $request->auto_complete ? 'completed' : 'pending',
                'admin_notes' => $request->admin_notes,
                'processed_by' => auth()->id(),
                'processed_at' => $request->auto_complete ? now() : null,
            ]);

          // Update wallet balance if auto-completing
            if ($request->auto_complete) {
                $wallet = $transaction->wallet;
                if ($request->type === 'deposit') {
                    $wallet->increment('current_amount', $request->amount);
                } elseif ($request->type === 'withdrawal') {
                    if ($wallet->current_amount >= $request->amount) {
                        $wallet->decrement('current_amount', $request->amount);
                    } else {
                        throw new \Exception('Insufficient wallet balance for withdrawal');
                    }
                }
            }

            ActivityLog::log('transaction_created_by_admin', $transaction, [
                'user_id' => $request->user_id,
                'wallet_id' => $request->wallet_id,
                'type' => $request->type,
                'amount' => $request->amount,
                'status' => $transaction->status,
                'auto_completed' => $request->auto_complete
            ]);

            DB::commit();

            $message = $request->auto_complete 
                ? 'Transaction created and completed successfully.' 
                : 'Transaction created successfully and is pending approval.';

            return redirect()->route('admin.transactions.show', $transaction)
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->with('error', 'Failed to create transaction: ' . $e->getMessage());
        }
    }

    /**
     * Delete/void a transaction
     */
    public function destroy(Request $request, WalletTransaction $transaction): RedirectResponse
    {
        $request->validate([
            'reason' => 'required|string|max:255',
            'confirm' => 'required|accepted'
        ]);

        // Only allow deletion of pending or failed transactions
        if (!in_array($transaction->status, ['pending', 'failed'])) {
            return back()->with('error', 'Only pending or failed transactions can be deleted.');
        }

        DB::beginTransaction();
        try {
            // Log the deletion before removing the transaction
            ActivityLog::log('transaction_deleted_by_admin', $transaction, [
                'transaction_id' => $transaction->id,
                'user_id' => $transaction->user_id,
                'wallet_id' => $transaction->wallet_id,
                'type' => $transaction->type,
                'amount' => $transaction->amount,
                'status' => $transaction->status,
                'reason' => $request->reason,
                'original_data' => $transaction->toArray()
            ]);

            $transaction->delete();

            DB::commit();

            return redirect()->route('admin.financial.transactions.index')
                ->with('success', 'Transaction deleted successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Failed to delete transaction: ' . $e->getMessage());
        }
    }

    /**
     * Get transaction reports
     */
    public function reports(Request $request): View
    {
        $request->validate([
            'report_type' => 'required|in:daily,weekly,monthly,yearly',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from'
        ]);

        $reportType = $request->report_type;
        $dateFrom = $request->date_from ? Carbon::parse($request->date_from) : now()->subDays(30);
        $dateTo = $request->date_to ? Carbon::parse($request->date_to) : now();

        $reports = [];

        switch ($reportType) {
            case 'daily':
                $reports = WalletTransaction::selectRaw('
                    DATE(created_at) as period,
                    COUNT(*) as total_transactions,
                    COUNT(CASE WHEN status = "completed" THEN 1 END) as completed_transactions,
                    SUM(CASE WHEN status = "completed" THEN amount ELSE 0 END) as total_amount,
                    SUM(CASE WHEN status = "completed" AND type = "deposit" THEN amount ELSE 0 END) as deposits,
                    SUM(CASE WHEN status = "completed" AND type = "withdrawal" THEN amount ELSE 0 END) as withdrawals,
                    COUNT(CASE WHEN status = "failed" THEN 1 END) as failed_transactions
                ')
                ->whereBetween('created_at', [$dateFrom, $dateTo])
                ->groupBy('period')
                ->orderBy('period', 'desc')
                ->get();
                break;

            case 'weekly':
                $reports = WalletTransaction::selectRaw('
                    YEAR(created_at) as year,
                    WEEK(created_at) as week,
                    COUNT(*) as total_transactions,
                    COUNT(CASE WHEN status = "completed" THEN 1 END) as completed_transactions,
                    SUM(CASE WHEN status = "completed" THEN amount ELSE 0 END) as total_amount,
                    SUM(CASE WHEN status = "completed" AND type = "deposit" THEN amount ELSE 0 END) as deposits,
                    SUM(CASE WHEN status = "completed" AND type = "withdrawal" THEN amount ELSE 0 END) as withdrawals,
                    COUNT(CASE WHEN status = "failed" THEN 1 END) as failed_transactions
                ')
                ->whereBetween('created_at', [$dateFrom, $dateTo])
                ->groupBy('year', 'week')
                ->orderBy('year', 'desc')
                ->orderBy('week', 'desc')
                ->get();
                break;

            case 'monthly':
                $reports = WalletTransaction::selectRaw('
                    YEAR(created_at) as year,
                    MONTH(created_at) as month,
                    COUNT(*) as total_transactions,
                    COUNT(CASE WHEN status = "completed" THEN 1 END) as completed_transactions,
                    SUM(CASE WHEN status = "completed" THEN amount ELSE 0 END) as total_amount,
                    SUM(CASE WHEN status = "completed" AND type = "deposit" THEN amount ELSE 0 END) as deposits,
                    SUM(CASE WHEN status = "completed" AND type = "withdrawal" THEN amount ELSE 0 END) as withdrawals,
                    COUNT(CASE WHEN status = "failed" THEN 1 END) as failed_transactions
                ')
                ->whereBetween('created_at', [$dateFrom, $dateTo])
                ->groupBy('year', 'month')
                ->orderBy('year', 'desc')
                ->orderBy('month', 'desc')
                ->get();
                break;

            case 'yearly':
                $reports = WalletTransaction::selectRaw('
                    YEAR(created_at) as year,
                    COUNT(*) as total_transactions,
                    COUNT(CASE WHEN status = "completed" THEN 1 END) as completed_transactions,
                    SUM(CASE WHEN status = "completed" THEN amount ELSE 0 END) as total_amount,
                    SUM(CASE WHEN status = "completed" AND type = "deposit" THEN amount ELSE 0 END) as deposits,
                    SUM(CASE WHEN status = "completed" AND type = "withdrawal" THEN amount ELSE 0 END) as withdrawals,
                    COUNT(CASE WHEN status = "failed" THEN 1 END) as failed_transactions
                ')
                ->whereBetween('created_at', [$dateFrom, $dateTo])
                ->groupBy('year')
                ->orderBy('year', 'desc')
                ->get();
                break;
        }

        // Calculate totals
        $totals = [
            'total_transactions' => $reports->sum('total_transactions'),
            'completed_transactions' => $reports->sum('completed_transactions'),
            'total_amount' => $reports->sum('total_amount'),
            'deposits' => $reports->sum('deposits'),
            'withdrawals' => $reports->sum('withdrawals'),
            'failed_transactions' => $reports->sum('failed_transactions'),
        ];

        $totals['success_rate'] = $totals['total_transactions'] > 0 
            ? round(($totals['completed_transactions'] / $totals['total_transactions']) * 100, 2) 
            : 0;

        return view('admin.financial.transactions.reports', compact('reports', 'totals', 'reportType', 'dateFrom', 'dateTo'));
    }

    /**
     * Reconciliation view for financial auditing
     */
    public function reconciliation(Request $request): View
    {
        $date = $request->input('date', today()->toDateString());
        $selectedDate = Carbon::parse($date);

        // Get all transactions for the selected date
        $transactions = WalletTransaction::with(['user', 'wallet.trip'])
            ->whereDate('created_at', $selectedDate)
            ->orderBy('created_at')
            ->get();

        // Calculate daily totals
        $dailyTotals = [
            'total_transactions' => $transactions->count(),
            'completed_transactions' => $transactions->where('status', 'completed')->count(),
            'pending_transactions' => $transactions->where('status', 'pending')->count(),
            'failed_transactions' => $transactions->where('status', 'failed')->count(),
            'total_amount' => $transactions->where('status', 'completed')->sum('amount'),
            'deposits_amount' => $transactions->where('status', 'completed')->where('type', 'deposit')->sum('amount'),
            'withdrawals_amount' => $transactions->where('status', 'completed')->where('type', 'withdrawal')->sum('amount'),
        ];

        // Group transactions by status for easier reconciliation
        $transactionsByStatus = $transactions->groupBy('status');

        // Get wallet balance changes for the day
        $walletChanges = DB::table('savings_wallets')
            ->select('id', 'name', 'current_amount')
            ->whereExists(function ($query) use ($selectedDate) {
                $query->select(DB::raw(1))
                    ->from('wallet_transactions')
                    ->whereColumn('wallet_transactions.wallet_id', 'savings_wallets.id')
                    ->whereDate('wallet_transactions.created_at', $selectedDate)
                    ->where('wallet_transactions.status', 'completed');
            })
            ->get();

        // Identify discrepancies (transactions that might need attention)
        $discrepancies = [];
        
        // Check for transactions without proper references
        $missingReferences = $transactions->filter(function ($transaction) {
            return empty($transaction->transaction_reference);
        });

        if ($missingReferences->count() > 0) {
            $discrepancies['missing_references'] = [
                'count' => $missingReferences->count(),
                'transactions' => $missingReferences->take(10)
            ];
        }

        // Check for high-value transactions without admin approval
        $highValuePending = $transactions->filter(function ($transaction) {
            return $transaction->amount > 5000 && $transaction->status === 'pending';
        });

        if ($highValuePending->count() > 0) {
            $discrepancies['high_value_pending'] = [
                'count' => $highValuePending->count(),
                'transactions' => $highValuePending
            ];
        }

        // Check for transactions processed outside business hours
        $afterHours = $transactions->filter(function ($transaction) {
            $hour = $transaction->created_at->hour;
            return $hour < 8 || $hour > 18;
        });

        if ($afterHours->count() > 0) {
            $discrepancies['after_hours'] = [
                'count' => $afterHours->count(),
                'transactions' => $afterHours->take(10)
            ];
        }

        return view('admin.financial.transactions.reconciliation', compact(
            'transactions',
            'dailyTotals',
            'transactionsByStatus',
            'walletChanges',
            'discrepancies',
            'selectedDate'
        ));
    }

    /**
     * Get transaction trends for dashboard widgets
     */
    public function trends(): View
    {
        $trends = [
            // Last 7 days comparison
            'weekly_comparison' => [
                'current_week' => WalletTransaction::where('created_at', '>=', now()->startOfWeek())->count(),
                'last_week' => WalletTransaction::whereBetween('created_at', [
                    now()->subWeek()->startOfWeek(),
                    now()->subWeek()->endOfWeek()
                ])->count(),
            ],

            // Monthly trends (last 6 months)
            'monthly_trends' => WalletTransaction::selectRaw('
                YEAR(created_at) as year,
                MONTH(created_at) as month,
                COUNT(*) as transaction_count,
                SUM(CASE WHEN status = "completed" THEN amount ELSE 0 END) as total_amount
            ')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get(),

            // Growth rates
            'growth_rates' => [
                'transaction_count' => $this->calculateGrowthRate('count'),
                'transaction_volume' => $this->calculateGrowthRate('volume'),
            ],

            // Peak hours and days
            'peak_analysis' => [
                'hours' => WalletTransaction::selectRaw('HOUR(created_at) as hour, COUNT(*) as count')
                    ->where('created_at', '>=', now()->subDays(30))
                    ->groupBy('hour')
                    ->orderByDesc('count')
                    ->take(5)
                    ->get(),
                
                'days' => WalletTransaction::selectRaw('DAYNAME(created_at) as day, COUNT(*) as count')
                    ->where('created_at', '>=', now()->subDays(30))
                    ->groupBy('day')
                    ->orderByDesc('count')
                    ->get(),
            ],
        ];

        return view('admin.financial.transactions.trends', compact('trends'));
    }

    /**
     * Calculate growth rate helper method
     */
    private function calculateGrowthRate(string $metric): array
    {
        $currentMonth = now()->startOfMonth();
        $lastMonth = now()->subMonth()->startOfMonth();

        if ($metric === 'count') {
            $current = WalletTransaction::where('created_at', '>=', $currentMonth)->count();
            $previous = WalletTransaction::whereBetween('created_at', [
                $lastMonth,
                $lastMonth->copy()->endOfMonth()
            ])->count();
        } else {
            $current = WalletTransaction::where('created_at', '>=', $currentMonth)
                ->where('status', 'completed')
                ->sum('amount');
            $previous = WalletTransaction::whereBetween('created_at', [
                $lastMonth,
                $lastMonth->copy()->endOfMonth()
            ])
            ->where('status', 'completed')
            ->sum('amount');
        }

        $growthRate = $previous > 0 ? (($current - $previous) / $previous) * 100 : 0;

        return [
            'current' => $current,
            'previous' => $previous,
            'growth_rate' => round($growthRate, 2),
            'is_positive' => $growthRate >= 0
        ];
    }

    /**
     * Process refund for a completed transaction
     */
    public function refund(Request $request, WalletTransaction $transaction): RedirectResponse
    {
        $request->validate([
            'refund_amount' => 'required|numeric|min:0.01|max:' . $transaction->amount,
            'refund_reason' => 'required|string|max:500',
            'confirm' => 'required|accepted'
        ]);

        // Only allow refunds for completed transactions
        if ($transaction->status !== 'completed') {
            return back()->with('error', 'Only completed transactions can be refunded.');
        }

        DB::beginTransaction();
        try {
            // Create refund transaction
            $refundTransaction = WalletTransaction::create([
                'user_id' => $transaction->user_id,
                'wallet_id' => $transaction->wallet_id,
                'type' => $transaction->type === 'deposit' ? 'withdrawal' : 'deposit',
                'amount' => $request->refund_amount,
                'payment_method' => 'refund',
                'transaction_reference' => 'REF-' . $transaction->id . '-' . strtoupper(uniqid()),
                'status' => 'completed',
                'admin_notes' => 'Refund for transaction ' . $transaction->transaction_reference . '. Reason: ' . $request->refund_reason,
                'processed_by' => auth()->id(),
                'processed_at' => now(),
            ]);

            // Update wallet balance
            $wallet = $transaction->wallet;
            if ($transaction->type === 'deposit') {
                // Refunding a deposit means removing money from wallet
                $wallet->decrement('current_amount', $request->refund_amount);
            } else {
                // Refunding a withdrawal means adding money back to wallet
                $wallet->increment('current_amount', $request->refund_amount);
            }

            // Update original transaction
            $transaction->update([
                'admin_notes' => ($transaction->admin_notes ?? '') . "\nPartial refund of {$request->refund_amount} processed on " . now()->format('Y-m-d H:i:s')
            ]);

            ActivityLog::log('transaction_refunded', $transaction, [
                'refund_transaction_id' => $refundTransaction->id,
                'refund_amount' => $request->refund_amount,
                'refund_reason' => $request->refund_reason,
                'original_amount' => $transaction->amount,
                'refunded_by' => auth()->user()->name
            ]);

            DB::commit();

            return back()->with('success', 'Refund processed successfully. Refund transaction ID: ' . $refundTransaction->transaction_reference);

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Failed to process refund: ' . $e->getMessage());
        }
    }
}