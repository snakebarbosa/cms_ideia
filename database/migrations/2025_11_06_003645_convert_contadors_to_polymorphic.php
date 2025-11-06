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
            // Add polymorphic columns
            $table->string('countable_type')->nullable()->after('id');
            $table->unsignedBigInteger('countable_id')->nullable()->after('countable_type');
            
            // Add action type (view, download, click, etc.)
            $table->string('action_type', 20)->default('view')->after('countable_id');
            
            // Add index for better performance
            $table->index(['countable_type', 'countable_id']);
        });
        
        // Migrate existing data
        DB::statement('UPDATE contadors SET countable_type = "App\\\\Model\\\\Artigo", countable_id = idArtigo, action_type = "view" WHERE idArtigo IS NOT NULL');
        DB::statement('UPDATE contadors SET countable_type = "App\\\\Model\\\\Documento", countable_id = idDocumento, action_type = "download" WHERE idDocumento IS NOT NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contadors', function (Blueprint $table) {
            $table->dropIndex(['countable_type', 'countable_id']);
            $table->dropColumn(['countable_type', 'countable_id', 'action_type']);
        });
    }
};
