<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_option_values', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_option_id');
            $table->string('value');          // "Noir", "XL", etc.
            $table->unsignedInteger('qty')->default(0);
            $table->string('image_path')->nullable(); // color image
            $table->timestamps();

            $table->foreign('product_option_id')
                  ->references('id')->on('product_options')->onDelete('cascade');
        });

        // Migrate existing JSON values → new table
        $options = DB::table('product_options')->get();
        foreach ($options as $option) {
            $values = json_decode($option->values ?? '[]', true) ?? [];
            foreach ($values as $value) {
                DB::table('product_option_values')->insert([
                    'product_option_id' => $option->id,
                    'value'             => $value,
                    'qty'               => 0,
                    'created_at'        => now(),
                    'updated_at'        => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('product_option_values');
    }
};
