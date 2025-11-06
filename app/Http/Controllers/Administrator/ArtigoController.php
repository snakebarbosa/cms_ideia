<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Http\Requests\Administrator\ArtigoRequest;
use App\Http\Requests\Administrator\CheckRequest;
use App\Model\Artigo;
use App\Model\Faq;
use App\Services\ArtigoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ArtigoController extends Controller 
{
    private $artigoService;

    public function __construct(ArtigoService $artigoService) 
    {
        $this->middleware('auth');
        $this->artigoService = $artigoService;
    }
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) 
    {
        try {
            $filters = $this->getFiltersFromRequest($request);
            $articles = $this->artigoService->getArticles($filters, false); // false for no pagination in current implementation
            
            return view('Administrator.Artigos.artigo', compact('articles'))
                ->with('art', $articles); // Keep original variable name for backward compatibility
        } catch (\Exception $e) {
            Session::flash('error', 'Erro ao carregar artigos: ' . $e->getMessage());
            return view('Administrator.Artigos.artigo')->with('art', collect());
        }
    }

    /**
     * Extract filters from request
     */
    private function getFiltersFromRequest(Request $request): array
    {
        $filters = [];
        
        if ($request->has('status')) {
            $filters['status'] = $request->input('status');
        }
        
        if ($request->has('destacado')) {
            $filters['destacado'] = $request->input('destacado');
        }
        
        if ($request->has('categoria')) {
            $filters['categoria'] = $request->input('categoria');
        }
        
        return $filters;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() 
    {
        try {
            $formData = $this->artigoService->getFormData();
            
            return view('Administrator.Artigos.artigo_form', [
                'doc' => $formData['documents'],
                'tag' => $formData['tags'],
                'cat' => $formData['categories'],
                'catimg' => $formData['imageCategories'],
                'tree' => $formData['categoryTree']
            ]);
        } catch (\Exception $e) {
            Session::flash('error', 'Erro ao carregar formulário: ' . $e->getMessage());
            return redirect()->route('Artigo.index');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ArtigoRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(ArtigoRequest $request) 
    {
        try {
            $article = $this->artigoService->createArticle($request->all());
            
            Session::flash('success', 'Artigo criado com sucesso!');
            return redirect()->route('Artigo.index', $article->id);
        } catch (\Exception $e) {
            Session::flash('error', 'Erro ao criar artigo: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) 
    {
        // Placeholder method - no implementation needed currently
        return redirect()->route('Artigo.index');
    }

    /**
     * Unpublish article
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function despublicar($id) 
    {
        return $this->updateArticleStatus($id, 'ativado', ArtigoService::STATUS_INACTIVE, 'Artigo despublicado!');
    }

    /**
     * Publish article
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function publicar($id) 
    {
        return $this->updateArticleStatus($id, 'ativado', ArtigoService::STATUS_ACTIVE, 'Artigo publicado!');
    }

    /**
     * Highlight article
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destacar($id) 
    {
        return $this->updateArticleStatus($id, 'destaque', ArtigoService::HIGHLIGHTED, 'Artigo destacado!');
    }

    /**
     * Remove article highlight
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function rdestacar($id) 
    {
        return $this->updateArticleStatus($id, 'destaque', ArtigoService::NOT_HIGHLIGHTED, 'Destaque removido!');
    }

    /**
     * Generic method to update article status
     *
     * @param int $id
     * @param string $field
     * @param int $value
     * @param string $message
     * @return \Illuminate\Http\Response
     */
    private function updateArticleStatus($id, $field, $value, $message) 
    {
        try {
            $this->artigoService->updateStatus($id, $field, $value);
            Session::flash('success', $message);
        } catch (\Exception $e) {
            Session::flash('error', 'Erro ao atualizar artigo: ' . $e->getMessage());
        }
        
        return redirect()->route('Artigo.index', $id);
    }

    /**
     * Bulk publish articles
     *
     * @param CheckRequest $request
     * @return \Illuminate\Http\Response
     */
    public function publicarCheck(CheckRequest $request) 
    {
        return $this->bulkUpdateArticles($request->check, ['ativado' => ArtigoService::STATUS_ACTIVE], 'Artigos publicados!');
    }

    /**
     * Bulk unpublish articles
     *
     * @param CheckRequest $request
     * @return \Illuminate\Http\Response
     */
    public function despublicarCheck(CheckRequest $request) 
    {
        return $this->bulkUpdateArticles($request->check, ['ativado' => ArtigoService::STATUS_INACTIVE], 'Artigos despublicados!');
    }

    /**
     * Bulk highlight articles
     *
     * @param CheckRequest $request
     * @return \Illuminate\Http\Response
     */
    public function destaqueCheck(CheckRequest $request) 
    {
        return $this->bulkUpdateArticles($request->check, ['destaque' => ArtigoService::HIGHLIGHTED], 'Artigos destacados!');
    }

    /**
     * Bulk remove highlight from articles
     *
     * @param CheckRequest $request
     * @return \Illuminate\Http\Response
     */
    public function rdestaqueCheck(CheckRequest $request) 
    {
        return $this->bulkUpdateArticles($request->check, ['destaque' => ArtigoService::NOT_HIGHLIGHTED], 'Destaques removidos!');
    }

    /**
     * Bulk delete articles
     *
     * @param CheckRequest $request
     * @return \Illuminate\Http\Response
     */
    public function removerCheck(CheckRequest $request) 
    {
        try {
            $this->artigoService->bulkDelete($request->check);
            Session::flash('success', 'Artigos removidos com sucesso!');
        } catch (\Exception $e) {
            Session::flash('error', 'Erro ao remover artigos: ' . $e->getMessage());
        }
        
        return redirect()->route('Artigo.index');
    }

    /**
     * Generic method for bulk operations
     *
     * @param array $articleIds
     * @param array $updates
     * @param string $message
     * @return \Illuminate\Http\Response
     */
    private function bulkUpdateArticles($articleIds, $updates, $message) 
    {
        try {
            $this->artigoService->bulkUpdate($articleIds, $updates);
            Session::flash('success', $message);
        } catch (\Exception $e) {
            Session::flash('error', 'Erro na operação: ' . $e->getMessage());
        }
        
        return redirect()->route('Artigo.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) 
    {
        try {
            $article = Artigo::with('anexos')->findOrFail($id);
            $formData = $this->artigoService->getFormData();
            $content = $this->prepareContentForEdit($article);
            $attachedDocuments = $this->getAttachedDocuments($article);
            $attachedDocumentIds = $this->getAttachedDocumentIds($article);

            return view('Administrator.Artigos.artigo_form', [
                'artigo' => $article,
                'content' => $content,
                'documento' => $attachedDocuments,
                'documentoIds' => $attachedDocumentIds,
                'doc' => $formData['documents'],
                'tag' => $formData['tags'],
                'cat' => $formData['categories'],
                'catimg' => $formData['imageCategories'],
                'tree' => $formData['categoryTree']
            ]);
        } catch (\Exception $e) {
            Session::flash('error', 'Erro ao carregar artigo: ' . $e->getMessage());
            return redirect()->route('Artigo.index');
        }
    }

    /**
     * Prepare content data for editing
     */
    private function prepareContentForEdit(Artigo $article): array 
    {
        $content = [];
        $articleContent = $article->conteudos;

        if ($articleContent->count() > 0) {
            foreach ($articleContent as $item) {
                if (isset($item->languages->tag)) {
                    if ($item->languages->tag === "pt") {
                        $content['conteudoPT'] = $item->texto;
                        $content['tituloPT'] = $item->titulo;
                    } else {
                        $content['conteudoEN'] = $item->texto;
                        $content['tituloEN'] = $item->titulo;
                    }
                }
            }
        } else {
            // Fallback to article data if no content exists
            $content = [
                'conteudoPT' => $article->descricao ?? '',
                'tituloPT' => $article->alias ?? '',
                'conteudoEN' => $article->descricao ?? '',
                'tituloEN' => $article->alias ?? ''
            ];
        }

        return $content;
    }

    /**
     * Get attached documents as comma-separated string of titles
     */
    private function getAttachedDocuments(Artigo $article): string 
    {
        return $article->anexos->map(function($doc) {
            // Try to get title from different possible fields
            return $doc->alias ?? $doc->nome ?? 'Documento sem título';
        })->implode(', ');
    }

    /**
     * Get attached document IDs as comma-separated string
     */
    private function getAttachedDocumentIds(Artigo $article): string 
    {
        return $article->anexos->pluck('id')->implode(',');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ArtigoRequest $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(ArtigoRequest $request, $id) 
    {
        try {
            $article = Artigo::findOrFail($id);
            $this->artigoService->updateArticle($article, $request->all());
            
            Session::flash('success', 'Artigo atualizado com sucesso!');
            return redirect()->route('Artigo.index', $article->id);
        } catch (\Exception $e) {
            Session::flash('error', 'Erro ao atualizar artigo: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) 
    {
        try {
            $this->artigoService->deleteArticle($id);
            Session::flash('success', 'Artigo removido com sucesso!');
        } catch (\Exception $e) {
            Session::flash('error', 'Erro ao remover artigo: ' . $e->getMessage());
        }
        
        return redirect()->route('Artigo.index');
    }

    /**
     * Export highlighted articles to JSON
     *
     * @return \Illuminate\Http\Response
     */
    public function export() 
    {
        try {
            $this->artigoService->exportToJson();
            Session::flash('success', 'Artigos em destaque exportados com sucesso!');
        } catch (\Exception $e) {
            Session::flash('error', 'Erro ao exportar artigos: ' . $e->getMessage());
        }
        
        return redirect()->route('Artigo.index');
    }

    /**
     * Search articles
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request) 
    {
        $request->validate([
            'search' => 'required|min:2|max:255',
        ]);

        try {
            $articles = Artigo::search($request->search)->get();
            return view('Administrator.search', compact('articles'))
                ->with('art', $articles); // Keep original variable name for backward compatibility
        } catch (\Exception $e) {
            Session::flash('error', 'Erro na pesquisa: ' . $e->getMessage());
            return view('Administrator.search')->with('art', collect());
        }
    }

    /**
     * Create slugs for all FAQ entries (development/debugging method)
     *
     * @return void
     */
    public function createSlugAll()
    {
        try {
            $faqs = Faq::orderBy('created_at', 'DESC')->get();
            
            foreach ($faqs as $faq) {
                $slug = Artigo::createSlug($faq->alias, $faq->id, new Faq);
                $faq->slug = $slug;
                $faq->save();
            }
            
            dd('Slugs criados com sucesso para ' . $faqs->count() . ' FAQs');
        } catch (\Exception $e) {
            dd('Erro ao criar slugs: ' . $e->getMessage());
        }
    }
}
