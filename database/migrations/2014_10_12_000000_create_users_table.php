<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password')->nullable();
            $table->string('role')->nullable();
            $table->string('profile_image')->nullable();
            $table->longText('description')->nullable();
            $table->dateTime('last_login')->nullable();
            $table->decimal('min_income')->nullable();
            $table->decimal('hourly_costs')->nullable();
            $table->decimal('project_cost')->nullable();
            $table->integer('hours_a_week')->nullable();
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();
        });

        $new = new User();
        $new->name = 'Kevin Bolhuis';
        $new->email = 'kevin@onlinebouwers.nl';
        $new->password = bcrypt('@kevin2021');
        $new->min_income = 4700.00;
        $new->hourly_costs = 28.00;
        $new->role = 'Admin';
        $new->save();

        $new = new User();
        $new->name = 'Martin Kok';
        $new->email = 'martin@onlinebouwers.nl';
        $new->password = bcrypt('@martin2021');
        $new->min_income = 4700.00;
        $new->hourly_costs = 28.00;
        $new->role = 'Admin';
        $new->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
