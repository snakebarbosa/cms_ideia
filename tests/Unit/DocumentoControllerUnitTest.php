<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Controllers\Administrator\DocumentoController;
use App\Http\Requests\Administrator\DocumentoRequest;
use App\Http\Requests\Administrator\CheckRequest;
use App\Services\DocumentoService;
use App\Model\Documento;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Session;
use Mockery;

class DocumentoControllerUnitTest extends TestCase
{
    protected $documentoService;
    protected $controller;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->documentoService = Mockery::mock(DocumentoService::class);
        $this->controller = new DocumentoController($this->documentoService);
        $this->user = factory(User::class)->make(['id' => 1]);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function index_returns_view_with_documents()
    {
        $documents = collect([
            factory(Documento::class)->make(),
            factory(Documento::class)->make()
        ]);

        $this->documentoService
            ->shouldReceive('getDocuments')
            ->once()
            ->andReturn($documents);

        $response = $this->controller->index();

        $this->assertInstanceOf(\Illuminate\View\View::class, $response);
        $this->assertEquals('Administrator.Documentacao.documento', $response->getName());
        $this->assertEquals($documents, $response->getData()['documents']);
    }

    /** @test */
    public function index_handles_exception_gracefully()
    {
        $this->documentoService
            ->shouldReceive('getDocuments')
            ->once()
            ->andThrow(new \Exception('Service error'));

        Session::shouldReceive('flash')
            ->once()
            ->with('error', 'Erro ao carregar documentos: Service error');

        $response = $this->controller->index();

        $this->assertInstanceOf(\Illuminate\View\View::class, $response);
        $this->assertTrue($response->getData()['documents']->isEmpty());
    }

    /** @test */
    public function create_returns_view_with_form_data()
    {
        $formData = [
            'tags' => collect(['tag1', 'tag2']),
            'categories' => collect(['cat1', 'cat2'])
        ];

        $this->documentoService
            ->shouldReceive('getFormData')
            ->once()
            ->andReturn($formData);

        $response = $this->controller->create();

        $this->assertInstanceOf(\Illuminate\View\View::class, $response);
        $this->assertEquals('Administrator.Documentacao.doc_form', $response->getName());
        $this->assertEquals($formData['tags'], $response->getData()['tag']);
        $this->assertEquals($formData['categories'], $response->getData()['cat']);
    }

