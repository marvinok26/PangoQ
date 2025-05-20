<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // First, create a backup of the existing names
        $wallets = DB::table('savings_wallets')->select('id', 'name')->get();
        $backups = [];
        
        foreach ($wallets as $wallet) {
            $backups[$wallet->id] = $wallet->name;
        }
        
        // Change the column type to JSON
        Schema::table('savings_wallets', function (Blueprint $table) {
            $table->json('name')->change();
        });
        
        // Restore the names as JSON
        foreach ($backups as $id => $name) {
            DB::table('savings_wallets')
                ->where('id', $id)
                ->update(['name' => json_encode(['en' => $name])]);
        }
    }

    public function down(): void
    {
        // First, extract the English names
        $wallets = DB::table('savings_wallets')->select('id', 'name')->get();
        $backups = [];
        
        foreach ($wallets as $wallet) {
            $nameData = json_decode($wallet->name, true);
            $backups[$wallet->id] = $nameData['en'] ?? '';
        }
        
        // Change back to string
        Schema::table('savings_wallets', function (Blueprint $table) {
            $table->string('name')->change();
        });
        
        // Restore the string names
        foreach ($backups as $id => $name) {
            DB::table('savings_wallets')
                ->where('id', $id)
                ->update(['name' => $name]);
        }
    }
};