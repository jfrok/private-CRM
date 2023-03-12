<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKeysoftwareCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('keysoftware_customers', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('company_street');
            $table->string('company_number');
            $table->string('company_zipcode');
            $table->string('company_place');
            $table->string('company_province');
            $table->string('company_phone');
            $table->string('company_email');
            $table->string('company_website');
            $table->date('start_date');
            $table->longText('api_token')->unique();
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
        Schema::dropIfExists('keysoftware_customers');
    }
}
