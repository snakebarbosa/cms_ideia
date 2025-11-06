<?php

namespace App\Services;

use App\Model\Documento;
use App\Model\Categoria;
use App\Model\Tag;
use App\Model\Artigo;
use App\Helpers\Helpers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Purifier;

class DocumentoService
{
    // Document status constants
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 3;
    
    // Highlight status constants
    const NOT_HIGHLIGHTED = 0;
    const HIGHLIGHTED = 1;

    /**
     * Get documents with filtering and pagination
     */
    public function getDocuments($filters = [], $paginate = true)
    {
        $query = Documento::where('ativado', '<>', self::STATUS_DELETED)
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
     * Get data needed for document forms
     */
    public function getFormData()
    {
        return [
            'tags' => Tag::orderBy('name')->pluck('name', 'id'),
            'categories' => Categoria::where('categoria_tipo', 'documento')->orderBy('titulo', 'desc')->pluck('titulo', 'id')
        ];
    }

    /**
     * Create a new document
     */
    public function createDocument(array $data, $filePT = null, $fileEN = null)
    {
        DB::beginTransaction();
        
        try {
            $document = new Documento();
            $this->fillDocumentData($document, $data);
            
            // Handle file uploads
            if ($filePT && $fileEN) {
                $document->url = $this->handleFileUploads($filePT, $fileEN);
            } else {
                throw new \Exception('Ambos os arquivos (PT e EN) são obrigatórios.');
            }

            $document->save();

            // Handle relationships
            $this->handleDocumentRelations($document, $data);

            DB::commit();
            return $document;
            
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Update an existing document
     */
    public function updateDocument(Documento $document, array $data, $filePT = null, $fileEN = null)
    {
        DB::beginTransaction();
        
        try {
            $this->fillDocumentData($document, $data);
            
            // Handle file updates
            if ($filePT) {
                $this->updateFile($document, $filePT, 'pt');
            }
            
            if ($fileEN) {
                $this->updateFile($document, $fileEN, 'en');
            }

            $document->save();

            // Handle relationships
            $this->handleDocumentRelations($document, $data);

            // Always handle slug updates for existing documents
            $this->updateDocumentSlug($document, $data);

            DB::commit();
            return $document;
            
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Delete a document
     */
    public function deleteDocument(Documento $document)
    {
        DB::beginTransaction();
        
        try {
            // Detach tags
            if (isset($document->tags)) {
                $document->tags()->detach();
            }

            // Delete content relationships
            $document->conteudos()->delete();

            // Delete files
            $this->deleteDocumentFiles($document);

            // Delete the document
            $document->delete();

            DB::commit();
            return true;
            
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Bulk update document status
     */
    public function bulkUpdateStatus(array $ids, string $field, $value)
    {
        return Documento::whereIn('id', $ids)->update([$field => $value]);
    }

    /**
     * Bulk delete documents
     */
    public function bulkDeleteDocuments(array $ids)
    {
        DB::beginTransaction();
        
        try {
            foreach ($ids as $id) {
                $document = Documento::find($id);
                if ($document) {
                    $this->deleteDocumentFiles($document);
                    $document->tags()->detach();
                    $document->conteudos()->delete();
                }
            }

            Documento::whereIn('id', $ids)->delete();
            
            DB::commit();
            return true;
            
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Update single document status
     */
    public function updateDocumentStatus(Documento $document, string $field, $value)
    {
        $document->{$field} = $value;
        $document->save();
        return $document;
    }

    /**
     * Export featured documents
     */
    public function exportFeaturedDocuments()
    {
        $documents = Documento::where('destaque', 1)
                             ->where('ativado', 1)
                             ->orderBy('updated_at', 'desc')
                             ->limit(6)
                             ->get();

        $this->processDocumentsForExport($documents);
        
        $path = storage_path('json/documento.json');
        File::delete($path);
        file_put_contents($path, json_encode($documents, true));
        
        return $documents;
    }

    /**
     * Export latest documents
     */
    public function exportLatestDocuments()
    {
        $documents = Documento::where('ativado', 1)
                             ->orderBy('updated_at', 'desc')
                             ->limit(6)
                             ->get();

        $this->processDocumentsForExport($documents);
        
        $path = storage_path('json/documentoLast.json');
        File::delete($path);
        file_put_contents($path, json_encode($documents, true));
        
        return $documents;
    }

    /**
     * Export random documents
     */
    public function exportRandomDocuments()
    {
        $randomCategory = Documento::where('ativado', 1)->inRandomOrder()->first();
        
        if (!$randomCategory) {
            return collect();
        }

        $documents = Documento::where('idCategoria', $randomCategory->idCategoria)
                             ->where('ativado', 1)
                             ->orderBy('updated_at', 'desc')
                             ->limit(6)
                             ->get();

        $this->processDocumentsForExport($documents);
        
        $path = storage_path('json/documentoRandom.json');
        File::delete($path);
        file_put_contents($path, json_encode($documents, true));
        
        return $documents;
    }

    /**
     * Generate slugs for all documents
     */
    public function createSlugForAllDocuments()
    {
        $documents = Documento::where('ativado', '<>', self::STATUS_DELETED)
                             ->orderBy('created_at', 'DESC')
                             ->get();

        foreach ($documents as $document) {
            if ($document->conteudos->isNotEmpty()) {
                $content = $document->conteudos->first();
                $slugPT = Artigo::createSlug($content->titulo, $document->id, new Documento);
                $slugEN = Artigo::createSlug($content->titulo, $document->id, new Documento);
                
                $slugData = [
                    'pt' => $slugPT,
                    'en' => $slugEN,
                ];

                $document->slug = json_encode($slugData);
                $document->save();
            }
        }

        return $documents->count();
    }

    /**
     * Get document content for editing
     */
    public function getDocumentContent(Documento $document)
    {
        $content = [];
        $documentContent = $document->conteudos;
        
        if ($documentContent->count() > 0) {
            foreach ($documentContent as $conteudo) {
                if ($conteudo->languages->tag == "pt") {
                    $content['conteudoPT'] = $conteudo->texto;
                    $content['tituloPT'] = $conteudo->titulo;
                } else {
                    $content['conteudoEN'] = $conteudo->texto;
                    $content['tituloEN'] = $conteudo->titulo;
                }
            }
        } else {
            $content['conteudoPT'] = $document->descricao;
            $content['tituloPT'] = $document->alias;
            $content['conteudoEN'] = $document->descricao;
            $content['tituloEN'] = $document->alias;
        }

        return $content;
    }

    /**
     * Fill document data from request
     */
    private function fillDocumentData(Documento $document, array $data)
    {
        $document->idTipo = 1;
        $document->nome = $data['tituloPT'];
        $document->alias = $data['tituloPT'];
        $document->idUser = Auth::user()->id;
        $document->ativado = ($data['ativado'] ?? null) === 'on' ? self::STATUS_ACTIVE : self::STATUS_INACTIVE;
        $document->descricao = Purifier::clean($data['textopt']);
        $document->destaque = ($data['destaque'] ?? null) === 'on' ? self::HIGHLIGHTED : self::NOT_HIGHLIGHTED;
        $document->idCategoria = $data['idCategoria'];
        $document->publicar = $data['publicar'];
        $document->data_criacao = $data['data_criacao'];
        $document->despublicar = $data['despublicar'] ?? config('custom.LAST_DATA_FIELD');
    }

    /**
     * Handle file uploads for new documents
     */
    private function handleFileUploads($filePT, $fileEN)
    {
        $location = storage_path('documento/');
        
        $filenamePT = time() . '_pt.' . $filePT->getClientOriginalExtension();
        $filePT->move($location, $filenamePT);

        $filenameEN = time() . '_en.' . $fileEN->getClientOriginalExtension();
        $fileEN->move($location, $filenameEN);

        return json_encode([
            'pt' => $filenamePT,
            'en' => $filenameEN,
        ]);
    }

    /**
     * Update a specific language file
     */
    private function updateFile(Documento $document, $file, $language)
    {
        $files = json_decode($document->url, true);
        $oldFilename = $files[$language];

        // Delete old file
        File::delete(storage_path('documento/' . $oldFilename));

        // Upload new file
        $filename = time() . '_' . $language . '.' . $file->getClientOriginalExtension();
        $location = storage_path('documento/');
        $file->move($location, $filename);

        // Update URL
        $files[$language] = $filename;
        $document->url = json_encode($files);
    }

    /**
     * Handle document relationships (tags, content)
     */
    private function handleDocumentRelations(Documento $document, array $data)
    {
        // Handle slug creation
        Helpers::criarSlug((object)$data, $document);

        // Handle content
        if ($document->wasRecentlyCreated) {
            Helpers::guardarConteudos((object)$data, $document);
        } else {
            $conteudos = $document->conteudos;
            if ($conteudos->count() > 0) {
                Helpers::atualizarConteudo($conteudos, (object)$data, $document);
            } else {
                Helpers::guardarConteudos((object)$data, $document);
            }
        }

        // Handle tags
        if (isset($data['tag'])) {
            $document->tags()->sync($data['tag']);
        }
    }

    /**
     * Delete document files
     */
    private function deleteDocumentFiles(Documento $document)
    {
        if ($this->isJson($document->url)) {
            $files = json_decode($document->url, true);
            foreach ($files as $file) {
                File::delete(storage_path('documento/' . $file));
            }
        } else {
            File::delete(storage_path('documento/' . $document->url));
        }
    }

    /**
     * Process documents for export (truncate content)
     */
    private function processDocumentsForExport($documents)
    {
        foreach ($documents as $document) {
            $document->load(['conteudos.languages', 'categorias', 'tipos']);
            
            foreach ($document->conteudos as $conteudo) {
                if (strlen($conteudo->texto) > 100) {
                    $conteudo->texto = substr($conteudo->texto, 0, 100) . '...';
                }
            }
        }
    }

    /**
     * Check if string is valid JSON
     */
    private function isJson($string, $returnData = false)
    {
        $data = json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE) ? ($returnData ? $data : true) : false;
    }

    /**
     * Update document slug with manual inputs while ensuring uniqueness
     */
    private function updateDocumentSlug(Documento $document, array $data)
    {
        // Get existing slug data
        $existingSlugData = json_decode($document->slug ?? '{}', true);
        $existingSlugPT = $existingSlugData['pt'] ?? null;
        $existingSlugEN = $existingSlugData['en'] ?? null;
        
        // Process PT slug
        $slugPT = isset($data['slug_pt']) && !empty(trim($data['slug_pt'])) ? trim($data['slug_pt']) : null;
        if ($slugPT) {
            // Validate slug format (only letters, numbers, and hyphens)
            $slugPT = Str::slug($slugPT);
            
            // Only check uniqueness if slug has changed from existing one
            if ($slugPT !== $existingSlugPT) {
                // Check uniqueness for PT slug (excluding current document)
                $existingDoc = Documento::where('id', '!=', $document->id)
                    ->where('slug', 'like', '%"pt":"' . $slugPT . '"%')
                    ->first();
                
                if ($existingDoc) {
                    // Make it unique by appending document ID
                    $slugPT = $slugPT . '-' . $document->id;
                }
            }
        } else {
            // Keep existing or auto-generate from tituloPT if empty
            $slugPT = $existingSlugPT ?? Str::slug(($data['tituloPT'] ?? $document->tituloPT) . ' ' . $document->id, '-');
        }
        
        // Process EN slug
        $slugEN = isset($data['slug_en']) && !empty(trim($data['slug_en'])) ? trim($data['slug_en']) : null;
        if ($slugEN) {
            // Validate slug format
            $slugEN = Str::slug($slugEN);
            
            // Only check uniqueness if slug has changed from existing one
            if ($slugEN !== $existingSlugEN) {
                // Check uniqueness for EN slug (excluding current document)
                $existingDoc = Documento::where('id', '!=', $document->id)
                    ->where('slug', 'like', '%"en":"' . $slugEN . '"%')
                    ->first();
                
                if ($existingDoc) {
                    // Make it unique by appending document ID
                    $slugEN = $slugEN . '-' . $document->id;
                }
            }
        } else {
            // Keep existing or auto-generate from tituloEN if empty
            $slugEN = $existingSlugEN ?? Str::slug(($data['tituloEN'] ?? $document->tituloEN) . ' ' . $document->id, '-');
        }
        
        // Create slug JSON
        $slugData = [
            'pt' => $slugPT,
            'en' => $slugEN,
        ];
        
        $document->slug = json_encode($slugData);
        $document->save();
    }
}