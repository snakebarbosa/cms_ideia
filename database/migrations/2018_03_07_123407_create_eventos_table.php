<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventosTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('eventos', function (Blueprint $table) {
				$table->increments('id');
				$table->string('nome', 500);
				$table->string('endereco', 2500);
				$table->string('latitude', 100)->nullable();
				$table->string('longitude', 100)->nullable();
				$table->string('linkGoogleMaps', 2500)->nullable();
				$table->unsignedInteger('idUser')->nullable();
				$table->foreign('idUser')->references('id')->on('users');
				$table->unsignedInteger('idImagem')->nullable();
				$table->foreign('idImagem')->references('id')->on('imagems');
				$table->unsignedInteger('idCategoria')->nullable();
				$table->foreign('idCategoria')->references('id')->on('categorias');
				$table->string('alias', 100)->nullable();
				$table->string('keyword', 1000)->nullable();
				$table->dateTime('dataInicio');
				$table->dateTime('dataFim')->nullable();
				$table->integer('order')->default('0')->nullable();
				$table->integer('numeroInscricao')->nullable();
				$table->integer('numeroInscrito')->nullable();
				$table->integer('precoIndividual')->nullable();
				$table->dateTime('dataPagamento')->nullable();
				$table->tinyInteger('ativado')->default('0');
				$table->tinyInteger('destaque')->default('0');
				$table->longText('formulario')->nullable();
				$table->longText('descricao')->nullable();
				$table->timestamps();
			});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('eventos');
	}
}
