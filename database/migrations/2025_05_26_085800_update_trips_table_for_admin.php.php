<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trips', function (Blueprint $table) {
            // Admin monitoring fields
            $table->enum('admin_status', ['approved', 'under_review', 'flagged', 'restricted'])->default('approved')->after('status');
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null')->after('admin_status');
            $table->timestamp('reviewed_at')->nullable()->after('reviewed_by');
            $table->text('admin_notes')->nullable()->after('reviewed_at');
            $table->boolean('is_featured')->default(false)->after('admin_notes');
            $table->integer('report_count')->default(0)->after('is_featured');
            
            $table->index(['admin_status', 'created_at']);
            $table->index(['is_featured', 'status']);
        });
    }

    public function down(): void
    {
        Schema::table('trips', function (Blueprint $table) {
            $table->dropIndex(['admin_status', 'created_at']);
            $table->dropIndex(['is_featured', 'status']);
            
            $table->dropColumn([
                'admin_status',
                'reviewed_by',
                'reviewed_at',
                'admin_notes',
                'is_featured',
                'report_count'
            ]);
        });
    }
};