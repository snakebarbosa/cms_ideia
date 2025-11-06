<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTagLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tag_links', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idLink')->unsigned();
            $table->foreign('idLink')->references('id')->on('links');
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
        Schema::dropIfExists('tag_links');
    }
}
