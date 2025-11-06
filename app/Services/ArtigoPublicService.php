<?php

namespace App\Services;

use App\Model\Artigo;
use App\Model\Categoria;
use App\Model\Contador;
use Illuminate\Support\Str;

class ArtigoPublicService
{
    /**
     * Get article by ID with validation
     */
    public function getArticle($id)
    {
        return Artigo::with(['conteudos', 'conteudos.languages', 'categorias', 'tags', 'anexos', 'imagems'])
            ->where('id', $id)
            ->where('ativado', 1)
            ->where('publicar', '<=', now())
            ->where('despublicar', '>=', now())
            ->first();
    }

    /**
     * Get article by slug
     */
    public function getArticleBySlug($slug)
    {
        $articles = Artigo::with(['conteudos', 'conteudos.languages', 'categorias', 'tags', 'anexos', 'imagems'])
            ->where('ativado', 1)
            ->where('publicar', '<=', now())
            ->where('despublicar', '>=', now())
            ->get();

        foreach ($articles as $article) {
            $slugData = json_decode($article->slug, true);
            
            // Check all possible slug values (both numeric and string keys)
            if (is_array($slugData)) {
                foreach ($slugData as $key => $value) {
                    if ($value === $slug) {
                        return $article;
                    }
                }
            }
        }

        return null;
    }

    /**
     * Get articles by category
     */
    public function getArticlesByCategory($categoryId)
    {
        return Artigo::where('idCategoria', $categoryId)
            ->where('ativado', 1)
            ->where('publicar', '<=', now())
            ->where('despublicar', '>=', now())
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get articles by tag
     */
    public function getArticlesByTag($tagId)
    {
        return Artigo::whereHas('tags', function($query) use ($tagId) {
                $query->where('tags.id', $tagId);
            })
            ->where('ativado', 1)
            ->where('publicar', '<=', now())
            ->where('despublicar', '>=', now())
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get recent news articles
     */
    public function getRecentNews($limit = 5)
    {
        $newsCategory = Categoria::where('categoria_tipo', 'artigo')
            ->where('titulo', 'Noticias')
            ->first();

        if (!$newsCategory) {
            return collect();
        }

        return Artigo::where('idCategoria', $newsCategory->id)
            ->where('ativado', 1)
            ->where('publicar', '<=', now())
            ->where('despublicar', '>=', now())
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get all news articles
     */
    public function getAllNews($limit = 5)
    {
        $newsCategory = Categoria::where('categoria_tipo', 'artigo')
            ->where('titulo', 'Noticias')
            ->first();

        if (!$newsCategory) {
            return collect();
        }

        return Artigo::where('idCategoria', $newsCategory->id)
            ->where('ativado', 1)
            ->where('publicar', '<=', now())
            ->where('despublicar', '>=', now())
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get article content by language
     */
    public function getArticleContent($article, $languageId = null)
    {
        if (!$article) {
            return null;
        }

        $languageId = $languageId ?? session('lan', 0);

        foreach ($article->conteudos as $content) {
            if ($content->idLanguage == $languageId) {
                return $content;
            }
        }

        return $article->conteudos->first();
    }

    /**
     * Increment article view counter
     * Uses both legacy and new polymorphic approach for compatibility
     */
    public function incrementViews($articleId)
    {
        $article = Artigo::find($articleId);
        
        if ($article) {
            // New polymorphic approach
            Contador::create([
                'countable_type' => 'App\Model\Artigo',
                'countable_id' => $articleId,
                'action_type' => 'view',
                'idArtigo' => $articleId,  // Legacy column for backward compatibility
                'ip' => request()->ip(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Get article view count
     * Supports both legacy and polymorphic counts
     */
    public function getViewCount($articleId)
    {
        // Count using polymorphic relationship
        $polymorphicCount = Contador::where('countable_type', 'App\Model\Artigo')
            ->where('countable_id', $articleId)
            ->where('action_type', 'view')
            ->count();

        // Count using legacy column (for old data)
        $legacyCount = Contador::where('idArtigo', $articleId)
            ->whereNull('countable_type')
            ->count();

        return $polymorphicCount + $legacyCount;
    }

    /**
     * Get slug from article ID
     */
    public function getSlugById($id)
    {
        $article = Artigo::find($id);
        
        if (!$article) {
            return null;
        }

        $slugData = json_decode($article->slug, true);
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
        $articles = Artigo::all();

        foreach ($articles as $article) {
            $slugData = json_decode($article->slug, true);
            
            if ($slugData) {
                foreach ($slugData as $key => $value) {
                    if ($value === $slug) {
                        return $article->id;
                    }
                }
            }
        }

        return null;
    }
}
