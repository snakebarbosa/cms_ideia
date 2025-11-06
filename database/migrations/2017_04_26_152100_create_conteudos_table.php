<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConteudosTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('conteudos', function (Blueprint $table) {
				$table->increments('id');
				$table->unsignedInteger('idFaq')->nullable();
				$table->foreign('idFaq')->references('id')->on('faqs');
				$table->unsignedInteger('idSlide')->nullable();
				$table->foreign('idSlide')->references('id')->on('slides');
				$table->unsignedInteger('idArtigo')->nullable();
				$table->foreign('idArtigo')->references('id')->on('artigos');
				$table->unsignedInteger('idLanguage')->nullable();
				$table->foreign('idLanguage')->references('id')->on('languages');
				$table->unsignedInteger('idItem')->nullable();
				$table->foreign('idItem')->references('id')->on('items');
				$table->foreign('idDocumento')->references('id')->on('documentos');
				$table->unsignedInteger('idDocumento')->nullable();
				$table->foreign('idCategoria')->references('id')->on('categorias');
				$table->unsignedInteger('idCategoria')->nullable();
				$table->string('titulo', 500);
				$table->longText('texto', 20000);
				$table->tinyInteger('ativado')->default('1');
				$table->timestamps();
			});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('conteudos');
	}
}
