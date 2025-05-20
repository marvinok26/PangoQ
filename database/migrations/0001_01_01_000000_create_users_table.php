<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone_number')->nullable();
            $table->string('profile_photo_path')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();

            $table->string('id_card_number')->nullable();
            $table->string('passport_number')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->string('nationality')->nullable();
            $table->string('address')->nullable();

            $table->string('account_number')->nullable();
            $table->enum('account_type', ['personal', 'business'])->default('personal');
            $table->string('currency', 3)->default('USD');
            $table->string('linked_bank_account')->nullable();
            $table->string('wallet_provider')->nullable();
            $table->enum('account_status', ['active', 'inactive', 'pending'])->default('active');
            $table->enum('preferred_payment_method', ['wallet', 'bank_transfer', 'credit_card', 'm_pesa'])->default('wallet');

            $table->timestamp('email_verified_at')->nullable();
            
            $table->string('auth_provider')->nullable();
            $table->string('auth_provider_id')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};