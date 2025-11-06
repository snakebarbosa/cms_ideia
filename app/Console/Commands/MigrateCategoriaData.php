<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Model\Categoria;

class MigrateCategoriaData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'categoria:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate categoria data from boolean fields (artigo, documento, etc) to categoria_tipo field';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Starting categoria migration...');
        
        // First, check if categoria_tipo column exists
        if (!$this->checkCategoriaTypeColumn()) {
            $this->error('categoria_tipo column does not exist. Please run migrations first.');
            return Command::FAILURE;
        }

        // Get all categories that don't have categoria_tipo set
        $categoriasToMigrate = DB::table('categorias')
            ->whereNull('categoria_tipo')
            ->orWhere('categoria_tipo', '')
            ->get();

        if ($categoriasToMigrate->isEmpty()) {
            $this->info('No categories need migration. All categories already have categoria_tipo set.');
            return Command::SUCCESS;
        }

        $this->info("Found {$categoriasToMigrate->count()} categories to migrate.");

        $migrated = 0;
        $skipped = 0;

        $this->output->progressStart($categoriasToMigrate->count());

        foreach ($categoriasToMigrate as $categoria) {
            $tipo = $this->determineCategoriaType($categoria);
            
            if ($tipo) {
                DB::table('categorias')
                    ->where('id', $categoria->id)
                    ->update(['categoria_tipo' => $tipo]);
                $migrated++;
            } else {
                $this->warn("Could not determine type for categoria ID: {$categoria->id} (titulo: {$categoria->titulo})");
                $skipped++;
            }
            
            $this->output->progressAdvance();
        }

        $this->output->progressFinish();

        $this->info("\n✓ Migration completed successfully!");
        $this->info("  - Migrated: {$migrated}");
        if ($skipped > 0) {
            $this->warn("  - Skipped: {$skipped}");
        }

        // Show summary of categoria types
        $this->showSummary();

        return Command::SUCCESS;
    }

    /**
     * Check if categoria_tipo column exists
     */
    private function checkCategoriaTypeColumn()
    {
        try {
            $columns = DB::getSchemaBuilder()->getColumnListing('categorias');
            return in_array('categoria_tipo', $columns);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Determine categoria type from boolean fields
     */
    private function determineCategoriaType($categoria)
    {
        $booleanFields = [
            'artigo' => 'artigo',
            'documento' => 'documento',
            'imagem' => 'imagem',
            'link' => 'link',
            'faq' => 'faq',
            'evento' => 'evento'
        ];

        foreach ($booleanFields as $field => $tipo) {
            if (isset($categoria->$field) && $categoria->$field == 1) {
                return $tipo;
            }
        }

        return null;
    }

    /**
     * Show summary of categoria types
     */
    private function showSummary()
    {
        $this->info("\nCategoria Type Distribution:");
        
        $summary = DB::table('categorias')
            ->select('categoria_tipo', DB::raw('count(*) as total'))
            ->whereNotNull('categoria_tipo')
            ->groupBy('categoria_tipo')
            ->get();

        $table = [];
        foreach ($summary as $row) {
            $table[] = [$row->categoria_tipo, $row->total];
        }

        $this->table(['Type', 'Count'], $table);

        // Show any categories without tipo
        $withoutTipo = DB::table('categorias')
            ->whereNull('categoria_tipo')
            ->orWhere('categoria_tipo', '')
            ->count();

        if ($withoutTipo > 0) {
            $this->warn("\n⚠ {$withoutTipo} categories still don't have categoria_tipo set.");
        }
    }
}
