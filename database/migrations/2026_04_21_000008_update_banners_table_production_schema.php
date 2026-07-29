<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            // Add production columns (skip if already exist)
            if (!Schema::hasColumn('banners', 'name')) {
                $table->string('name')->after('id')->default('');
            }
            if (!Schema::hasColumn('banners', 'order_item')) {
                $table->unsignedInteger('order_item')->default(0)->after('link');
            }
            if (!Schema::hasColumn('banners', 'visibility')) {
                $table->boolean('visibility')->default(true)->after('order_item');
            }
            if (!Schema::hasColumn('banners', 'deleted')) {
                $table->boolean('deleted')->default(false)->after('visibility');
            }
            // Drop old columns added by our previous migration
            if (Schema::hasColumn('banners', 'is_active')) {
                $table->dropColumn('is_active');
            }
            if (Schema::hasColumn('banners', 'order')) {
                $table->dropColumn('order');
            }
        });
    }

    public function down(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->dropColumn(['name', 'order_item', 'visibility', 'deleted']);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('order')->default(0);
        });
    }
};
