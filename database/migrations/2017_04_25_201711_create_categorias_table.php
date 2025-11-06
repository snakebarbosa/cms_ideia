<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categorias', function (Blueprint $table) {
            $table->increments('id');
            //  $table->unsignedInteger('idUser')->nullable();
            // $table->foreign('idUser')->references('id')->on('users');
            $table->unsignedInteger('idTipo')->nullable();
            $table->foreign('idTipo')->references('id')->on('tipos');
            $table->unsignedInteger('parent')->default(0);
            // $table->foreign('parent')->references('id')->on('categorias');
            $table->tinyInteger('artigo')->default('0');
            $table->tinyInteger('link')->default('0');
            $table->tinyInteger('faq')->default('0');
            $table->tinyInteger('evento')->default('0');
            $table->tinyInteger('documento')->default('0');
            $table->tinyInteger('imagem')->default('0');
            $table->tinyInteger('default')->default('0');
            $table->integer('order')->default('0')->nullable();
            $table->string('titulo', 150);
             $table->string('slug', 250);
            $table->tinyInteger('ativado')->default('1');
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
        Schema::dropIfExists('categorias');
    }
}
