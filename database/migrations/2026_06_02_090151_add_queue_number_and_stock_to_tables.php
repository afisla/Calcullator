<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->integer('queue_number')->nullable()->after('order_code');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->integer('stock')->default(100)->after('is_available');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('queue_number');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('stock');
        });
    }
};
