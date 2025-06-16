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
        Schema::table('trips', function (Blueprint $table) {
            // Add selected_optional_activities field to store selected optional activities for pre-planned trips
            if (!Schema::hasColumn('trips', 'selected_optional_activities')) {
                $table->json('selected_optional_activities')->nullable()->after('total_cost');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trips', function (Blueprint $table) {
            if (Schema::hasColumn('trips', 'selected_optional_activities')) {
                $table->dropColumn('selected_optional_activities');
            }
        });
    }
};