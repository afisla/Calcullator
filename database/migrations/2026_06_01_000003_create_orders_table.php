<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained();
            $table->string('session_token')->index();
            $table->string('order_code', 25)->unique();
            $table->string('customer_name', 100);
            $table->string('customer_class', 20)->nullable();
            $table->enum('status', [
                'pending',      // Menunggu pembayaran
                'paid',         // Sudah bayar, menunggu diproses warung
                'processing',   // Sedang dimasak/diproses
                'ready',        // Siap diambil
                'completed',    // Sudah diambil
                'rejected',     // Ditolak warung
            ])->default('pending');
            $table->decimal('total_price', 10, 2);
            $table->text('rejection_reason')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
