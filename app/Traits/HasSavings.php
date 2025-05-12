<?php

namespace App\Traits;

use App\Models\SavingsWallet;
use App\Models\WalletTransaction;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasSavings
{
    /**
     * Get the savings wallets associated with the user.
     */
    public function savingsWallets()
    {
        return $this->hasMany(SavingsWallet::class);
    }

    /**
     * Get the wallet transactions made by the user.
     */
    public function walletTransactions(): HasMany
    {
        return $this->hasMany(WalletTransaction::class);
    }

    /**
     * Create a new savings wallet for the user.
     */
    public function createSavingsWallet(array $attributes = [])
    {
        return $this->savingsWallets()->create($attributes);
    }

    /**
     * Get the user's active savings wallets.
     */
    public function activeSavingsWallets()
    {
        return $this->savingsWallets()->where('status', 'active');
    }
}