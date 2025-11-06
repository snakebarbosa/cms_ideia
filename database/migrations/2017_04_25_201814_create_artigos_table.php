<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArtigosTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('artigos', function (Blueprint $table) {
				$table->increments('id');
				$table->unsignedInteger('idUser')->nullable();
				$table->foreign('idUser')->references('id')->on('users');
				$table->unsignedInteger('idImagem')->nullable();
				$table->foreign('idImagem')->references('id')->on('imagems');
				$table->unsignedInteger('idCategoria')->nullable();
				$table->foreign('idCategoria')->references('id')->on('categorias');
				 $table->string('slug', 250);
				$table->string('alias', 100)->nullable();
				$table->integer('order')->default('0')->nullable();
				$table->string('keyword', 1000)->nullable();
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
	public function down() {
		Schema::dropIfExists('artigos');
	}
}
