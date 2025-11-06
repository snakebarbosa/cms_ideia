<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('links', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('idCategoria')->nullable();
            $table->foreign('idCategoria')->references('id')->on('categorias');
            $table->string('titulo', 300);
            $table->string('url', 500);
            $table->string('keyword', 100)->nullable();
            $table->integer('order')->default('0')->nullable();
            $table->tinyInteger('ativado')->default('0');
            $table->tinyInteger('destaque')->default('0');
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
        Schema::dropIfExists('links');
    }
}
