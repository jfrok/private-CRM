<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_id');
            $table->integer('user_id')->nullable();
            $table->integer('offer_id')->nullable();
            $table->text('title')->nullable();
            $table->longText('description')->nullable();
            $table->boolean('include_count')->default(true);
            $table->integer('set_hours')->nullable()->default(0);
            $table->decimal('set_price')->nullable()->default(75.00);
            $table->string('status')->default('Open');
            $table->softDeletes();
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
        Schema::dropIfExists('projects');
    }
}
