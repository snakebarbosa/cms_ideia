<?php

namespace App\Services;

use App\Model\Artigo;
use App\Model\Documento;
use App\Model\Faq;
use Illuminate\Support\Str;

class SearchService
{
    /**
     * Search across articles, documents, and FAQs
     */
    public function search($query, $type = 'all')
    {
        $results = [];

        if ($type === 'all' || $type === 'artigo') {
            $results['artigos'] = $this->searchArticles($query);
        }

        if ($type === 'all' || $type === 'documento') {
            $results['documentos'] = $this->searchDocuments($query);
        }

        if ($type === 'all' || $type === 'faq') {
            $results['faqs'] = $this->searchFaqs($query);
        }

        return $results;
    }

    /**
     * Search articles
     */
    protected function searchArticles($query)
    {
        return Artigo::where('ativado', 1)
            ->where('publicar', '<=', now())
            ->where('despublicar', '>=', now())
            ->where(function($q) use ($query) {
                $q->where('alias', 'like', '%' . $query . '%')
                  ->orWhere('keyword', 'like', '%' . $query . '%')
                  ->orWhereHas('conteudos', function($subQ) use ($query) {
                      $subQ->where('titulo', 'like', '%' . $query . '%')
                           ->orWhere('texto', 'like', '%' . $query . '%');
                  })
                  ->orWhereHas('tags', function($subQ) use ($query) {
                      $subQ->where('name', 'like', '%' . $query . '%');
                  });
            })
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Search documents
     */
    protected function searchDocuments($query)
    {
        return Documento::where('ativado', 1)
            ->where('publicar', '<=', now())
            ->where('despublicar', '>=', now())
            ->where(function($q) use ($query) {
                $q->where('nome', 'like', '%' . $query . '%')
                  ->orWhere('alias', 'like', '%' . $query . '%')
                  ->orWhereHas('conteudos', function($subQ) use ($query) {
                      $subQ->where('titulo', 'like', '%' . $query . '%')
                           ->orWhere('texto', 'like', '%' . $query . '%');
                  })
                  ->orWhereHas('tags', function($subQ) use ($query) {
                      $subQ->where('name', 'like', '%' . $query . '%');
                  });
            })
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Search FAQs
     */
    protected function searchFaqs($query)
    {
        return Faq::where('ativado', 1)
            ->where(function($q) use ($query) {
                $q->where('alias', 'like', '%' . $query . '%')
                  ->orWhere('keyword', 'like', '%' . $query . '%')
                  ->orWhereHas('conteudos', function($subQ) use ($query) {
                      $subQ->where('titulo', 'like', '%' . $query . '%')
                           ->orWhere('texto', 'like', '%' . $query . '%');
                  })
                  ->orWhereHas('tags', function($subQ) use ($query) {
                      $subQ->where('name', 'like', '%' . $query . '%');
                  });
            })
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get search suggestions
     */
    public function getSuggestions($query, $limit = 5)
    {
        $suggestions = [];

        // Get article titles
        $articles = Artigo::where('ativado', 1)
            ->where('alias', 'like', '%' . $query . '%')
            ->limit($limit)
            ->pluck('alias')
            ->toArray();

        // Get document titles
        $documents = Documento::where('ativado', 1)
            ->where('nome', 'like', '%' . $query . '%')
            ->limit($limit)
            ->pluck('nome')
            ->toArray();

        $suggestions = array_merge($articles, $documents);
        $suggestions = array_unique($suggestions);
        $suggestions = array_slice($suggestions, 0, $limit);

        return $suggestions;
    }

    /**
     * Get total search results count
     */
    public function getResultsCount($query)
    {
        $results = $this->search($query);
        
        return [
            'artigos' => $results['artigos']->count(),
            'documentos' => $results['documentos']->count(),
            'faqs' => $results['faqs']->count(),
            'total' => $results['artigos']->count() + 
                      $results['documentos']->count() + 
                      $results['faqs']->count(),
        ];
    }
}
