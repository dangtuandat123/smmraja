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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('service_id')->constrained();
            $table->integer('api_order_id')->nullable(); // Order ID từ SMMRaja
            $table->string('link');
            $table->integer('quantity');
            $table->decimal('price_per_unit', 10, 5); // Giá VND/đơn vị tại thời điểm mua
            $table->decimal('total_price', 15, 2); // Tổng tiền VND
            $table->decimal('api_charge', 10, 5)->nullable(); // Chi phí API (USD)
            $table->string('status')->default('pending');
            // Status: pending, processing, in_progress, completed, partial, canceled, refunded, error
            $table->integer('start_count')->nullable();
            $table->integer('remains')->nullable();
            $table->json('extra_data')->nullable(); // comments, usernames, hashtags, etc.
            $table->text('error_message')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'status']);
            $table->index('api_order_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
