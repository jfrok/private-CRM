<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('user_id')->nullable();
            $table->string('user_name');
            $table->integer('project_id');
            $table->integer('customer_id');
            $table->string('datum_vanaf');
            $table->string('datum_tot');
            $table->string('tijd_vanaf');
            $table->string('tijd_tot');
            $table->string('titel');
            $table->string('beschrijving')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events');
    }
}
