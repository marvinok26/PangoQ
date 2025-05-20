<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('savings_wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->decimal('minimum_goal', 10, 2);
            $table->decimal('custom_goal', 10, 2)->nullable();
            $table->decimal('current_amount', 10, 2)->default(0);
            $table->date('target_date')->nullable();
            $table->enum('contribution_frequency', ['weekly', 'monthly'])->default('weekly');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('savings_wallets');
    }
};