<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTiposTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('tipos', function (Blueprint $table) {
				$table->increments('id');
				$table->string('titulo', 50)->nullable();
				$table->tinyInteger('doc')->default('0');
				$table->tinyInteger('menu')->default('0');
				$table->tinyInteger('user')->default('0');
				$table->tinyInteger('ativado')->default('1');
				$table->string('posicao')->nullable();
				$table->integer('order')->default('0')->nullable();
				$table->timestamps();

			});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('tipos');
	}
}
