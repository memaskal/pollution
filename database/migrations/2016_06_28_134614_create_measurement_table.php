<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMeasurementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('measurements', function (Blueprint $table) {
            $table->increments('id');
            $table->char('station_id', 5);
            $table->enum('pollution_type', ['CO','NO','NO2','O3','SO2','C6H6','Smoke']);
            $table->date('date');
            
            $table->foreign('station_id')->references('id')->on('stations')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');

            $table->unique(['station_id', 'pollution_type', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('measurements');
    }
}
