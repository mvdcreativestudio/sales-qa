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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('number');
            $table->date('date');
            $table->enum('type', ['venta_credito', 'nota_credito', 'venta_contado', 'devolucion_contado', 'venta_credito_contingencia', 'nota_credito_contingencia', 'venta_contado_contingencia', 'devolucion_contado_contingencia']);
            $table->enum('status', ['accepted', 'pending', 'rejected']);
            $table->unsignedBigInteger('client_id');
            $table->enum('currency', ['USD', 'UYU']);
            $table->decimal('subtotal', 10, 2);
            $table->decimal('tax', 10, 2);
            $table->decimal('total', 10, 2);
            $table->date('due_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
