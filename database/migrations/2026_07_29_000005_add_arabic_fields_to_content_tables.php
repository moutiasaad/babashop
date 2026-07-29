<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('product', function (Blueprint $table) {
            if (!Schema::hasColumn('product', 'name_ar')) {
                $table->string('name_ar')->nullable()->after('name');
            }
            if (!Schema::hasColumn('product', 'description_ar')) {
                $table->text('description_ar')->nullable()->after('description');
            }
        });

        Schema::table('category', function (Blueprint $table) {
            if (!Schema::hasColumn('category', 'name_ar')) {
                $table->string('name_ar')->nullable()->after('name');
            }
        });

        if (Schema::hasTable('banners')) {
            Schema::table('banners', function (Blueprint $table) {
                if (!Schema::hasColumn('banners', 'name_ar')) {
                    $table->string('name_ar')->nullable()->after('name');
                }
            });
        }
    }

    public function down(): void
    {
        Schema::table('product', function (Blueprint $table) {
            $table->dropColumn(['name_ar', 'description_ar']);
        });
        Schema::table('category', function (Blueprint $table) {
            $table->dropColumn('name_ar');
        });
        if (Schema::hasTable('banners')) {
            Schema::table('banners', function (Blueprint $table) {
                $table->dropColumn('name_ar');
            });
        }
    }
};