    /** @test */
    public function create_handles_exception_and_redirects()
    {
        $this->documentoService
            ->shouldReceive('getFormData')
            ->once()
            ->andThrow(new \Exception('Service error'));

        Session::shouldReceive('flash')
            ->once()
            ->with('error', 'Erro ao carregar formulário: Service error');

        $response = $this->controller->create();

        $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $response);
    }

    /** @test */
    public function store_creates_document_successfully()
    {
        $filePT = UploadedFile::fake()->create('document_pt.pdf');
        $fileEN = UploadedFile::fake()->create('document_en.pdf');
        
        $request = Mockery::mock(DocumentoRequest::class);
        $request->shouldReceive('file')
            ->with('filePT')
            ->andReturn($filePT);
        $request->shouldReceive('file')
            ->with('fileEN')
            ->andReturn($fileEN);
        $request->shouldReceive('all')
            ->andReturn(['tituloPT' => 'Test Document']);

        $document = factory(Documento::class)->make();

        $this->documentoService
            ->shouldReceive('createDocument')
            ->once()
            ->with(['tituloPT' => 'Test Document'], $filePT, $fileEN)
            ->andReturn($document);

        Session::shouldReceive('flash')
            ->once()
            ->with('success', 'Documento criado com sucesso!');

        $response = $this->controller->store($request);

        $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $response);
    }

    /** @test */
    public function store_handles_missing_files()
    {
        $request = Mockery::mock(DocumentoRequest::class);
        $request->shouldReceive('file')
            ->with('filePT')
            ->andReturn(null);
        $request->shouldReceive('file')
            ->with('fileEN')
            ->andReturn(null);

        Session::shouldReceive('flash')
            ->once()
            ->with('warning', 'Ambos os arquivos (PT e EN) são obrigatórios!');

        $response = $this->controller->store($request);

        $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $response);
    }

    /** @test */
    public function store_handles_service_exception()
    {
        $filePT = UploadedFile::fake()->create('document_pt.pdf');
        $fileEN = UploadedFile::fake()->create('document_en.pdf');
        
        $request = Mockery::mock(DocumentoRequest::class);
        $request->shouldReceive('file')
            ->with('filePT')
            ->andReturn($filePT);
        $request->shouldReceive('file')
            ->with('fileEN')
            ->andReturn($fileEN);
        $request->shouldReceive('all')
            ->andReturn(['tituloPT' => 'Test Document']);

        $this->documentoService
            ->shouldReceive('createDocument')
            ->once()
            ->andThrow(new \Exception('Service error'));

        Session::shouldReceive('flash')
            ->once()
            ->with('warning', 'Erro ao criar documento: Service error');

        $response = $this->controller->store($request);

        $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $response);
    }

    /** @test */
    public function edit_returns_view_with_document_data()
    {
        $document = factory(Documento::class)->make(['id' => 1]);
        $content = ['conteudoPT' => 'Content PT', 'conteudoEN' => 'Content EN'];
        $formData = [
            'tags' => collect(['tag1']),
            'categories' => collect(['cat1'])
        ];

        Documento::shouldReceive('findOrFail')
            ->with(1)
            ->andReturn($document);

        $this->documentoService
            ->shouldReceive('getDocumentContent')
            ->with($document)
            ->andReturn($content);

        $this->documentoService
            ->shouldReceive('getFormData')
            ->andReturn($formData);

        $response = $this->controller->edit(1);

        $this->assertInstanceOf(\Illuminate\View\View::class, $response);
        $this->assertEquals('Administrator.Documentacao.doc_form', $response->getName());
        $this->assertEquals($document, $response->getData()['documento']);
        $this->assertEquals($content, $response->getData()['content']);
    }

    /** @test */
    public function update_updates_document_successfully()
    {
        $document = factory(Documento::class)->make(['id' => 1]);
        $updatedDocument = factory(Documento::class)->make(['id' => 1]);
        
        $request = Mockery::mock(DocumentoRequest::class);
        $request->shouldReceive('file')
            ->with('filePT')
            ->andReturn(null);
        $request->shouldReceive('file')
            ->with('fileEN')
            ->andReturn(null);
        $request->shouldReceive('all')
            ->andReturn(['tituloPT' => 'Updated Document']);

        Documento::shouldReceive('findOrFail')
            ->with(1)
            ->andReturn($document);

        $this->documentoService
            ->shouldReceive('updateDocument')
            ->once()
            ->with($document, ['tituloPT' => 'Updated Document'], null, null)
            ->andReturn($updatedDocument);

        Session::shouldReceive('flash')
            ->once()
            ->with('success', 'Documento atualizado com sucesso!');

        $response = $this->controller->update($request, 1);

        $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $response);
    }

    /** @test */
    public function destroy_deletes_document_successfully()
    {
        $document = factory(Documento::class)->make(['id' => 1]);

        Documento::shouldReceive('findOrFail')
            ->with(1)
            ->andReturn($document);

        $this->documentoService
            ->shouldReceive('deleteDocument')
            ->once()
            ->with($document);

        Session::shouldReceive('flash')
            ->once()
            ->with('success', 'Documento apagado com sucesso!');

        $response = $this->controller->destroy(1);

        $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $response);
    }

    /** @test */
    public function publicarCheck_bulk_publishes_documents()
    {
        $request = Mockery::mock(CheckRequest::class);
        $request->check = [1, 2, 3];

        $this->documentoService
            ->shouldReceive('bulkUpdateStatus')
            ->once()
            ->with([1, 2, 3], 'ativado', 1);

        Session::shouldReceive('flash')
            ->once()
            ->with('success', 'Documentos publicados com sucesso!');

        $response = $this->controller->publicarCheck($request);

        $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $response);
    }

    /** @test */
    public function despublicarCheck_bulk_unpublishes_documents()
    {
        $request = Mockery::mock(CheckRequest::class);
        $request->check = [1, 2, 3];

        $this->documentoService
            ->shouldReceive('bulkUpdateStatus')
            ->once()
            ->with([1, 2, 3], 'ativado', 0);

        Session::shouldReceive('flash')
            ->once()
            ->with('success', 'Documentos despublicados com sucesso!');

        $response = $this->controller->despublicarCheck($request);

        $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $response);
    }

    /** @test */
    public function removerCheck_bulk_deletes_documents()
    {
        $request = Mockery::mock(CheckRequest::class);
        $request->check = [1, 2, 3];

        $this->documentoService
            ->shouldReceive('bulkDeleteDocuments')
            ->once()
            ->with([1, 2, 3]);

        Session::shouldReceive('flash')
            ->once()
            ->with('success', 'Documentos removidos com sucesso!');

        $response = $this->controller->removerCheck($request);

        $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $response);
    }

    /** @test */
    public function publicar_publishes_single_document()
    {
        $document = factory(Documento::class)->make(['id' => 1]);

        Documento::shouldReceive('findOrFail')
            ->with(1)
            ->andReturn($document);

        $this->documentoService
            ->shouldReceive('updateDocumentStatus')
            ->once()
            ->with($document, 'ativado', 1);

        Session::shouldReceive('flash')
            ->once()
            ->with('success', 'Documento publicado com sucesso!');

        $response = $this->controller->publicar(1);

        $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $response);
    }

    /** @test */
    public function export_exports_featured_documents()
    {
        $this->documentoService
            ->shouldReceive('exportFeaturedDocuments')
            ->once();

        $this->documentoService
            ->shouldReceive('exportLatestDocuments')
            ->once();

        $this->documentoService
            ->shouldReceive('exportRandomDocuments')
            ->once();

        Session::shouldReceive('flash')
            ->once()
            ->with('success', 'Documentos em destaque exportados com sucesso!');

        $response = $this->controller->export();

        $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $response);
    }

    /** @test */
    public function createSlugAll_creates_slugs_for_all_documents()
    {
        $this->documentoService
            ->shouldReceive('createSlugForAllDocuments')
            ->once()
            ->andReturn(5);

        Session::shouldReceive('flash')
            ->once()
            ->with('success', 'Slugs criados para 5 documentos!');

        $response = $this->controller->createSlugAll();

        $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $response);
    }
}