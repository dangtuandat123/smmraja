<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Round balance to integer before changing type
        DB::statement('UPDATE users SET balance = ROUND(balance)');
        
        Schema::table('users', function (Blueprint $table) {
            $table->bigInteger('balance')->default(0)->change();
        });
        
        // Also update transactions table
        DB::statement('UPDATE transactions SET amount = ROUND(amount), balance_before = ROUND(balance_before), balance_after = ROUND(balance_after)');
        
        Schema::table('transactions', function (Blueprint $table) {
            $table->bigInteger('amount')->change();
            $table->bigInteger('balance_before')->default(0)->change();
            $table->bigInteger('balance_after')->default(0)->change();
        });
        
        // Update orders table
        DB::statement('UPDATE orders SET total_price = ROUND(total_price)');
        
        Schema::table('orders', function (Blueprint $table) {
            $table->bigInteger('total_price')->default(0)->change();
        });
        
        // Update services table
        DB::statement('UPDATE services SET price_vnd = ROUND(price_vnd)');
        
        Schema::table('services', function (Blueprint $table) {
            $table->bigInteger('price_vnd')->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('balance', 15, 2)->default(0)->change();
        });
        
        Schema::table('transactions', function (Blueprint $table) {
            $table->decimal('amount', 15, 2)->change();
            $table->decimal('balance_before', 15, 2)->default(0)->change();
            $table->decimal('balance_after', 15, 2)->default(0)->change();
        });
        
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('total_price', 15, 2)->default(0)->change();
        });
        
        Schema::table('services', function (Blueprint $table) {
            $table->decimal('price_vnd', 15, 2)->default(0)->change();
        });
    }
};
