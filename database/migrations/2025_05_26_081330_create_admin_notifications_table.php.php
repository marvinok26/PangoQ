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
        Schema::create('admin_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('message');
            $table->enum('type', ['info', 'warning', 'success', 'error'])->default('info');
            $table->enum('target_audience', ['all_users', 'active_users', 'specific_users', 'admins_only']);
            $table->json('target_user_ids')->nullable(); // For specific users
            $table->boolean('is_active')->default(true);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->boolean('show_on_dashboard')->default(false);
            $table->boolean('send_email')->default(false);
            $table->boolean('send_sms')->default(false);
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->integer('views_count')->default(0);
            $table->timestamps();

            $table->index(['is_active', 'starts_at', 'expires_at']);
            $table->index(['target_audience', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_notifications');
    }
};