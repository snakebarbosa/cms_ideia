<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFaqsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('faqs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('idImagem')->nullable();
            $table->foreign('idImagem')->references('id')->on('imagems');
            $table->unsignedInteger('idCategoria')->nullable();
            $table->foreign('idCategoria')->references('id')->on('categorias');
            $table->string('alias', 500)->nullable();
            $table->string('slug', 250);
            $table->string('keyword', 1000)->nullable();
            $table->tinyInteger('ativado')->default('0');
            $table->integer('order')->default('0')->nullable();
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
        Schema::dropIfExists('faqs');
    }
}
