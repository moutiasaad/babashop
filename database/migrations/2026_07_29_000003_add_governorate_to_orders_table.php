<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'delivery_zone_id')) {
                $table->unsignedBigInteger('delivery_zone_id')->nullable()->after('merchant_id');
                $table->index('delivery_zone_id');
            }
            if (!Schema::hasColumn('orders', 'delivery_fee_snapshot')) {
                // Immutable copy of the fee charged at order time — protects historical orders
                // if zone fees change later.
                $table->decimal('delivery_fee_snapshot', 8, 3)->nullable()->after('delivery_cost');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['delivery_zone_id']);
            $table->dropColumn(['delivery_zone_id', 'delivery_fee_snapshot']);
        });
    }
};
