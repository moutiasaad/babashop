<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('delivery_zones', function (Blueprint $table) {
            $table->id();
            $table->string('name');                                 // French name (default UI language)
            $table->string('name_ar')->nullable();                  // Arabic name (task #14 will populate)
            $table->string('code', 8)->unique();                    // ISO 3166-2 subdivision code, e.g. TN-11
            $table->decimal('delivery_fee', 8, 3)->default(0);      // TND uses 3 decimals (millimes)
            $table->decimal('free_shipping_threshold', 10, 3)->nullable();
            $table->unsignedTinyInteger('estimated_days_min')->nullable();
            $table->unsignedTinyInteger('estimated_days_max')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('display_order')->default(0);
            $table->timestamps();

            $table->index(['is_active', 'display_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_zones');
    }
};
