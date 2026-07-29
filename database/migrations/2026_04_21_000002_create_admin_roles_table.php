<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admin_roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Seed default roles
        DB::table('admin_roles')->insert([
            ['id' => 1, 'name' => 'Super Admin', 'description' => 'Full access', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'Merchant',    'description' => 'Merchant access', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_roles');
    }
};
