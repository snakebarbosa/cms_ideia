<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;
use App\Model\Documento;
use App\Model\Categoria;
use App\Model\Tag;
use App\Services\DocumentoService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class DocumentoControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;
    protected $categoria;
    protected $tag;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = factory(User::class)->create();
        $this->categoria = factory(Categoria::class)->create([
            'documento' => 1,
            'ativado' => 1
        ]);
        $this->tag = factory(Tag::class)->create();
    }

    /** @test */
    public function it_displays_documents_index_page()
    {
        $this->actingAs($this->user);
        
        $documento = factory(Documento::class)->create([
            'idUser' => $this->user->id,
            'idCategoria' => $this->categoria->id
        ]);
        
        $response = $this->get(route('Documento.index'));
        
        $response->assertStatus(200);
        $response->assertViewIs('Administrator.Documentacao.documento');
        $response->assertViewHas('documents');
    }

    /** @test */
    public function it_shows_create_form()
    {
        $this->actingAs($this->user);
        
        $response = $this->get(route('Documento.create'));
        
        $response->assertStatus(200);
        $response->assertViewIs('Administrator.Documentacao.doc_form');
        $response->assertViewHas(['tag', 'cat']);
    }

    /** @test */
    public function it_can_store_new_document()
    {
        $this->actingAs($this->user);
        
        Storage::fake('local');
        
        $filePT = UploadedFile::fake()->create('document_pt.pdf', 100);
        $fileEN = UploadedFile::fake()->create('document_en.pdf', 100);
        
        $data = [
            'tituloPT' => 'Documento Teste PT',
            'tituloEN' => 'Test Document EN',
            'textopt' => 'Conteúdo do documento em português',
            'textoen' => 'Document content in English',
            'idCategoria' => $this->categoria->id,
            'ativado' => 'on',
            'destaque' => 'on',
            'publicar' => now()->format('Y-m-d'),
            'data_criacao' => now()->format('Y-m-d'),
            'despublicar' => now()->addYear()->format('Y-m-d'),
            'tag' => [$this->tag->id],
            'filePT' => $filePT,
            'fileEN' => $fileEN
        ];
        
        $response = $this->post(route('Documento.store'), $data);
        
        $response->assertRedirect(route('Documento.index'));
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('documentos', [
            'nome' => 'Documento Teste PT',
            'ativado' => 1,
            'destaque' => 1,
            'idCategoria' => $this->categoria->id
        ]);
    }

    /** @test */
    public function it_requires_both_files_for_document_creation()
    {
        $this->actingAs($this->user);
        
        $data = [
            'tituloPT' => 'Documento Teste PT',
            'textopt' => 'Conteúdo do documento',
            'idCategoria' => $this->categoria->id,
            'ativado' => 'on',
            'publicar' => now()->format('Y-m-d'),
            'data_criacao' => now()->format('Y-m-d')
        ];
        
        $response = $this->post(route('Documento.store'), $data);
        
        $response->assertRedirect(route('Documento.index'));
        $response->assertSessionHas('warning');
    }

    /** @test */
    public function it_shows_edit_form()
    {
        $this->actingAs($this->user);
        
        $documento = factory(Documento::class)->create([
            'idUser' => $this->user->id,
            'idCategoria' => $this->categoria->id
        ]);
        
        $response = $this->get(route('Documento.edit', $documento->id));
        
        $response->assertStatus(200);
        $response->assertViewIs('Administrator.Documentacao.doc_form');
        $response->assertViewHas(['tag', 'cat', 'documento', 'content']);
    }

    /** @test */
    public function it_can_update_document()
    {
        $this->actingAs($this->user);
        
        $documento = factory(Documento::class)->create([
            'idUser' => $this->user->id,
            'idCategoria' => $this->categoria->id,
            'nome' => 'Documento Original'
        ]);
        
        $data = [
            'tituloPT' => 'Documento Atualizado',
            'tituloEN' => 'Updated Document',
            'textopt' => 'Conteúdo atualizado',
            'textoen' => 'Updated content',
            'idCategoria' => $this->categoria->id,
            'ativado' => 'on',
            'publicar' => now()->format('Y-m-d'),
            'data_criacao' => now()->format('Y-m-d')
        ];
        
        $response = $this->put(route('Documento.update', $documento->id), $data);
        
        $response->assertRedirect(route('Documento.index'));
        $response->assertSessionHas('success');
        
        $documento->refresh();
        $this->assertEquals('Documento Atualizado', $documento->nome);
    }

    /** @test */
    public function it_can_delete_document()
    {
        $this->actingAs($this->user);
        
        $documento = factory(Documento::class)->create([
            'idUser' => $this->user->id,
            'idCategoria' => $this->categoria->id
        ]);
        
        $response = $this->delete(route('Documento.destroy', $documento->id));
        
        $response->assertRedirect(route('Documento.index'));
        $response->assertSessionHas('success');
        
        $this->assertDatabaseMissing('documentos', [
            'id' => $documento->id
        ]);
    }

    /** @test */
    public function it_can_publish_document()
    {
        $this->actingAs($this->user);
        
        $documento = factory(Documento::class)->create([
            'idUser' => $this->user->id,
            'idCategoria' => $this->categoria->id,
            'ativado' => 0
        ]);
        
        $response = $this->post(route('Documento.publicar', $documento->id));
        
        $response->assertRedirect(route('Documento.index'));
        $response->assertSessionHas('success');
        
        $documento->refresh();
        $this->assertEquals(1, $documento->ativado);
    }

    /** @test */
    public function it_can_unpublish_document()
    {
        $this->actingAs($this->user);
        
        $documento = factory(Documento::class)->create([
            'idUser' => $this->user->id,
            'idCategoria' => $this->categoria->id,
            'ativado' => 1
        ]);
        
        $response = $this->post(route('Documento.despublicar', $documento->id));
        
        $response->assertRedirect(route('Documento.index'));
        $response->assertSessionHas('success');
        
        $documento->refresh();
        $this->assertEquals(0, $documento->ativado);
    }

    /** @test */
    public function it_can_highlight_document()
    {
        $this->actingAs($this->user);
        
        $documento = factory(Documento::class)->create([
            'idUser' => $this->user->id,
            'idCategoria' => $this->categoria->id,
            'destaque' => 0
        ]);
        
        $response = $this->post(route('Documento.destacar', $documento->id));
        
        $response->assertRedirect(route('Documento.index'));
        $response->assertSessionHas('success');
        
        $documento->refresh();
        $this->assertEquals(1, $documento->destaque);
    }

    /** @test */
    public function it_can_remove_highlight_from_document()
    {
        $this->actingAs($this->user);
        
        $documento = factory(Documento::class)->create([
            'idUser' => $this->user->id,
            'idCategoria' => $this->categoria->id,
            'destaque' => 1
        ]);
        
        $response = $this->post(route('Documento.rdestacar', $documento->id));
        
        $response->assertRedirect(route('Documento.index'));
        $response->assertSessionHas('success');
        
        $documento->refresh();
        $this->assertEquals(0, $documento->destaque);
    }

    /** @test */
    public function it_can_bulk_publish_documents()
    {
        $this->actingAs($this->user);
        
        $documento1 = factory(Documento::class)->create([
            'idUser' => $this->user->id,
            'idCategoria' => $this->categoria->id,
            'ativado' => 0
        ]);
        
        $documento2 = factory(Documento::class)->create([
            'idUser' => $this->user->id,
            'idCategoria' => $this->categoria->id,
            'ativado' => 0
        ]);
        
        $data = ['check' => [$documento1->id, $documento2->id]];
        
        $response = $this->post(route('Documento.publicarCheck'), $data);
        
        $response->assertRedirect(route('Documento.index'));
        $response->assertSessionHas('success');
        
        $documento1->refresh();
        $documento2->refresh();
        
        $this->assertEquals(1, $documento1->ativado);
        $this->assertEquals(1, $documento2->ativado);
    }

    /** @test */
    public function it_can_bulk_delete_documents()
    {
        $this->actingAs($this->user);
        
        $documento1 = factory(Documento::class)->create([
            'idUser' => $this->user->id,
            'idCategoria' => $this->categoria->id
        ]);
        
        $documento2 = factory(Documento::class)->create([
            'idUser' => $this->user->id,
            'idCategoria' => $this->categoria->id
        ]);
        
        $data = ['check' => [$documento1->id, $documento2->id]];
        
        $response = $this->post(route('Documento.removerCheck'), $data);
        
        $response->assertRedirect(route('Documento.index'));
        $response->assertSessionHas('success');
        
        $this->assertDatabaseMissing('documentos', ['id' => $documento1->id]);
        $this->assertDatabaseMissing('documentos', ['id' => $documento2->id]);
    }

    /** @test */
    public function it_can_export_featured_documents()
    {
        $this->actingAs($this->user);
        
        $documento = factory(Documento::class)->create([
            'idUser' => $this->user->id,
            'idCategoria' => $this->categoria->id,
            'destaque' => 1,
            'ativado' => 1
        ]);
        
        Storage::fake('local');
        
        $response = $this->post(route('Documento.export'));
        
        $response->assertRedirect(route('Documento.index'));
        $response->assertSessionHas('success');
    }

    /** @test */
    public function it_can_create_slugs_for_all_documents()
    {
        $this->actingAs($this->user);
        
        $documento1 = factory(Documento::class)->create([
            'idUser' => $this->user->id,
            'idCategoria' => $this->categoria->id
        ]);
        
        $documento2 = factory(Documento::class)->create([
            'idUser' => $this->user->id,
            'idCategoria' => $this->categoria->id
        ]);
        
        $response = $this->post(route('Documento.createSlugAll'));
        
        $response->assertRedirect(route('Documento.index'));
        $response->assertSessionHas('success');
    }

    /** @test */
    public function it_handles_nonexistent_document_gracefully()
    {
        $this->actingAs($this->user);
        
        $response = $this->get(route('Documento.edit', 999));
        
        $response->assertRedirect(route('Documento.index'));
        $response->assertSessionHas('error');
    }

    /** @test */
    public function it_validates_required_fields()
    {
        $this->actingAs($this->user);
        
        $response = $this->post(route('Documento.store'), []);
        
        $response->assertSessionHasErrors([
            'tituloPT',
            'textopt',
            'idCategoria',
            'publicar',
            'data_criacao'
        ]);
    }
}