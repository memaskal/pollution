<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMeasurementValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('measurement_values', function (Blueprint $table) {

            $table->unsignedInteger('measurement_id');
            $table->unsignedTinyInteger('hour');
            $table->float('value');

            $table->foreign('measurement_id')->references('id')->on('measurements')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->primary(['measurement_id', 'hour']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('measurement_values');
    }
}
