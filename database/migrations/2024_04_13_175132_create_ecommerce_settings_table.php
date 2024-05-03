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
        Schema::create('ecommerce_settings', function (Blueprint $table) {
          $table->boolean('enable_coupons')->default(false);
          $table->enum('currency', ['UYU', 'USD', 'EUR', 'ARS'])->default('UYU');
          $table->string('currency_symbol')->default('$');
          $table->string('decimal_separator')->default(',');
          $table->string('thousands_separator')->default('.');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ecommerce_settings');
    }
};
