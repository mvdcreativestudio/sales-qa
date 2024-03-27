<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->string('rut')->nullable()->change();
            $table->string('ci')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->string('rut')->nullable(false)->change();
            $table->string('ci')->nullable(false)->change();
        });
    }
};
