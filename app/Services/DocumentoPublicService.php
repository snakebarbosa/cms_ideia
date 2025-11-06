<?php

namespace App\Services;

use App\Model\Documento;
use App\Model\Categoria;
use App\Model\Contador;
use Illuminate\Support\Facades\File;

class DocumentoPublicService
{
    /**
     * Get document by ID with validation
     */
    public function getDocument($id)
    {
        return Documento::with(['conteudos', 'conteudos.languages', 'categorias', 'tags', 'contador'])
            ->where('id', $id)
            ->where('ativado', 1)
            ->where('publicar', '<=', now())
            ->where('despublicar', '>=', now())
            ->first();
    }

    /**
     * Get document by slug
     */
    public function getDocumentBySlug($slug)
    {
        $documents = Documento::with(['conteudos', 'conteudos.languages', 'categorias', 'tags'])
            ->where('ativado', 1)
            ->where('publicar', '<=', now())
            ->where('despublicar', '>=', now())
            ->get();

        foreach ($documents as $document) {
            $slugData = json_decode($document->slug, true);
            
            if ($slugData) {
                foreach ($slugData as $key => $value) {
                    if ($value === $slug) {
                        return $document;
                    }
                }
            }
        }

        return null;
    }

    /**
     * Get documents by category
     */
    public function getDocumentsByCategory($categoryId)
    {
        return Documento::where('idCategoria', $categoryId)
            ->where('ativado', 1)
            ->where('publicar', '<=', now())
            ->where('despublicar', '>=', now())
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get documents by tag
     */
    public function getDocumentsByTag($tagId)
    {
        return Documento::whereHas('tags', function($query) use ($tagId) {
                $query->where('tags.id', $tagId);
            })
            ->where('ativado', 1)
            ->where('publicar', '<=', now())
            ->where('despublicar', '>=', now())
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get featured documents
     */
    public function getFeaturedDocuments($limit = 5)
    {
        return Documento::where('destaque', 1)
            ->where('ativado', 1)
            ->where('publicar', '<=', now())
            ->where('despublicar', '>=', now())
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get latest documents
     */
    public function getLatestDocuments($limit = 5)
    {
        return Documento::where('ativado', 1)
            ->where('publicar', '<=', now())
            ->where('despublicar', '>=', now())
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get random documents
     */
    public function getRandomDocuments($limit = 5)
    {
        return Documento::where('ativado', 1)
            ->where('publicar', '<=', now())
            ->where('despublicar', '>=', now())
            ->inRandomOrder()
            ->limit($limit)
            ->get();
    }

    /**
     * Open document file
     */
    public function openDocument($filename)
    {
        $filePath = storage_path('documento/' . $filename);

        if (!File::exists($filePath)) {
            abort(404, 'Document not found');
        }

        return response()->file($filePath);
    }

    /**
     * Increment document download counter
     * Uses both legacy and new polymorphic approach for compatibility
     */
    public function incrementDownloads($documentId)
    {
        $document = Documento::find($documentId);
        
        if ($document) {
            // New polymorphic approach
            Contador::create([
                'countable_type' => 'App\Model\Documento',
                'countable_id' => $documentId,
                'action_type' => 'download',
                'idDocumento' => $documentId,  // Legacy column for backward compatibility
                'ip' => request()->ip(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Get document download count
     * Supports both legacy and polymorphic counts
     */
    public function getDownloadCount($documentId)
    {
        // Count using polymorphic relationship
        $polymorphicCount = Contador::where('countable_type', 'App\Model\Documento')
            ->where('countable_id', $documentId)
            ->where('action_type', 'download')
            ->count();

        // Count using legacy column (for old data)
        $legacyCount = Contador::where('idDocumento', $documentId)
            ->whereNull('countable_type')
            ->count();

        return $polymorphicCount + $legacyCount;
    }

    /**
     * Get document content by language
     */
    public function getDocumentContent($document, $languageId = null)
    {
        if (!$document) {
            return null;
        }

        $languageId = $languageId ?? session('lan', 0);

        foreach ($document->conteudos as $content) {
            if ($content->idLanguage == $languageId) {
                return $content;
            }
        }

        return $document->conteudos->first();
    }

    /**
     * Get slug from document ID
     */
    public function getSlugById($id)
    {
        $document = Documento::find($id);
        
        if (!$document) {
            return null;
        }

        $slugData = json_decode($document->slug, true);
        $currentLang = session('lan', 0);

        // Try to get slug by current language key first
        if (isset($slugData[$currentLang])) {
            return $slugData[$currentLang];
        }

        // Fallback: return first slug found
        return $slugData ? reset($slugData) : null;
    }

    /**
     * Get ID from slug
     */
    public function getIdBySlug($slug)
    {
        $documents = Documento::all();

        foreach ($documents as $document) {
            $slugData = json_decode($document->slug, true);
            
            if ($slugData) {
                foreach ($slugData as $key => $value) {
                    if ($value === $slug) {
                        return $document->id;
                    }
                }
            }
        }

        return null;
    }

    /**
     * Get document URL by language
     */
    public function getDocumentUrl($document, $lang = 'pt')
    {
        if (!$document) {
            return null;
        }

        $urls = json_decode($document->url, true);
        return $urls[$lang] ?? null;
    }
}
