<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTagItemsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('tag_items', function (Blueprint $table) {
				$table->increments('id');
				$table->integer('idItem')->unsigned();
				$table->foreign('idItem')->references('id')->on('items');
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
		Schema::dropIfExists('tag_items');
	}
}
