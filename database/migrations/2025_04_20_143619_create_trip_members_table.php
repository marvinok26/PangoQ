<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trip_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->enum('role', ['organizer', 'member'])->default('member');
            $table->enum('invitation_status', ['pending', 'accepted', 'declined'])->default('pending');
            $table->string('invitation_email')->nullable();
            $table->timestamps();

            // One user can only be added once to a trip
            $table->unique(['trip_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trip_members');
    }
};