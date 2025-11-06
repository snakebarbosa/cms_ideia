<?php

namespace App\Services;

use App\Model\Artigo;
use App\Model\Categoria;
use App\Model\Tag;
use App\Model\Documento;
use App\Helpers\Helpers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ArtigoService
{
    // Article status constants
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 3;
    
    // Highlight status constants
    const NOT_HIGHLIGHTED = 0;
    const HIGHLIGHTED = 1;

    /**
     * Get articles with filtering and pagination
     */
    public function getArticles($filters = [], $paginate = true)
    {
        $query = Artigo::with(['anexos', 'imagems'])
                      ->where('ativado', '<>', self::STATUS_DELETED)
                      ->orderBy('created_at', 'DESC');

        if (isset($filters['status'])) {
            $query->where('ativado', $filters['status']);
        }

        if (isset($filters['destacado'])) {
            $query->where('destaque', $filters['destacado']);
        }

        if (isset($filters['categoria'])) {
            $query->where('idCategoria', $filters['categoria']);
        }

        return $paginate ? $query->paginate(15) : $query->get();
    }

    /**
     * Get data needed for article forms
     */
    public function getFormData()
    {
        return [
            'tags' => Tag::orderBy('name')->pluck('name', 'id'),
            'categories' => Categoria::where('categoria_tipo', 'artigo')->orderBy('titulo', 'desc')->pluck('titulo', 'id'),
            'imageCategories' => Categoria::where('categoria_tipo', 'imagem')->where('ativado', 1)->pluck('titulo', 'id'),
            'categoryTree' => (new Categoria)->tree('imagem'),
            'documents' => Documento::orderBy('created_at', 'desc')->get()
        ];
    }

    /**
     * Create a new article
     */
    public function createArticle(array $data)
    {
        return DB::transaction(function () use ($data) {
            $article = new Artigo();
            $this->fillArticleData($article, $data);
            $article->idUser = Auth::user()->id;
            $article->save();

            $this->handleArticleRelations($article, $data);

            return $article;
        });
    }

    /**
     * Update an existing article
     */
    public function updateArticle(Artigo $article, array $data)
    {
        return DB::transaction(function () use ($article, $data) {
            $this->fillArticleData($article, $data);
            $article->save();

            $this->handleArticleRelations($article, $data);

            return $article;
        });
    }

    /**
     * Fill article with data from request
     */
    private function fillArticleData(Artigo $article, array $data)
    {
        $article->alias = $data['tituloPT'] ?? $article->alias;
        $article->ativado = ($data['ativado'] ?? null) === 'on' ? self::STATUS_ACTIVE : self::STATUS_INACTIVE;
        $article->destaque = ($data['destaque'] ?? null) === 'on' ? self::HIGHLIGHTED : self::NOT_HIGHLIGHTED;
        $article->publicar = $data['publicar'] ?? $article->publicar;
        $article->data_criacao = $data['data_criacao'] ?? $article->data_criacao;
        $article->despublicar = $data['despublicar'] ?? config('custom.LAST_DATA_FIELD');
        $article->idCategoria = $data['idCategoria'] ?? $article->idCategoria;
        $article->keyword = $data['keyword'] ?? $article->keyword;

        // Handle manual slug input for existing articles
        if (!$article->wasRecentlyCreated && (isset($data['slug_pt']) || isset($data['slug_en']))) {
            $slugData = json_decode($article->slug ?? '{}', true);
            $hasChanges = false;
            
            // Handle PT slug
            if (isset($data['slug_pt']) && !empty($data['slug_pt'])) {
                // Validate PT slug uniqueness
                $ptSlugExists = Artigo::where('id', '!=', $article->id)
                    ->whereRaw("JSON_EXTRACT(slug, '$.pt') = ?", [$data['slug_pt']])
                    ->exists();
                
                if (!$ptSlugExists) {
                    $slugData['pt'] = $data['slug_pt'];
                    $hasChanges = true;
                } else {
                    throw new \Exception('O slug PT "' . $data['slug_pt'] . '" já está em uso por outro artigo.');
                }
            }
            
            // Handle EN slug
            if (isset($data['slug_en']) && !empty($data['slug_en'])) {
                // Validate EN slug uniqueness
                $enSlugExists = Artigo::where('id', '!=', $article->id)
                    ->whereRaw("JSON_EXTRACT(slug, '$.en') = ?", [$data['slug_en']])
                    ->exists();
                
                if (!$enSlugExists) {
                    $slugData['en'] = $data['slug_en'];
                    $hasChanges = true;
                } else {
                    throw new \Exception('O slug EN "' . $data['slug_en'] . '" já está em uso por outro artigo.');
                }
            }
            
            // Update the article slug if there were changes
            if ($hasChanges) {
                $article->slug = json_encode($slugData);
            }
        }

        if (isset($data['idimagem']) && is_numeric($data['idimagem'])) {
            $article->idImagem = $data['idimagem'];
        }
    }

    /**
     * Handle article relationships (tags, content, documents)
     */
    private function handleArticleRelations(Artigo $article, array $data)
    {
        // Handle slug creation - only auto-generate if no manual slugs provided
        $shouldAutoGenerateSlug = true;
        
        // For existing articles, check if user provided manual slugs
        if (!$article->wasRecentlyCreated && 
            ((isset($data['slug_pt']) && !empty($data['slug_pt'])) || 
             (isset($data['slug_en']) && !empty($data['slug_en'])))) {
            $shouldAutoGenerateSlug = false;
        }
        
        // For new articles, always auto-generate slug
        // For existing articles, use the new helper to handle manual inputs
        if ($article->wasRecentlyCreated) {
            Helpers::criarSlug((object)$data, $article);
        } else {
            // For existing articles, use the updated helper that handles manual inputs
            Helpers::updateSlugWithManualInputs((object)$data, $article);
        }

        // Handle content
        if ($article->wasRecentlyCreated) {
            Helpers::guardarConteudos((object)$data, $article);
        } else {
            $conteudos = $article->conteudos;
            Helpers::atualizarConteudo($conteudos, (object)$data, $article);
        }

        // Handle tags
        $tags = $data['tag'] ?? [];
        $article->tags()->sync($tags);

        // Handle documents
        $this->handleDocuments($article, $data);
    }

    /**
     * Handle document attachments
     */
    private function handleDocuments(Artigo $article, array $data)
    {
        if (isset($data['iddocumentoartigo'][0]) && !empty($data['iddocumentoartigo'][0])) {
            $documentIds = explode(',', $data['iddocumentoartigo'][0]);
            $documentIds = array_filter($documentIds); // Remove empty values
            $article->anexos()->sync($documentIds);
        } else {
            $article->anexos()->detach();
        }
    }

    /**
     * Update article status
     */
    public function updateStatus(int $articleId, string $field, int $value)
    {
        $article = Artigo::findOrFail($articleId);
        $article->update([$field => $value]);
        return $article;
    }

    /**
     * Bulk update articles
     */
    public function bulkUpdate(array $articleIds, array $updates)
    {
        return Artigo::whereIn('id', $articleIds)->update($updates);
    }

    /**
     * Delete article with cleanup
     */
    public function deleteArticle(int $articleId)
    {
        return DB::transaction(function () use ($articleId) {
            $article = Artigo::findOrFail($articleId);

            // Clean up relationships
            $article->tags()->detach();
            $article->conteudos()->delete();
            $article->anexos()->detach();

            return $article->delete();
        });
    }

    /**
     * Bulk delete articles
     */
    public function bulkDelete(array $articleIds)
    {
        return DB::transaction(function () use ($articleIds) {
            foreach ($articleIds as $id) {
                $this->deleteArticle($id);
            }
        });
    }

    /**
     * Get highlighted articles for export
     */
    public function getHighlightedArticles()
    {
        return Artigo::where('destaque', self::HIGHLIGHTED)
                    ->where('ativado', self::STATUS_ACTIVE)
                    ->orderBy('created_at', 'DESC')
                    ->get();
    }

    /**
     * Transform articles for export
     */
    public function transformForExport($articles)
    {
        $transformed = [];

        foreach ($articles as $article) {
            $articleData = $article->toArray();
            
            // Process content
            if ($article->conteudos) {
                foreach ($article->conteudos as $content) {
                    if (strlen($content->texto) > 100) {
                        $content->texto = substr($content->texto, 0, 100) . '...';
                    }
                    // Remove unwanted fields
                    $this->removeUnwantedFields($content, [
                        'created_at', 'updated_at', 'idArtigo', 'idDocumento', 
                        'idFaq', 'idSlide', 'idItem', 'ativado'
                    ]);
                }
            }

            // Remove unwanted article fields
            $this->removeUnwantedFields($articleData, [
                'destaque', 'updated_at', 'idUser', 'ativado'
            ]);

            $transformed[] = $articleData;
        }

        return $transformed;
    }

    /**
     * Remove unwanted fields from array
     */
    private function removeUnwantedFields(&$data, array $fields)
    {
        foreach ($fields as $field) {
            unset($data[$field]);
        }
    }

    /**
     * Export articles to JSON
     */
    public function exportToJson()
    {
        $articles = $this->getHighlightedArticles();
        $transformed = $this->transformForExport($articles);
        
        $path = storage_path('json/artigo.json');
        
        // Ensure directory exists
        $directory = dirname($path);
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        // Delete existing file
        if (File::exists($path)) {
            File::delete($path);
        }

        // Write new file
        file_put_contents($path, json_encode($transformed, JSON_PRETTY_PRINT));

        return $path;
    }
}