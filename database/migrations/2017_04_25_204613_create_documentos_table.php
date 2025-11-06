<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentosTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('documentos', function (Blueprint $table) {
				$table->increments('id');
				$table->unsignedInteger('idCategoria');
				$table->foreign('idCategoria')->references('id')->on('categorias');
				$table->unsignedInteger('idUser')->nullable();
				$table->foreign('idUser')->references('id')->on('users');
				$table->string('nome', 500);
				$table->string('descricao', 2500);
				$table->string('url', 500);
				 $table->string('slug', 250);
				$table->string('alias', 200);
				$table->string('filezise', 200)->default('0')->nullable();
				$table->integer('order')->default('0')->nullable();
				$table->tinyInteger('destaque')->default('0');
				$table->tinyInteger('ativado')->default('0');
				$table->timestamps();

			});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('documentos');
	}
}
