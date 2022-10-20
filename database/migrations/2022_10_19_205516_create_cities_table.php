<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->foreignId("district_id");
            $table->string("coa_path")->nullable();
            $table->string("name");
            $table->string("mayor_name");
            $table->string("ch_address");
            $table->string("websites");
            $table->string("phone_numbers");
            $table->string("faxes");
            $table->string("emails");
            $table->float("latitude", 10, 8)->nullable();
            $table->float("longitude", 10, 8)->nullable();
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
        Schema::dropIfExists('cities');
    }
}
