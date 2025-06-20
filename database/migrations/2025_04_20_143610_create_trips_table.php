<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('creator_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('trip_template_id')->nullable()->constrained()->onDelete('set null');
            $table->string('planning_type')->default('self_planned');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('destination');
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('budget', 10, 2)->nullable();
            $table->decimal('total_cost', 10, 2)->nullable();
            $table->enum('status', ['planning', 'active', 'completed'])->default('planning');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trips');
    }
};