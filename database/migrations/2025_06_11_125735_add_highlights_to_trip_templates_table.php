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
        Schema::table('trip_templates', function (Blueprint $table) {
            // Check if highlights column doesn't exist before adding it
            if (!Schema::hasColumn('trip_templates', 'highlights')) {
                $table->json('highlights')->nullable()->after('description');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trip_templates', function (Blueprint $table) {
            if (Schema::hasColumn('trip_templates', 'highlights')) {
                $table->dropColumn('highlights');
            }
        });
    }
};