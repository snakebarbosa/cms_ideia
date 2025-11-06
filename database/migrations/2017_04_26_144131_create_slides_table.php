<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSlidesTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('slides', function (Blueprint $table) {
				$table->increments('id');
				$table->unsignedInteger('idImagem')->nullable();
				$table->foreign('idImagem')->references('id')->on('imagems');
				$table->string('alias', 100)->nullable();
				$table->string('url', 500)->nullable();
				$table->integer('order')->default('0')->nullable();
				$table->tinyInteger('ativado')->default('0');
				$table->string('posicao')->nullable();
				$table->timestamps();
			});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('slides');
	}
}
