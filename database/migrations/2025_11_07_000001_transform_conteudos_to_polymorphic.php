<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class TransformConteudosToPolymorphic extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Step 1: Add new polymorphic columns
        Schema::table('conteudos', function (Blueprint $table) {
            $table->string('contentable_type')->nullable()->after('id');
            $table->unsignedInteger('contentable_id')->nullable()->after('contentable_type');
            $table->index(['contentable_type', 'contentable_id']);
        });

        // Step 2: Migrate existing data to polymorphic structure
        $this->migrateExistingData();

        // Step 3: Remove old foreign key columns (after verification)
        // Uncomment these after verifying migration worked correctly
        /*
        Schema::table('conteudos', function (Blueprint $table) {
            $table->dropColumn([
                'idFaq',
                'idSlide',
                'idArtigo',
                'idItem',
                'idDocumento',
                'idCategoria'
            ]);
        });
        */
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Restore old columns if needed
        Schema::table('conteudos', function (Blueprint $table) {
            if (!Schema::hasColumn('conteudos', 'idFaq')) {
                $table->unsignedInteger('idFaq')->nullable();
                $table->unsignedInteger('idSlide')->nullable();
                $table->unsignedInteger('idArtigo')->nullable();
                $table->unsignedInteger('idItem')->nullable();
                $table->unsignedInteger('idDocumento')->nullable();
                $table->integer('idCategoria')->nullable();
            }
        });

        // Migrate data back
        $this->restoreOldData();

        // Remove polymorphic columns
        Schema::table('conteudos', function (Blueprint $table) {
            $table->dropIndex(['contentable_type', 'contentable_id']);
            $table->dropColumn(['contentable_type', 'contentable_id']);
        });
    }

    /**
     * Migrate existing data to polymorphic structure
     */
    private function migrateExistingData()
    {
        $mappings = [
            'idArtigo' => 'App\Model\Artigo',
            'idDocumento' => 'App\Model\Documento',
            'idSlide' => 'App\Model\Slide',
            'idItem' => 'App\Model\Item',
            'idFaq' => 'App\Model\Faq',
            'idCategoria' => 'App\Model\Categoria',
        ];

        foreach ($mappings as $column => $modelClass) {
            DB::table('conteudos')
                ->whereNotNull($column)
                ->update([
                    'contentable_type' => $modelClass,
                    'contentable_id' => DB::raw($column)
                ]);

            echo "Migrated {$column} to polymorphic structure\n";
        }

        // Verify migration
        $total = DB::table('conteudos')->count();
        $migrated = DB::table('conteudos')->whereNotNull('contentable_type')->count();
        echo "Total records: {$total}, Migrated: {$migrated}\n";
    }

    /**
     * Restore data to old structure (for rollback)
     */
    private function restoreOldData()
    {
        $mappings = [
            'App\Model\Artigo' => 'idArtigo',
            'App\Model\Documento' => 'idDocumento',
            'App\Model\Slide' => 'idSlide',
            'App\Model\Item' => 'idItem',
            'App\Model\Faq' => 'idFaq',
            'App\Model\Categoria' => 'idCategoria',
        ];

        foreach ($mappings as $modelClass => $column) {
            DB::table('conteudos')
                ->where('contentable_type', $modelClass)
                ->update([
                    $column => DB::raw('contentable_id')
                ]);
        }
    }
}
