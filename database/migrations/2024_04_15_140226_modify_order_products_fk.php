<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyOrderProductsFk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Asegúrate de ajustar los nombres de tabla y columna a tus necesidades
        Schema::table('order_products', function (Blueprint $table) {
            // Primero eliminamos la restricción existente
            $table->dropForeign(['order_id']);

            // Ahora añadimos la nueva restricción con acción en cascada
            $table->foreign('order_id')
                  ->references('id')->on('orders')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_products', function (Blueprint $table) {
            $table->dropForeign(['order_id']);

            // Restauramos la restricción original sin acción en cascada
            $table->foreign('order_id')
                  ->references('id')->on('orders')
                  ->onDelete('restrict'); // O 'no action' dependiendo de la DB
        });
    }
}
