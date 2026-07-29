<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->json('options');          // {"Couleur":"Noir","Taille":"XL"}
            $table->unsignedInteger('qty')->default(0);
            $table->decimal('price', 10, 2)->nullable(); // price override (null = use product price)
            $table->string('sku')->nullable();
            $table->string('image_path')->nullable();
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('product')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
