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
        Schema::table('users', function (Blueprint $table) {
            // Admin role fields
            $table->boolean('is_admin')->default(false)->after('email_verified_at');
            $table->enum('admin_role', ['super_admin', 'admin', 'moderator'])->nullable()->after('is_admin');
            $table->timestamp('admin_since')->nullable()->after('admin_role');
            $table->timestamp('last_admin_login')->nullable()->after('admin_since');
            $table->text('admin_notes')->nullable()->after('last_admin_login');
            
            // Add indexes for admin queries
            $table->index(['is_admin', 'account_status']);
            $table->index('admin_role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['is_admin', 'account_status']);
            $table->dropIndex(['admin_role']);
            
            $table->dropColumn([
                'is_admin',
                'admin_role', 
                'admin_since',
                'last_admin_login',
                'admin_notes'
            ]);
        });
    }
};