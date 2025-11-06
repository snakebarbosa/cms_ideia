<?php

namespace App\Http\Controllers\Administrator;
use App\Http\Controllers\Controller;
use App\Http\Requests\Administrator\CheckRequest;
use App\Http\Requests\Administrator\DocumentoRequest;
use App\Model\Documento;
use App\Services\DocumentoService;
use Illuminate\Support\Facades\Session;

class DocumentoController extends Controller {
    
    protected $documentoService;

    public function __construct(DocumentoService $documentoService) {
        $this->middleware('auth');
        $this->documentoService = $documentoService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        try {
            $documents = $this->documentoService->getDocuments();
            return view('Administrator.Documentacao.documento', compact('documents'));
        } catch (\Exception $e) {
            Session::flash('error', 'Erro ao carregar documentos: ' . $e->getMessage());
            return view('Administrator.Documentacao.documento', ['documents' => collect()]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        try {
            $formData = $this->documentoService->getFormData();
            return view('Administrator.Documentacao.doc_form', [
                'tag' => $formData['tags'],
                'cat' => $formData['categories']
            ]);
        } catch (\Exception $e) {
            Session::flash('error', 'Erro ao carregar formulário: ' . $e->getMessage());
            return redirect()->route('Documento.index');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DocumentoRequest $request) {
        try {
            $filePT = $request->file('filePT');
            $fileEN = $request->file('fileEN');
            
            if (!$filePT || !$fileEN) {
                Session::flash('warning', 'Ambos os arquivos (PT e EN) são obrigatórios!');
                return redirect()->route('Documento.index');
            }

            $document = $this->documentoService->createDocument(
                $request->all(),
                $filePT,
                $fileEN
            );

            Session::flash('success', 'Documento criado com sucesso!');
            return redirect()->route('Documento.index');
            
        } catch (\Exception $e) {
            Session::flash('warning', 'Erro ao criar documento: ' . $e->getMessage());
            return redirect()->route('Documento.index');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        try {
            $document = Documento::findOrFail($id);
            return view('Administrator.Documentacao.documento_show', compact('document'));
        } catch (\Exception $e) {
            Session::flash('error', 'Documento não encontrado.');
            return redirect()->route('Documento.index');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        try {
            $document = Documento::findOrFail($id);
            $content = $this->documentoService->getDocumentContent($document);
            $formData = $this->documentoService->getFormData();

            return view('Administrator.Documentacao.doc_form', [
                'tag' => $formData['tags'],
                'cat' => $formData['categories'],
                'documento' => $document,
                'content' => $content
            ]);
        } catch (\Exception $e) {
            Session::flash('error', 'Erro ao carregar documento: ' . $e->getMessage());
            return redirect()->route('Documento.index');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(DocumentoRequest $request, $id) {
        try {
            $document = Documento::findOrFail($id);
            
            $filePT = $request->file('filePT');
            $fileEN = $request->file('fileEN');

            $updatedDocument = $this->documentoService->updateDocument(
                $document,
                $request->all(),
                $filePT,
                $fileEN
            );

            Session::flash('success', 'Documento atualizado com sucesso!');
            return redirect()->route('Documento.index');
            
        } catch (\Exception $e) {
            Session::flash('warning', 'Erro ao atualizar documento: ' . $e->getMessage());
            return redirect()->route('Documento.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        try {
            $document = Documento::findOrFail($id);
            $this->documentoService->deleteDocument($document);

            Session::flash('success', 'Documento apagado com sucesso!');
            return redirect()->route('Documento.index');
            
        } catch (\Exception $e) {
            Session::flash('error', 'Erro ao apagar documento: ' . $e->getMessage());
            return redirect()->route('Documento.index');
        }
    }

    /**
     * Bulk operations for publishing, unpublishing, highlighting, etc.
     */
    public function publicarCheck(CheckRequest $request) {
        try {
            $this->documentoService->bulkUpdateStatus($request->check, 'ativado', 1);
            Session::flash('success', 'Documentos publicados com sucesso!');
            return redirect()->route('Documento.index');
        } catch (\Exception $e) {
            Session::flash('error', 'Erro ao publicar documentos: ' . $e->getMessage());
            return redirect()->route('Documento.index');
        }
    }

    public function despublicarCheck(CheckRequest $request) {
        try {
            $this->documentoService->bulkUpdateStatus($request->check, 'ativado', 0);
            Session::flash('success', 'Documentos despublicados com sucesso!');
            return redirect()->route('Documento.index');
        } catch (\Exception $e) {
            Session::flash('error', 'Erro ao despublicar documentos: ' . $e->getMessage());
            return redirect()->route('Documento.index');
        }
    }

    public function destaqueCheck(CheckRequest $request) {
        try {
            $this->documentoService->bulkUpdateStatus($request->check, 'destaque', 1);
            Session::flash('success', 'Documentos destacados com sucesso!');
            return redirect()->route('Documento.index');
        } catch (\Exception $e) {
            Session::flash('error', 'Erro ao destacar documentos: ' . $e->getMessage());
            return redirect()->route('Documento.index');
        }
    }

    public function rdestaqueCheck(CheckRequest $request) {
        try {
            $this->documentoService->bulkUpdateStatus($request->check, 'destaque', 0);
            Session::flash('success', 'Destaques removidos com sucesso!');
            return redirect()->route('Documento.index');
        } catch (\Exception $e) {
            Session::flash('error', 'Erro ao remover destaques: ' . $e->getMessage());
            return redirect()->route('Documento.index');
        }
    }

    public function removerCheck(CheckRequest $request) {
        try {
            $this->documentoService->bulkDeleteDocuments($request->check);
            Session::flash('success', 'Documentos removidos com sucesso!');
            return redirect()->route('Documento.index');
        } catch (\Exception $e) {
            Session::flash('error', 'Erro ao remover documentos: ' . $e->getMessage());
            return redirect()->route('Documento.index');
        }
    }

    /**
     * Individual operations for document status changes
     */
    public function despublicar($id) {
        try {
            $document = Documento::findOrFail($id);
            $this->documentoService->updateDocumentStatus($document, 'ativado', 0);
            
            Session::flash('success', 'Documento despublicado com sucesso!');
            return redirect()->route('Documento.index');
        } catch (\Exception $e) {
            Session::flash('error', 'Erro ao despublicar documento: ' . $e->getMessage());
            return redirect()->route('Documento.index');
        }
    }

    public function publicar($id) {
        try {
            $document = Documento::findOrFail($id);
            $this->documentoService->updateDocumentStatus($document, 'ativado', 1);
            
            Session::flash('success', 'Documento publicado com sucesso!');
            return redirect()->route('Documento.index');
        } catch (\Exception $e) {
            Session::flash('error', 'Erro ao publicar documento: ' . $e->getMessage());
            return redirect()->route('Documento.index');
        }
    }

    public function destacar($id) {
        try {
            $document = Documento::findOrFail($id);
            $this->documentoService->updateDocumentStatus($document, 'destaque', 1);
            
            Session::flash('success', 'Documento destacado com sucesso!');
            return redirect()->route('Documento.index');
        } catch (\Exception $e) {
            Session::flash('error', 'Erro ao destacar documento: ' . $e->getMessage());
            return redirect()->route('Documento.index');
        }
    }

    public function rdestacar($id) {
        try {
            $document = Documento::findOrFail($id);
            $this->documentoService->updateDocumentStatus($document, 'destaque', 0);
            
            Session::flash('success', 'Destaque removido com sucesso!');
            return redirect()->route('Documento.index');
        } catch (\Exception $e) {
            Session::flash('error', 'Erro ao remover destaque: ' . $e->getMessage());
            return redirect()->route('Documento.index');
        }
    }

    /**
     * Export operations for documents
     */
    public function export() {
        try {
            $this->documentoService->exportFeaturedDocuments();
            $this->exportLast();
            
            Session::flash('success', 'Documentos em destaque exportados com sucesso!');
            return redirect()->route('Documento.index');
        } catch (\Exception $e) {
            Session::flash('error', 'Erro ao exportar documentos: ' . $e->getMessage());
            return redirect()->route('Documento.index');
        }
    }

    public function exportLast() {
        try {
            $this->documentoService->exportLatestDocuments();
            $this->exportRandom();
            
            Session::flash('success', 'Últimos documentos exportados com sucesso!');
            return redirect()->route('Documento.index');
        } catch (\Exception $e) {
            Session::flash('error', 'Erro ao exportar últimos documentos: ' . $e->getMessage());
            return redirect()->route('Documento.index');
        }
    }

    public function exportRandom() {
        try {
            $this->documentoService->exportRandomDocuments();
            
            Session::flash('success', 'Documentos aleatórios exportados com sucesso!');
            return redirect()->route('Documento.index');
        } catch (\Exception $e) {
            Session::flash('error', 'Erro ao exportar documentos aleatórios: ' . $e->getMessage());
            return redirect()->route('Documento.index');
        }
    }

    /**
     * Generate slugs for all documents
     */
    public function createSlugAll() {
        try {
            $count = $this->documentoService->createSlugForAllDocuments();
            
            Session::flash('success', "Slugs criados para {$count} documentos!");
            return redirect()->route('Documento.index');
        } catch (\Exception $e) {
            Session::flash('error', 'Erro ao criar slugs: ' . $e->getMessage());
            return redirect()->route('Documento.index');
        }
    }
}
