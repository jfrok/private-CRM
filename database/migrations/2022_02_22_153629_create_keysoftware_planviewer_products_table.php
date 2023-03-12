<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKeysoftwarePlanviewerProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('keysoftware_planviewer_products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->decimal('kadaster_price', 16, 3)->nullable();
            $table->decimal('planviewer_price', 16, 3)->nullable();
            $table->decimal('total_price', 16, 3)->nullable();
            $table->decimal('vat', 16, 2)->default(21.00);
            $table->timestamps();
        });

        $data = [
            [
                "slug" => "bestemmingsplanrapport",
                "name" => "Bestemmingsplanrapport",
                "kadaster_price" => 0.000,
                "planviewer_price" => 1.50,
            ],
            [
                "slug" => "percelenrapport",
                "name" => "Percelenrapport",
                "kadaster_price" => 0.000,
                "planviewer_price" => 0.750,
            ],
            [
                "slug" => "maatvoeringperceel",
                "name" => "Maatvoering perceel",
                "kadaster_price" => 0.000,
                "planviewer_price" => 12.500,
            ],
            [
                "slug" => "verbouwpakket",
                "name" => "Verbouwpakket",
                "kadaster_price" => 0.000,
                "planviewer_price" => 17.500,
            ],
            [
                "slug" => "bestemmingsplankaart",
                "name" => "Bestemmingsplankaart",
                "kadaster_price" => 0.000,
                "planviewer_price" => 0.750,
            ],
            [
                "slug" => "hypotheekinformatie",
                "name" => "Hypotheekinformatie",
                "kadaster_price" => 2.800,
                "planviewer_price" => 0.150,
            ],
            [
                "slug" => "eigendomsinformatie",
                "name" => "Eigendomsinformatie",
                "kadaster_price" => 2.800,
                "planviewer_price" => 0.150,
            ],
            [
                "slug" => "uittreksel-kadastrale-kaart",
                "name" => "Uittreksel Kadastrale Kaart",
                "kadaster_price" => 1.700,
                "planviewer_price" => 0.150,
            ],
            [
                "slug" => "koopsommen",
                "name" => "Koopsommen",
                "kadaster_price" => 0.000,
                "planviewer_price" => 0.150,
            ],
            [
                "slug" => "maps_api",
                "name" => "Maps Api",
                "kadaster_price" => 0.000,
                "planviewer_price" => 0.001,
            ],
            [
                "slug" => "data_api",
                "name" => "Data Api",
                "kadaster_price" => 0.670,
                "planviewer_price" => 0.001,
            ],
        ];

        foreach ($data as $d) {
            $new = new \App\Models\KeysoftwarePlanviewerProducts();
            $new->slug = $d['slug'];
            $new->name = $d['name'];
            $new->kadaster_price = $d['kadaster_price'];
            $new->planviewer_price = $d['planviewer_price'];
            $new->total_price = $d['kadaster_price'] + $d['planviewer_price'];
            $new->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('keysoftware_planviewer_products');
    }
}
