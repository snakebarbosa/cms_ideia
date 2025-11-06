<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTagFaqsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tag_faqs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idFaq')->unsigned();
            $table->foreign('idFaq')->references('id')->on('faqs');
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
        Schema::dropIfExists('tag_faqs');
    }
}
