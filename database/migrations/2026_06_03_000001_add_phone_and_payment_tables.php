<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah kolom customer_phone ke orders
        Schema::table('orders', function (Blueprint $table) {
            $table->string('customer_phone', 20)->nullable()->after('customer_class');
            $table->string('queue_code', 15)->nullable()->after('queue_number'); // format KOP-001 / KTN-001
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'expired'])
                  ->default('pending')->after('status');
        });

        // Tabel payments
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->string('midtrans_order_id')->unique()->nullable();
            $table->string('snap_token')->nullable();
            $table->enum('status', ['pending', 'paid', 'failed', 'expired'])->default('pending');
            $table->decimal('amount', 12, 2);
            $table->string('payment_method', 50)->nullable();
            $table->string('payment_type', 50)->nullable();
            $table->text('raw_response')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });

        // Tabel stock_histories
        Schema::create('stock_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('type', ['in', 'out', 'adjustment'])->default('out');
            $table->integer('qty_change');
            $table->integer('qty_before');
            $table->integer('qty_after');
            $table->string('note', 200)->nullable();
            $table->timestamps();
        });

        // Tambah kolom photo ke products
        Schema::table('products', function (Blueprint $table) {
            $table->string('photo', 255)->nullable()->after('name');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['customer_phone', 'queue_code', 'payment_status']);
        });

        Schema::dropIfExists('payments');
        Schema::dropIfExists('stock_histories');

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('photo');
        });
    }
};
