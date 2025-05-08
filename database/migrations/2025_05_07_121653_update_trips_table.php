<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trips', function (Blueprint $table) {
            $table->foreignId('trip_template_id')->nullable()->after('creator_id')->constrained()->nullOnDelete();
            $table->enum('planning_type', ['self_planned', 'pre_planned'])->default('self_planned')->after('trip_template_id');
        });
    }

    public function down(): void
    {
        Schema::table('trips', function (Blueprint $table) {
            $table->dropForeign(['trip_template_id']);
            $table->dropColumn(['trip_template_id', 'planning_type']);
        });
    }
};