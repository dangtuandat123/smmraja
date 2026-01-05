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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->integer('api_service_id'); // Service ID từ SMMRaja
            $table->string('name');
            $table->string('api_name'); // Tên gốc từ API
            $table->string('type')->default('Default'); // Default, Custom Comments, etc.
            $table->text('description')->nullable();
            $table->decimal('api_rate', 10, 5); // Giá gốc USD từ API (giá/1000)
            $table->decimal('markup_percent', 5, 2)->default(30); // % lợi nhuận
            $table->decimal('price_vnd', 15, 2); // Giá bán VND (giá/1000)
            $table->integer('min');
            $table->integer('max');
            $table->boolean('refill')->default(false);
            $table->boolean('cancel')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->json('extra_parameters')->nullable();
            $table->timestamps();
            
            $table->index(['category_id', 'is_active']);
            $table->index('api_service_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
