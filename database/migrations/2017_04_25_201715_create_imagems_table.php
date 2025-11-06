<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImagemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('imagems', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('idCategoria');
            $table->foreign('idCategoria')->references('id')->on('categorias');
            $table->string('titulo', 100)->unique();
            $table->string('url', 500);
            $table->integer('order')->default('0')->nullable();
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
        Schema::dropIfExists('imagems');
    }
}
