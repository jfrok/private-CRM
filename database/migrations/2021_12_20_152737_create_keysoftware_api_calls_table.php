<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKeysoftwareApiCallsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('keysoftware_api_calls', function (Blueprint $table) {
            $table->id();
            $table->integer('keysoftware_customer_id');
            $table->string('name');
            $table->string('slug');
            $table->integer('count');
            $table->date('date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('keysoftware_api_calls');
    }
}
