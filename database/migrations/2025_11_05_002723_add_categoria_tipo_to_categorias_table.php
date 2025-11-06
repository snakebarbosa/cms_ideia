<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categorias', function (Blueprint $table) {
            // Add new categoria_tipo field
            $table->string('categoria_tipo', 50)->nullable()->after('parent');
        });

        // Migrate existing data from boolean fields to categoria_tipo
        DB::statement("
            UPDATE categorias 
            SET categoria_tipo = CASE
                WHEN artigo = 1 THEN 'artigo'
                WHEN documento = 1 THEN 'documento'
                WHEN imagem = 1 THEN 'imagem'
                WHEN link = 1 THEN 'link'
                WHEN faq = 1 THEN 'faq'
                WHEN evento = 1 THEN 'evento'
                ELSE NULL
            END
        ");

        // Make categoria_tipo not nullable after migration
        Schema::table('categorias', function (Blueprint $table) {
            $table->string('categoria_tipo', 50)->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categorias', function (Blueprint $table) {
            $table->dropColumn('categoria_tipo');
        });
    }
};
