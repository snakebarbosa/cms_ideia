<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTagRelsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('tag_rels', function (Blueprint $table) {
				$table->increments('id');
				$table->integer('idTag')->unsigned();
				$table->foreign('idTag')->references('id')->on('tags');
				$table->integer('idTag2')->unsigned();
				$table->foreign('idTag2')->references('id')->on('tags');
				$table->timestamps();
			});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('tag_rels');
	}
}
