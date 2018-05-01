<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSensorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sensors', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('precision');
            $table->double('expected_operating_time');
            $table->string('power_consumption');
            $table->string('voltage_min');
            $table->string('voltage_max');
            $table->string('operating_temperature_min');
            $table->string('operating_temperature_max');
            $table->string('name');
            $table->string('value_name');
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
        Schema::dropIfExists('sensors');
    }
}
