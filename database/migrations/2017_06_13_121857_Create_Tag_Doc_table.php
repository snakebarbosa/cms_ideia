<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTagDocTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('tag_doc', function (Blueprint $table) {
				$table->increments('id');
				$table->integer('idDoc')->unsigned();
				$table->foreign('idDoc')->references('id')->on('documentos');
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
	public function down() {
		Schema::dropIfExists('tag_doc');
	}
}
