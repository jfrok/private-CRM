<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWefactFacturenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wefact_facturen', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('ar_number');
            $table->string('quantity');
            $table->string('article');
            $table->string('article_price');
            $table->string('omschrijving');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wefact_facturen');
    }
}
