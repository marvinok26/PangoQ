<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('savings_wallets', function (Blueprint $table) {
            $table->string('currency', 3)->default('USD')->after('contribution_frequency');
        });
    }

    public function down(): void
    {
        Schema::table('savings_wallets', function (Blueprint $table) {
            $table->dropColumn('currency');
        });
    }
};