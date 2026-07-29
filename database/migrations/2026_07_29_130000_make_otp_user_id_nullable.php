<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Older environments have `otp.user_id` as NOT NULL — a leftover from the
// SMS-only OTP flow that always had a `user_id`. The email OTP flow allows
// requesting a code for a brand-new address (no user row yet), so this
// column must be nullable.
//
// Uses raw ALTER instead of doctrine/dbal to avoid adding a dev dep.
return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('otp')) return;

        // Only MySQL is supported in this project. Guard anyway.
        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE `otp` MODIFY `user_id` BIGINT UNSIGNED NULL');
        }
    }

    public function down(): void
    {
        // Intentional no-op — reverting would break the email OTP flow and
        // requires wiping otp rows with null user_id anyway.
    }
};
