<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trip_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('destination_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('highlights')->nullable();
            $table->integer('duration_days');
            $table->decimal('base_price', 10, 2);
            $table->string('difficulty_level')->default('moderate');
            $table->string('trip_style')->default('general');
            $table->boolean('is_featured')->default(false);
            $table->string('featured_image')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trip_templates');
    }
};