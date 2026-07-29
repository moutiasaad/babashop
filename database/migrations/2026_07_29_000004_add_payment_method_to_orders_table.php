<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'payment_method')) {
                // 'cod' = cash on delivery (default — dominant TN payment method)
                // 'online' = card / e-wallet processed via MyFatoorah (existing flow)
                $table->string('payment_method', 20)->default('cod')->after('is_paid');
                $table->index('payment_method');
            }
            if (!Schema::hasColumn('orders', 'paid_at')) {
                $table->timestamp('paid_at')->nullable()->after('payment_method');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['payment_method']);
            $table->dropColumn(['payment_method', 'paid_at']);
        });
    }
};
