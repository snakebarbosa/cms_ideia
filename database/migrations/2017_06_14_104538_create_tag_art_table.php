<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTagArtTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tag_art', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idArtigo')->unsigned();
            $table->foreign('idArtigo')->references('id')->on('artigos');
            $table->integer('idTag')->unsigned();
            $table->foreign('idTag')->references('id')->on('tags');
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
        Schema::dropIfExists('tag_art');
    }
}
