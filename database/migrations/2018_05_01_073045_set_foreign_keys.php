<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SetForeignKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    
/*         Schema::table('devices', function($table) {
            $table->foreign('users_id')->references('id')
                ->on('users');
        }); */
/*         Schema::table('streets', function($table) {
            $table->foreign('city_id')->references('id')
                ->on('cities')->onDelete('cascade');
        }); */
/*         Schema::table('sensor_data', function($table) {
            $table->foreign('sensors_id')->references('id')
            ->on('sensors');
            $table->foreign('streets_id')->references('id')
            ->on('streets');
        }); */
/*         Schema::table('devices_sensors', function($table) {
            $table->foreign('devices_id')->references('id')
            ->on('devices')->onDelete('cascade');
            $table->foreign('sensors_id')->references('id')
            ->on('sensors');
        }); */
/*         Schema::table('reports', function($table) {
            $table->foreign('creator_id')->references('id')
            ->on('users')->onDelete('cascade');
        }); */
    
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
