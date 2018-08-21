<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateServedBiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('served_bies', function(Blueprint $table)
        {
            $table->increments('id');
            $table->timestamps();
            $table->integer('service_id')->unsigned()->index();
            $table->integer('resource_id')->unsigned()->index();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('served_bies');
    }
}
