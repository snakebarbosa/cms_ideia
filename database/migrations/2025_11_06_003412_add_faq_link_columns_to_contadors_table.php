<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contadors', function (Blueprint $table) {
            $table->integer('idFaq')->nullable()->after('idArtigo');
            $table->integer('idLink')->nullable()->after('idFaq');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contadors', function (Blueprint $table) {
            $table->dropColumn(['idFaq', 'idLink']);
        });
    }
};
