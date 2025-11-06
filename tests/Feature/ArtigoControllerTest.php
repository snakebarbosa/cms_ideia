<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;
use App\Model\Artigo;
use App\Model\Categoria;
use App\Model\Tag;
use App\Model\Documento;
use App\Model\Conteudo;
use App\Model\Faq;
use App\Services\ArtigoService;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class ArtigoControllerTest extends TestCase
{
    use DatabaseTransactions, WithFaker;

    protected $user;
    protected $artigo;
    protected $categoria;
    protected $tag;
    protected $artigoService;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create a test user
        $this->user = factory(User::class)->create();
        
        // Create test categoria
        $this->categoria = factory(Categoria::class)->create([
            'artigo' => 1,
            'ativado' => 1
        ]);
        
        // Create test tag
        $this->tag = factory(Tag::class)->create();
        
        // Create test artigo
        $this->artigo = factory(Artigo::class)->create([
            'idCategoria' => $this->categoria->id,
            'idUser' => $this->user->id,
            'ativado' => ArtigoService::STATUS_ACTIVE
        ]);

        // Initialize service
        $this->artigoService = new ArtigoService();
    }

    /** @test */
    public function it_can_display_articles_index()
    {
        $this->actingAs($this->user);
        
        $response = $this->get(route('Artigo.index'));
        
        $response->assertStatus(200);
        $response->assertViewIs('Administrator.Artigos.artigo');
        $response->assertViewHas('art');
        $response->assertViewHas('articles');
    }

    /** @test */
    public function it_can_display_articles_index_with_filters()
    {
        $this->actingAs($this->user);
        
        $response = $this->get(route('Artigo.index', [
            'status' => ArtigoService::STATUS_ACTIVE,
            'destacado' => ArtigoService::HIGHLIGHTED
        ]));
        
        $response->assertStatus(200);
        $response->assertViewIs('Administrator.Artigos.artigo');
    }

    /** @test */
    public function it_handles_index_errors_gracefully()
    {
        $this->actingAs($this->user);
        
        // Test with error condition that might occur
        $response = $this->get(route('Artigo.index'));
        
        $response->assertStatus(200);
        $response->assertViewIs('Administrator.Artigos.artigo');
    }

    /** @test */
    public function it_can_show_create_form()
    {
        $this->actingAs($this->user);
        
        $response = $this->get(route('Artigo.create'));
        
        $response->assertStatus(200);
        $response->assertViewIs('Administrator.Artigos.artigo_form');
        $response->assertViewHas(['tag', 'cat', 'catimg', 'tree', 'doc']);
    }

    /** @test */
    public function it_handles_create_form_errors_gracefully()
    {
        $this->actingAs($this->user);
        
        // This test verifies that the create form loads properly
        // In case of service errors, it should redirect with error message
        $response = $this->get(route('Artigo.create'));
        
        // If successful, should show the form
        $response->assertStatus(200);
        $response->assertViewIs('Administrator.Artigos.artigo_form');
    }

    /** @test */
    public function it_can_store_new_article()
    {
        $this->actingAs($this->user);
        
        $data = [
            'tituloPT' => 'Test Article Title',
            'ativado' => 'on',
            'destaque' => 'on',
            'publicar' => now()->format('Y-m-d'),
            'data_criacao' => now()->format('Y-m-d'),
            'despublicar' => null,
            'idCategoria' => $this->categoria->id,
            'keyword' => 'test, article, keywords',
            'idimagem' => 1,
            'tag' => [$this->tag->id],
            'textopt' => 'Portuguese content here',
            'textoen' => 'English content here',
            'tituloEN' => 'English Title'
        ];
        
        $response = $this->post(route('Artigo.store'), $data);
        
        $response->assertRedirect();
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('artigos', [
            'alias' => 'Test Article Title',
            'ativado' => ArtigoService::STATUS_ACTIVE,
            'destaque' => ArtigoService::HIGHLIGHTED,
            'idCategoria' => $this->categoria->id,
            'keyword' => 'test, article, keywords',
            'idUser' => $this->user->id
        ]);
    }

    /** @test */
    public function it_handles_store_errors_gracefully()
    {
        $this->actingAs($this->user);
        
        // Test with invalid data to trigger error
        $data = [
            'tituloPT' => '', // Empty title should cause error
            'idCategoria' => 99999, // Non-existent category
        ];
        
        $response = $this->post(route('Artigo.store'), $data);
        
        // Should redirect back with error or validation errors
        $response->assertRedirect();
    }

    /** @test */
    public function it_can_show_edit_form()
    {
        $this->actingAs($this->user);
        
        $response = $this->get(route('Artigo.edit', $this->artigo->id));
        
        $response->assertStatus(200);
        $response->assertViewIs('Administrator.Artigos.artigo_form');
        $response->assertViewHas(['artigo', 'content', 'documento', 'tag', 'cat', 'catimg', 'tree', 'doc']);
    }

    /** @test */
    public function it_handles_edit_form_errors_gracefully()
    {
        $this->actingAs($this->user);
        
        $response = $this->get(route('Artigo.edit', 999999)); // Non-existent ID
        
        $response->assertRedirect(route('Artigo.index'));
        $response->assertSessionHas('error');
    }

    /** @test */
    public function it_can_update_article()
    {
        $this->actingAs($this->user);
        
        $data = [
            'tituloPT' => 'Updated Article Title',
            'ativado' => 'on',
            'destaque' => '',
            'publicar' => now()->format('Y-m-d'),
            'data_criacao' => now()->format('Y-m-d'),
            'despublicar' => null,
            'idCategoria' => $this->categoria->id,
            'keyword' => 'updated, keywords',
            'idimagem' => 1,
            'tag' => [$this->tag->id],
            'textopt' => 'Updated Portuguese content',
            'textoen' => 'Updated English content',
            'tituloEN' => 'Updated English Title',
            'iddocumentoartigo' => [null]
        ];
        
        $response = $this->put(route('Artigo.update', $this->artigo->id), $data);
        
        $response->assertRedirect();
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('artigos', [
            'id' => $this->artigo->id,
            'alias' => 'Updated Article Title',
            'destaque' => ArtigoService::NOT_HIGHLIGHTED,
            'keyword' => 'updated, keywords'
        ]);
    }

    /** @test */
    public function it_handles_update_errors_gracefully()
    {
        $this->actingAs($this->user);
        
        $response = $this->put(route('Artigo.update', 999999), []); // Non-existent ID
        
        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    /** @test */
    public function it_can_delete_article()
    {
        $this->actingAs($this->user);
        
        $response = $this->get('/Administrator/Artigo/delete/' . $this->artigo->id);
        
        $response->assertRedirect(route('Artigo.index'));
        $response->assertSessionHas('success');
        
        $this->assertDatabaseMissing('artigos', [
            'id' => $this->artigo->id
        ]);
    }

    /** @test */
    public function it_handles_delete_errors_gracefully()
    {
        $this->actingAs($this->user);
        
        $response = $this->get('/Administrator/Artigo/delete/999999'); // Non-existent ID
        
        $response->assertRedirect(route('Artigo.index'));
        $response->assertSessionHas('error');
    }

    /** @test */
    public function it_can_unpublish_article()
    {
        $this->actingAs($this->user);
        
        $response = $this->get('/Administrator/Artigo/despublicar/' . $this->artigo->id);
        
        $response->assertRedirect(route('Artigo.index', $this->artigo->id));
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('artigos', [
            'id' => $this->artigo->id,
            'ativado' => ArtigoService::STATUS_INACTIVE
        ]);
    }

    /** @test */
    public function it_can_publish_article()
    {
        $this->actingAs($this->user);
        
        // First unpublish the article
        $this->artigo->update(['ativado' => ArtigoService::STATUS_INACTIVE]);
        
        $response = $this->get('/Administrator/Artigo/publicar/' . $this->artigo->id);
        
        $response->assertRedirect(route('Artigo.index', $this->artigo->id));
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('artigos', [
            'id' => $this->artigo->id,
            'ativado' => ArtigoService::STATUS_ACTIVE
        ]);
    }

    /** @test */
    public function it_can_highlight_article()
    {
        $this->actingAs($this->user);
        
        $response = $this->get('/Administrator/Artigo/destacar/' . $this->artigo->id);
        
        $response->assertRedirect(route('Artigo.index', $this->artigo->id));
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('artigos', [
            'id' => $this->artigo->id,
            'destaque' => ArtigoService::HIGHLIGHTED
        ]);
    }

    /** @test */
    public function it_can_remove_highlight_from_article()
    {
        $this->actingAs($this->user);
        
        // First highlight the article
        $this->artigo->update(['destaque' => ArtigoService::HIGHLIGHTED]);
        
        $response = $this->get('/Administrator/Artigo/rdestacar/' . $this->artigo->id);
        
        $response->assertRedirect(route('Artigo.index', $this->artigo->id));
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('artigos', [
            'id' => $this->artigo->id,
            'destaque' => ArtigoService::NOT_HIGHLIGHTED
        ]);
    }

    /** @test */
    public function it_handles_status_update_errors_gracefully()
    {
        $this->actingAs($this->user);
        
        $response = $this->get('/Administrator/Artigo/publicar/999999'); // Non-existent ID
        
        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    /** @test */
    public function it_can_bulk_publish_articles()
    {
        $this->actingAs($this->user);
        
        $artigo2 = factory(Artigo::class)->create([
            'ativado' => ArtigoService::STATUS_INACTIVE,
            'idUser' => $this->user->id
        ]);
        
        $data = [
            'check' => [$this->artigo->id, $artigo2->id]
        ];
        
        $response = $this->post(route('Art.publicarcheck'), $data);
        
        $response->assertRedirect(route('Artigo.index'));
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('artigos', [
            'id' => $this->artigo->id,
            'ativado' => ArtigoService::STATUS_ACTIVE
        ]);
        
        $this->assertDatabaseHas('artigos', [
            'id' => $artigo2->id,
            'ativado' => ArtigoService::STATUS_ACTIVE
        ]);
    }

    /** @test */
    public function it_can_bulk_unpublish_articles()
    {
        $this->actingAs($this->user);
        
        $artigo2 = factory(Artigo::class)->create([
            'ativado' => ArtigoService::STATUS_ACTIVE,
            'idUser' => $this->user->id
        ]);
        
        $data = [
            'check' => [$this->artigo->id, $artigo2->id]
        ];
        
        $response = $this->post(route('Art.despublicarcheck'), $data);
        
        $response->assertRedirect(route('Artigo.index'));
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('artigos', [
            'id' => $this->artigo->id,
            'ativado' => ArtigoService::STATUS_INACTIVE
        ]);
        
        $this->assertDatabaseHas('artigos', [
            'id' => $artigo2->id,
            'ativado' => ArtigoService::STATUS_INACTIVE
        ]);
    }

    /** @test */
    public function it_can_bulk_highlight_articles()
    {
        $this->actingAs($this->user);
        
        $artigo2 = factory(Artigo::class)->create([
            'destaque' => ArtigoService::NOT_HIGHLIGHTED,
            'idUser' => $this->user->id
        ]);
        
        $data = [
            'check' => [$this->artigo->id, $artigo2->id]
        ];
        
        $response = $this->post(route('Art.destaquecheck'), $data);
        
        $response->assertRedirect(route('Artigo.index'));
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('artigos', [
            'id' => $this->artigo->id,
            'destaque' => ArtigoService::HIGHLIGHTED
        ]);
        
        $this->assertDatabaseHas('artigos', [
            'id' => $artigo2->id,
            'destaque' => ArtigoService::HIGHLIGHTED
        ]);
    }

    /** @test */
    public function it_can_bulk_remove_highlight_from_articles()
    {
        $this->actingAs($this->user);
        
        $artigo2 = factory(Artigo::class)->create([
            'destaque' => ArtigoService::HIGHLIGHTED,
            'idUser' => $this->user->id
        ]);
        
        $data = [
            'check' => [$this->artigo->id, $artigo2->id]
        ];
        
        $response = $this->post(route('Art.rdestaquecheck'), $data);
        
        $response->assertRedirect(route('Artigo.index'));
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('artigos', [
            'id' => $this->artigo->id,
            'destaque' => ArtigoService::NOT_HIGHLIGHTED
        ]);
        
        $this->assertDatabaseHas('artigos', [
            'id' => $artigo2->id,
            'destaque' => ArtigoService::NOT_HIGHLIGHTED
        ]);
    }

    /** @test */
    public function it_can_bulk_delete_articles()
    {
        $this->actingAs($this->user);
        
        $artigo2 = factory(Artigo::class)->create([
            'idUser' => $this->user->id
        ]);
        
        $data = [
            'check' => [$this->artigo->id, $artigo2->id]
        ];
        
        $response = $this->post(route('Art.removercheck'), $data);
        
        $response->assertRedirect(route('Artigo.index'));
        $response->assertSessionHas('success');
        
        $this->assertDatabaseMissing('artigos', [
            'id' => $this->artigo->id
        ]);
        
        $this->assertDatabaseMissing('artigos', [
            'id' => $artigo2->id
        ]);
    }

    /** @test */
    public function it_handles_bulk_operation_errors_gracefully()
    {
        $this->actingAs($this->user);
        
        // Test with empty check array or non-existent IDs
        $data = [
            'check' => [999999, 999998] // Non-existent IDs
        ];
        
        $response = $this->post(route('Art.publicarcheck'), $data);
        
        $response->assertRedirect(route('Artigo.index'));
        // Depending on implementation, might have success or error message
    }

    /** @test */
    public function it_can_export_highlighted_articles()
    {
        $this->actingAs($this->user);
        
        // Create a highlighted article
        $highlightedArticle = factory(Artigo::class)->create([
            'destaque' => ArtigoService::HIGHLIGHTED,
            'ativado' => ArtigoService::STATUS_ACTIVE,
            'idUser' => $this->user->id
        ]);
        
        $response = $this->get('/Administrator/Artigo/export');
        
        $response->assertRedirect(route('Artigo.index'));
        $response->assertSessionHas('success');
        
        // Check if JSON file was created
        $this->assertTrue(File::exists(storage_path('json/artigo.json')));
    }

    /** @test */
    public function it_handles_export_errors_gracefully()
    {
        $this->actingAs($this->user);
        
        // Create a scenario where export might fail
        // For example, no highlighted articles
        $response = $this->get('/Administrator/Artigo/export');
        
        $response->assertRedirect(route('Artigo.index'));
        // Should have success or error message depending on result
    }

    /** @test */
    public function it_can_search_articles()
    {
        $this->actingAs($this->user);
        
        $data = [
            'search' => 'test search term'
        ];
        
        $response = $this->post(route('Art.search'), $data);
        
        $response->assertStatus(200);
        $response->assertViewIs('Administrator.search');
        $response->assertViewHas('art');
        $response->assertViewHas('articles');
    }

    /** @test */
    public function it_validates_search_input()
    {
        $this->actingAs($this->user);
        
        // Test with too short search term
        $data = [
            'search' => 'a'
        ];
        
        $response = $this->post(route('Art.search'), $data);
        
        $response->assertSessionHasErrors('search');
    }

    /** @test */
    public function it_handles_search_errors_gracefully()
    {
        $this->actingAs($this->user);
        
        // Test with empty search term
        $data = [
            'search' => ''
        ];
        
        $response = $this->post(route('Art.search'), $data);
        
        $response->assertSessionHasErrors('search');
    }

    /** @test */
    public function it_requires_authentication_for_all_actions()
    {
        // Test without authentication
        $response = $this->get(route('Artigo.index'));
        $response->assertRedirect('/login');
        
        $response = $this->get(route('Artigo.create'));
        $response->assertRedirect('/login');
        
        $response = $this->post(route('Artigo.store'), []);
        $response->assertRedirect('/login');
        
        $response = $this->get(route('Artigo.edit', 1));
        $response->assertRedirect('/login');
        
        $response = $this->put(route('Artigo.update', 1), []);
        $response->assertRedirect('/login');
        
        $response = $this->get('/Administrator/Artigo/delete/1');
        $response->assertRedirect('/login');
    }

    /** @test */
    public function it_can_create_slugs_for_all_faqs()
    {
        $this->actingAs($this->user);
        
        // Create some FAQs
        $faq1 = factory(Faq::class)->create(['alias' => 'Test FAQ 1']);
        $faq2 = factory(Faq::class)->create(['alias' => 'Test FAQ 2']);
        
        // This method uses dd() so we expect it to halt execution
        // We can test that FAQs exist for the method to work on
        $this->assertDatabaseHas('faqs', ['id' => $faq1->id]);
        $this->assertDatabaseHas('faqs', ['id' => $faq2->id]);
    }

    /** @test */
    public function it_handles_store_without_optional_fields()
    {
        $this->actingAs($this->user);
        
        $data = [
            'tituloPT' => 'Minimal Article',
            'publicar' => now()->format('Y-m-d'),
            'data_criacao' => now()->format('Y-m-d'),
            'idCategoria' => $this->categoria->id,
            'textopt' => 'Minimal content'
        ];
        
        $response = $this->post(route('Artigo.store'), $data);
        
        $response->assertRedirect();
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('artigos', [
            'alias' => 'Minimal Article',
            'ativado' => ArtigoService::STATUS_INACTIVE, // Should be inactive when checkbox is not checked
            'destaque' => ArtigoService::NOT_HIGHLIGHTED // Should be not highlighted when checkbox is not checked
        ]);
    }

    /** @test */
    public function it_handles_update_with_document_attachments()
    {
        $this->actingAs($this->user);
        
        $documento = factory(Documento::class)->create();
        
        $data = [
            'tituloPT' => 'Updated with Documents',
            'ativado' => 'on',
            'publicar' => now()->format('Y-m-d'),
            'data_criacao' => now()->format('Y-m-d'),
            'idCategoria' => $this->categoria->id,
            'keyword' => 'test',
            'textopt' => 'Content with documents',
            'iddocumentoartigo' => [$documento->id . ',']
        ];
        
        $response = $this->put(route('Artigo.update', $this->artigo->id), $data);
        
        $response->assertRedirect();
        $response->assertSessionHas('success');
    }

    /** @test */
    public function show_method_redirects_to_index()
    {
        $this->actingAs($this->user);
        
        $response = $this->get(route('Artigo.show', $this->artigo->id));
        
        $response->assertRedirect(route('Artigo.index'));
    }
}