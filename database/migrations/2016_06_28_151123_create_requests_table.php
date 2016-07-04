<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requests', function (Blueprint $table) {

            $table->unsignedInteger('user_id');
            $table->unsignedInteger('request_type');
            $table->unsignedInteger('total')->default(0);

            $table->foreign('user_id')->references('id')->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('request_type')->references('id')->on('request_types')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->primary(['user_id', 'request_type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('requests');
    }
}
