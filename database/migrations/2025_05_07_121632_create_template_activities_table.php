<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('template_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_template_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('location');
            $table->integer('day_number');
            $table->enum('time_of_day', ['morning', 'afternoon', 'evening'])->default('morning');
            $table->time('start_time');
            $table->time('end_time');
            $table->decimal('cost', 10, 2)->nullable();
            $table->string('category');
            $table->string('image_url')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('template_activities');
    }
};