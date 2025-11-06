<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('items', function (Blueprint $table){
            $table->increments('id');
            $table->string('titulo', 100);
            $table->unsignedInteger('parent')->default(0);
            $table->unsignedInteger('idImagem')->nullable();
            $table->foreign('idImagem')->references('id')->on('imagems');
            $table->unsignedInteger('idTipo');
            $table->foreign('idTipo')->references('id')->on('tipos');
            $table->tinyInteger('default')->default('0');
            $table->string('url', 1000);
            $table->tinyInteger('ativado')->default('1');
            $table->integer('order')->default('0')->nullable();
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
         Schema::dropIfExists('items');
    }
}
