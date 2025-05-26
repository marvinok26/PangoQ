<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add minimal admin fields to wallet_transactions
        Schema::table('wallet_transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('wallet_transactions', 'processed_by_admin_id')) {
                $table->foreignId('processed_by_admin_id')->nullable()->constrained('users')->onDelete('set null')->after('transaction_reference');
            }
            if (!Schema::hasColumn('wallet_transactions', 'admin_notes')) {
                $table->text('admin_notes')->nullable()->after('processed_by_admin_id');
            }
        });

        // Add minimal admin fields to savings_wallets
        Schema::table('savings_wallets', function (Blueprint $table) {
            if (!Schema::hasColumn('savings_wallets', 'admin_flagged')) {
                $table->boolean('admin_flagged')->default(false)->after('currency');
            }
            if (!Schema::hasColumn('savings_wallets', 'admin_notes')) {
                $table->text('admin_notes')->nullable()->after('admin_flagged');
            }
            
            // Add index for flagged wallets
            if (!$this->indexExists('savings_wallets', 'savings_wallets_admin_flagged_index')) {
                $table->index('admin_flagged');
            }
        });

        // Add minimal admin fields to notifications
        Schema::table('notifications', function (Blueprint $table) {
            if (!Schema::hasColumn('notifications', 'created_by_admin_id')) {
                $table->foreignId('created_by_admin_id')->nullable()->constrained('users')->onDelete('set null')->after('message');
            }
            if (!Schema::hasColumn('notifications', 'is_admin_notification')) {
                $table->boolean('is_admin_notification')->default(false)->after('created_by_admin_id');
            }
            if (!Schema::hasColumn('notifications', 'priority')) {
                $table->enum('priority', ['low', 'normal', 'high'])->default('normal')->after('is_admin_notification');
            }
            
            // Add indexes
            if (!$this->indexExists('notifications', 'notifications_is_admin_notification_index')) {
                $table->index('is_admin_notification');
            }
            if (!$this->indexExists('notifications', 'notifications_priority_index')) {
                $table->index('priority');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wallet_transactions', function (Blueprint $table) {
            if (Schema::hasColumn('wallet_transactions', 'processed_by_admin_id')) {
                $table->dropForeign(['processed_by_admin_id']);
                $table->dropColumn(['processed_by_admin_id', 'admin_notes']);
            }
        });

        Schema::table('savings_wallets', function (Blueprint $table) {
            if ($this->indexExists('savings_wallets', 'savings_wallets_admin_flagged_index')) {
                $table->dropIndex(['admin_flagged']);
            }
            if (Schema::hasColumn('savings_wallets', 'admin_flagged')) {
                $table->dropColumn(['admin_flagged', 'admin_notes']);
            }
        });

        Schema::table('notifications', function (Blueprint $table) {
            if (Schema::hasColumn('notifications', 'created_by_admin_id')) {
                $table->dropForeign(['created_by_admin_id']);
            }
            if ($this->indexExists('notifications', 'notifications_is_admin_notification_index')) {
                $table->dropIndex(['is_admin_notification']);
            }
            if ($this->indexExists('notifications', 'notifications_priority_index')) {
                $table->dropIndex(['priority']);
            }
            if (Schema::hasColumn('notifications', 'created_by_admin_id')) {
                $table->dropColumn(['created_by_admin_id', 'is_admin_notification', 'priority']);
            }
        });
    }

    /**
     * Check if an index exists
     */
    private function indexExists(string $table, string $indexName): bool
    {
        $indexes = Schema::getConnection()
            ->getDoctrineSchemaManager()
            ->listTableIndexes($table);
            
        return array_key_exists($indexName, $indexes);
    }
};