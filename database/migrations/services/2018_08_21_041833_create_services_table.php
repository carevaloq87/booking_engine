<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function(Blueprint $table)
        {
            $table->increments('id');
            $table->timestamps();
            $table->string('name', 255)->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('color')->nullable();
            $table->string('description', 1000)->nullable();
            $table->string('duration')->nullable();
            $table->string('listed_duration')->nullable();
            $table->string('spaces')->nullable();
            $table->integer('service_provider_id')->unsigned()->nullable()->index();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('services');
    }
}
