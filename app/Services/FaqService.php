<?php

namespace App\Services;

use App\Model\Faq;
use App\Model\Categoria;
use App\Model\Contador;

class FaqService
{
    /**
     * Get FAQ by ID with validation
     */
    public function getFaq($id)
    {
        return Faq::with(['conteudos', 'conteudos.languages', 'categorias', 'tags'])
            ->where('id', $id)
            ->where('ativado', 1)
            ->first();
    }

    /**
     * Get FAQ by slug
     */
    public function getFaqBySlug($slug)
    {
        $faqs = Faq::with(['conteudos', 'conteudos.languages', 'categorias', 'tags'])
            ->where('ativado', 1)
            ->get();

        foreach ($faqs as $faq) {
            $slugData = json_decode($faq->slug, true);
            
            if ($slugData) {
                foreach ($slugData as $key => $value) {
                    if ($value === $slug) {
                        return $faq;
                    }
                }
            }
        }

        return null;
    }

    /**
     * Get FAQs by category
     */
    public function getFaqsByCategory($categoryId)
    {
        return Faq::where('idCategoria', $categoryId)
            ->where('ativado', 1)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get FAQs by tag
     */
    public function getFaqsByTag($tagId)
    {
        return Faq::whereHas('tags', function($query) use ($tagId) {
                $query->where('tags.id', $tagId);
            })
            ->where('ativado', 1)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get all FAQs
     */
    public function getAllFaqs()
    {
        return Faq::where('ativado', 1)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get featured FAQs
     */
    public function getFeaturedFaqs($limit = 5)
    {
        return Faq::where('destaque', 1)
            ->where('ativado', 1)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get FAQ content by language
     */
    public function getFaqContent($faq, $languageId = null)
    {
        if (!$faq) {
            return null;
        }

        $languageId = $languageId ?? session('lan', 0);

        foreach ($faq->conteudos as $content) {
            if ($content->idLanguage == $languageId) {
                return $content;
            }
        }

        return $faq->conteudos->first();
    }

    /**
     * Get slug from FAQ ID
     */
    public function getSlugById($id)
    {
        $faq = Faq::find($id);
        
        if (!$faq) {
            return null;
        }

        $slugData = json_decode($faq->slug, true);
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
        $faqs = Faq::all();

        foreach ($faqs as $faq) {
            $slugData = json_decode($faq->slug, true);
            
            if ($slugData) {
                foreach ($slugData as $key => $value) {
                    if ($value === $slug) {
                        return $faq->id;
                    }
                }
            }
        }

        return null;
    }

    /**
     * Increment FAQ view count
     * Uses both legacy and new polymorphic approach for compatibility
     */
    public function incrementViews($faqId)
    {
        $faq = Faq::find($faqId);
        
        if ($faq) {
            // New polymorphic approach
            Contador::create([
                'countable_type' => 'App\Model\Faq',
                'countable_id' => $faqId,
                'action_type' => 'view',
                'idFaq' => $faqId,  // Legacy column for backward compatibility
                'ip' => request()->ip(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Get FAQ view count
     * Supports both legacy and polymorphic counts
     */
    public function getViewCount($faqId)
    {
        // Count using polymorphic relationship
        $polymorphicCount = Contador::where('countable_type', 'App\Model\Faq')
            ->where('countable_id', $faqId)
            ->where('action_type', 'view')
            ->count();

        // Count using legacy column (for old data)
        $legacyCount = Contador::where('idFaq', $faqId)
            ->whereNull('countable_type')
            ->count();

        return $polymorphicCount + $legacyCount;
    }
}
