<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // The otp table has no create migration in history — guard against fresh envs.
        if (!Schema::hasTable('otp')) {
            Schema::create('otp', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('type')->default('login');
                $table->string('otp', 10);
                $table->boolean('is_used')->default(false);
                $table->timestamps();
                $table->index('user_id');
            });
        }

        Schema::table('otp', function (Blueprint $table) {
            if (!Schema::hasColumn('otp', 'channel')) {
                $table->string('channel', 10)->default('sms')->after('type');
            }
            if (!Schema::hasColumn('otp', 'email')) {
                $table->string('email')->nullable()->after('user_id')->index();
            }
            if (!Schema::hasColumn('otp', 'expires_at')) {
                $table->timestamp('expires_at')->nullable()->after('is_used');
            }
            if (!Schema::hasColumn('otp', 'attempts')) {
                $table->unsignedTinyInteger('attempts')->default(0)->after('expires_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('otp', function (Blueprint $table) {
            $table->dropColumn(['channel', 'email', 'expires_at', 'attempts']);
        });
    }
};
