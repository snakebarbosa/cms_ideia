<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\User;
use App\Model\Documento;
use App\Model\Categoria;
use App\Model\Tag;
use App\Services\DocumentoService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class DocumentoServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected $documentoService;
    protected $user;
    protected $categoria;
    protected $tag;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->documentoService = new DocumentoService();
        $this->user = factory(User::class)->create();
        $this->categoria = factory(Categoria::class)->create([
            'documento' => 1,
            'ativado' => 1
        ]);
        $this->tag = factory(Tag::class)->create();
        
        Auth::login($this->user);
    }

    /** @test */
    public function it_gets_documents_with_default_filters()
    {
        $documento1 = factory(Documento::class)->create([
            'ativado' => DocumentoService::STATUS_ACTIVE
        ]);
        
        $documento2 = factory(Documento::class)->create([
            'ativado' => DocumentoService::STATUS_DELETED
        ]);

        $documents = $this->documentoService->getDocuments([], false);

        $this->assertTrue($documents->contains($documento1));
        $this->assertFalse($documents->contains($documento2));
    }

    /** @test */
    public function it_gets_documents_with_status_filter()
    {
        $activeDocument = factory(Documento::class)->create([
            'ativado' => DocumentoService::STATUS_ACTIVE
        ]);
        
        $inactiveDocument = factory(Documento::class)->create([
            'ativado' => DocumentoService::STATUS_INACTIVE
        ]);

        $activeDocuments = $this->documentoService->getDocuments(['status' => DocumentoService::STATUS_ACTIVE], false);
        $inactiveDocuments = $this->documentoService->getDocuments(['status' => DocumentoService::STATUS_INACTIVE], false);

        $this->assertTrue($activeDocuments->contains($activeDocument));
        $this->assertFalse($activeDocuments->contains($inactiveDocument));
        
        $this->assertTrue($inactiveDocuments->contains($inactiveDocument));
        $this->assertFalse($inactiveDocuments->contains($activeDocument));
    }

    /** @test */
    public function it_gets_documents_with_highlight_filter()
    {
        $highlightedDocument = factory(Documento::class)->create([
            'destaque' => DocumentoService::HIGHLIGHTED
        ]);
        
        $normalDocument = factory(Documento::class)->create([
            'destaque' => DocumentoService::NOT_HIGHLIGHTED
        ]);

        $highlightedDocuments = $this->documentoService->getDocuments(['destacado' => DocumentoService::HIGHLIGHTED], false);

        $this->assertTrue($highlightedDocuments->contains($highlightedDocument));
        $this->assertFalse($highlightedDocuments->contains($normalDocument));
    }

    /** @test */
    public function it_gets_form_data()
    {
        $formData = $this->documentoService->getFormData();

        $this->assertArrayHasKey('tags', $formData);
        $this->assertArrayHasKey('categories', $formData);
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $formData['tags']);
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $formData['categories']);
    }

    /** @test */
    public function it_creates_document_successfully()
    {
        Storage::fake('local');
        
        $filePT = UploadedFile::fake()->create('document_pt.pdf', 100);
        $fileEN = UploadedFile::fake()->create('document_en.pdf', 100);
        
        $data = [
            'tituloPT' => 'Test Document PT',
            'tituloEN' => 'Test Document EN',
            'textopt' => 'Portuguese content',
            'textoen' => 'English content',
            'idCategoria' => $this->categoria->id,
            'ativado' => 'on',
            'destaque' => 'on',
            'publicar' => now()->format('Y-m-d'),
            'data_criacao' => now()->format('Y-m-d'),
            'tag' => [$this->tag->id]
        ];

        $document = $this->documentoService->createDocument($data, $filePT, $fileEN);

        $this->assertInstanceOf(Documento::class, $document);
        $this->assertEquals('Test Document PT', $document->nome);
        $this->assertEquals(DocumentoService::STATUS_ACTIVE, $document->ativado);
        $this->assertEquals(DocumentoService::HIGHLIGHTED, $document->destaque);
        $this->assertNotNull($document->url);
        
        $urlData = json_decode($document->url, true);
        $this->assertArrayHasKey('pt', $urlData);
        $this->assertArrayHasKey('en', $urlData);
    }

    /** @test */
    public function it_throws_exception_when_files_missing()
    {
        $data = [
            'tituloPT' => 'Test Document',
            'textopt' => 'Content',
            'idCategoria' => $this->categoria->id,
            'ativado' => 'on',
            'publicar' => now()->format('Y-m-d'),
            'data_criacao' => now()->format('Y-m-d')
        ];

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Ambos os arquivos (PT e EN) são obrigatórios.');

        $this->documentoService->createDocument($data, null, null);
    }

    /** @test */
    public function it_updates_document_successfully()
    {
        $document = factory(Documento::class)->create([
            'nome' => 'Original Name',
            'url' => json_encode(['pt' => 'old_pt.pdf', 'en' => 'old_en.pdf'])
        ]);

        $data = [
            'tituloPT' => 'Updated Name',
            'textopt' => 'Updated content',
            'idCategoria' => $this->categoria->id,
            'ativado' => 'on',
            'publicar' => now()->format('Y-m-d'),
            'data_criacao' => now()->format('Y-m-d')
        ];

        $updatedDocument = $this->documentoService->updateDocument($document, $data);

        $this->assertEquals('Updated Name', $updatedDocument->nome);
        $this->assertEquals(DocumentoService::STATUS_ACTIVE, $updatedDocument->ativado);
    }

    /** @test */
    public function it_deletes_document_successfully()
    {
        $document = factory(Documento::class)->create([
            'url' => json_encode(['pt' => 'test_pt.pdf', 'en' => 'test_en.pdf'])
        ]);

        $result = $this->documentoService->deleteDocument($document);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('documentos', ['id' => $document->id]);
    }

    /** @test */
    public function it_bulk_updates_status()
    {
        $document1 = factory(Documento::class)->create(['ativado' => DocumentoService::STATUS_INACTIVE]);
        $document2 = factory(Documento::class)->create(['ativado' => DocumentoService::STATUS_INACTIVE]);
        
        $ids = [$document1->id, $document2->id];
        
        $result = $this->documentoService->bulkUpdateStatus($ids, 'ativado', DocumentoService::STATUS_ACTIVE);

        $this->assertGreaterThan(0, $result);
        
        $document1->refresh();
        $document2->refresh();
        
        $this->assertEquals(DocumentoService::STATUS_ACTIVE, $document1->ativado);
        $this->assertEquals(DocumentoService::STATUS_ACTIVE, $document2->ativado);
    }

    /** @test */
    public function it_bulk_deletes_documents()
    {
        $document1 = factory(Documento::class)->create([
            'url' => json_encode(['pt' => 'test1_pt.pdf', 'en' => 'test1_en.pdf'])
        ]);
        $document2 = factory(Documento::class)->create([
            'url' => json_encode(['pt' => 'test2_pt.pdf', 'en' => 'test2_en.pdf'])
        ]);
        
        $ids = [$document1->id, $document2->id];
        
        $result = $this->documentoService->bulkDeleteDocuments($ids);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('documentos', ['id' => $document1->id]);
        $this->assertDatabaseMissing('documentos', ['id' => $document2->id]);
    }

    /** @test */
    public function it_updates_document_status()
    {
        $document = factory(Documento::class)->create(['ativado' => DocumentoService::STATUS_INACTIVE]);

        $updatedDocument = $this->documentoService->updateDocumentStatus($document, 'ativado', DocumentoService::STATUS_ACTIVE);

        $this->assertEquals(DocumentoService::STATUS_ACTIVE, $updatedDocument->ativado);
    }

    /** @test */
    public function it_exports_featured_documents()
    {
        Storage::fake('local');
        
        factory(Documento::class)->create([
            'destaque' => DocumentoService::HIGHLIGHTED,
            'ativado' => DocumentoService::STATUS_ACTIVE
        ]);

        $documents = $this->documentoService->exportFeaturedDocuments();

        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $documents);
        Storage::disk('local')->assertExists('json/documento.json');
    }

    /** @test */
    public function it_exports_latest_documents()
    {
        Storage::fake('local');
        
        factory(Documento::class)->create([
            'ativado' => DocumentoService::STATUS_ACTIVE
        ]);

        $documents = $this->documentoService->exportLatestDocuments();

        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $documents);
        Storage::disk('local')->assertExists('json/documentoLast.json');
    }

    /** @test */
    public function it_exports_random_documents()
    {
        Storage::fake('local');
        
        factory(Documento::class)->create([
            'ativado' => DocumentoService::STATUS_ACTIVE,
            'idCategoria' => $this->categoria->id
        ]);

        $documents = $this->documentoService->exportRandomDocuments();

        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $documents);
        Storage::disk('local')->assertExists('json/documentoRandom.json');
    }

    /** @test */
    public function it_creates_slugs_for_all_documents()
    {
        $document1 = factory(Documento::class)->create(['slug' => null]);
        $document2 = factory(Documento::class)->create(['slug' => null]);

        $count = $this->documentoService->createSlugForAllDocuments();

        $this->assertEquals(2, $count);
        
        $document1->refresh();
        $document2->refresh();
        
        $this->assertNotNull($document1->slug);
        $this->assertNotNull($document2->slug);
    }

    /** @test */
    public function it_gets_document_content_from_relationships()
    {
        $document = factory(Documento::class)->create([
            'descricao' => 'Fallback description',
            'alias' => 'Fallback alias'
        ]);

        $content = $this->documentoService->getDocumentContent($document);

        $this->assertArrayHasKey('conteudoPT', $content);
        $this->assertArrayHasKey('tituloPT', $content);
        $this->assertArrayHasKey('conteudoEN', $content);
        $this->assertArrayHasKey('tituloEN', $content);
        
        // Should fallback to document properties when no content relationships exist
        $this->assertEquals('Fallback description', $content['conteudoPT']);
        $this->assertEquals('Fallback alias', $content['tituloPT']);
    }

    /** @test */
    public function it_handles_inactive_status_correctly()
    {
        $data = [
            'tituloPT' => 'Test Document',
            'textopt' => 'Content',
            'idCategoria' => $this->categoria->id,
            'publicar' => now()->format('Y-m-d'),
            'data_criacao' => now()->format('Y-m-d')
            // Note: 'ativado' not set, should default to inactive
        ];

        Storage::fake('local');
        $filePT = UploadedFile::fake()->create('document_pt.pdf', 100);
        $fileEN = UploadedFile::fake()->create('document_en.pdf', 100);

        $document = $this->documentoService->createDocument($data, $filePT, $fileEN);

        $this->assertEquals(DocumentoService::STATUS_INACTIVE, $document->ativado);
        $this->assertEquals(DocumentoService::NOT_HIGHLIGHTED, $document->destaque);
    }
}