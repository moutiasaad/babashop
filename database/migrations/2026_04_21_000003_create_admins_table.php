<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('title')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->unsignedBigInteger('role_id')->default(1);
            $table->string('image')->nullable();
            $table->unsignedBigInteger('merchant_id')->nullable();
            $table->string('phone')->nullable();
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('role_id')->references('id')->on('admin_roles')->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
