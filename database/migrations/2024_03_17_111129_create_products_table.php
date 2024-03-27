<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->enum('type', ['simple', 'configurable']);
            $table->integer('old_price');
            $table->integer('price');
            $table->integer('discount');
            $table->string('categories');
            $table->string('tags');
            $table->string('atributtes');
            $table->string('variations');
            $table->string('image');
            $table->foreignId('store_id')->constrained();
            $table->boolean('status');
            $table->bigInteger('stock');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
