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
        // Add column as nullable first to avoid unique constraint violation with existing data
        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_id')->nullable()->after('user_id');
        });

        // Populate existing orders with unique IDs
        $orders = \App\Models\Order::all();
        foreach ($orders as $order) {
            $order->payment_id = \App\Models\Order::generateUniquePaymentId();
            $order->save();
        }

        // Apply unique constraint and index after data is populated
        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_id')->nullable(false)->change();
            $table->unique('payment_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('payment_id');
        });
    }
};
