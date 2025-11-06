<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArtDocsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('art_docs', function (Blueprint $table) {
				$table->increments('id');
				$table->integer('idArtigo')->unsigned();
				$table->foreign('idArtigo')->references('id')->on('artigos');
				$table->integer('idDocumento')->unsigned();
				$table->foreign('idDocumento')->references('id')->on('documentos');
				$table->timestamps();
			});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('art_docs');
	}
}
