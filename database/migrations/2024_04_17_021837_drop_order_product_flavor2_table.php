<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropOrderProductFlavor2Table extends Migration
{
    public function up()
    {
        Schema::dropIfExists('order_product_flavor');
    }

    public function down()
    {
      //
    }
}
