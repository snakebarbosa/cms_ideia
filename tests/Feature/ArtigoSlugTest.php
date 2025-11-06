<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;
use App\Model\Artigo;
use App\Model\Categoria;
use App\Services\ArtigoService;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ArtigoSlugTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;
    protected $categoria;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = factory(User::class)->create();
        $this->categoria = factory(Categoria::class)->create([
            'artigo' => 1,
            'ativado' => 1
        ]);
    }

    /** @test */
    public function it_shows_slug_fields_only_when_editing()
    {
        $this->actingAs($this->user);
        
        // Test create form (slug fields should not appear)
        $response = $this->get(route('Artigo.create'));
        $response->assertStatus(200);
        $response->assertDontSee('URL Slug (PT):');
        $response->assertDontSee('URL Slug (EN):');
        
        // Create an article
        $article = factory(Artigo::class)->create([
            'idUser' => $this->user->id,
            'idCategoria' => $this->categoria->id
        ]);
        
        // Test edit form (slug fields should appear)
        $response = $this->get(route('Artigo.edit', $article->id));
        $response->assertStatus(200);
        $response->assertSee('URL Slug (PT):');
        $response->assertSee('URL Slug (EN):');
    }

    /** @test */
    public function it_can_update_article_with_custom_slugs()
    {
        $this->actingAs($this->user);
        
        $article = factory(Artigo::class)->create([
            'idUser' => $this->user->id,
            'idCategoria' => $this->categoria->id,
            'slug' => json_encode(['pt' => 'slug-original-pt', 'en' => 'original-slug-en'])
        ]);
        
        $data = [
            'tituloPT' => 'Updated Article Title',
            'tituloEN' => 'Updated Article Title EN',
            'ativado' => 'on',
            'publicar' => now()->format('Y-m-d'),
            'data_criacao' => now()->format('Y-m-d'),
            'idCategoria' => $this->categoria->id,
            'textopt' => 'Updated content',
            'textoen' => 'Updated content EN',
            'slug_pt' => 'slug-personalizado-pt',
            'slug_en' => 'custom-updated-slug-en'
        ];
        
        $response = $this->put(route('Artigo.update', $article->id), $data);
        
        $response->assertRedirect();
        $response->assertSessionHas('success');
        
        $article->refresh();
        $slugData = json_decode($article->slug, true);
        $this->assertEquals('slug-personalizado-pt', $slugData['pt']);
        $this->assertEquals('custom-updated-slug-en', $slugData['en']);
    }

    /** @test */
    public function it_prevents_duplicate_pt_slug_on_update()
    {
        $this->actingAs($this->user);
        
        // Create first article
        $article1 = factory(Artigo::class)->create([
            'idUser' => $this->user->id,
            'idCategoria' => $this->categoria->id,
            'slug' => json_encode(['pt' => 'slug-existente-pt', 'en' => 'existing-slug-en'])
        ]);
        
        // Create second article
        $article2 = factory(Artigo::class)->create([
            'idUser' => $this->user->id,
            'idCategoria' => $this->categoria->id,
            'slug' => json_encode(['pt' => 'outro-slug-pt', 'en' => 'another-slug-en'])
        ]);
        
        $data = [
            'tituloPT' => 'Updated Article Title',
            'tituloEN' => 'Updated Article Title EN',
            'ativado' => 'on',
            'publicar' => now()->format('Y-m-d'),
            'data_criacao' => now()->format('Y-m-d'),
            'idCategoria' => $this->categoria->id,
            'textopt' => 'Updated content',
            'textoen' => 'Updated content EN',
            'slug_pt' => 'slug-existente-pt' // Try to use existing PT slug
        ];
        
        $response = $this->put(route('Artigo.update', $article2->id), $data);
        
        // Should fail validation
        $response->assertRedirect();
        $response->assertSessionHasErrors('slug_pt');
    }

    /** @test */
    public function it_prevents_duplicate_en_slug_on_update()
    {
        $this->actingAs($this->user);
        
        // Create first article
        $article1 = factory(Artigo::class)->create([
            'idUser' => $this->user->id,
            'idCategoria' => $this->categoria->id,
            'slug' => json_encode(['pt' => 'slug-existente-pt', 'en' => 'existing-slug-en'])
        ]);
        
        // Create second article
        $article2 = factory(Artigo::class)->create([
            'idUser' => $this->user->id,
            'idCategoria' => $this->categoria->id,
            'slug' => json_encode(['pt' => 'outro-slug-pt', 'en' => 'another-slug-en'])
        ]);
        
        $data = [
            'tituloPT' => 'Updated Article Title',
            'tituloEN' => 'Updated Article Title EN',
            'ativado' => 'on',
            'publicar' => now()->format('Y-m-d'),
            'data_criacao' => now()->format('Y-m-d'),
            'idCategoria' => $this->categoria->id,
            'textopt' => 'Updated content',
            'textoen' => 'Updated content EN',
            'slug_en' => 'existing-slug-en' // Try to use existing EN slug
        ];
        
        $response = $this->put(route('Artigo.update', $article2->id), $data);
        
        // Should fail validation
        $response->assertRedirect();
        $response->assertSessionHasErrors('slug_en');
    }

    /** @test */
    public function it_validates_pt_slug_format()
    {
        $this->actingAs($this->user);
        
        $article = factory(Artigo::class)->create([
            'idUser' => $this->user->id,
            'idCategoria' => $this->categoria->id
        ]);
        
        $data = [
            'tituloPT' => 'Test Article',
            'tituloEN' => 'Test Article EN',
            'ativado' => 'on',
            'publicar' => now()->format('Y-m-d'),
            'data_criacao' => now()->format('Y-m-d'),
            'idCategoria' => $this->categoria->id,
            'textopt' => 'Test content',
            'textoen' => 'Test content EN',
            'slug_pt' => 'Slug Inválido Com Espaços!' // Invalid PT slug format
        ];
        
        $response = $this->put(route('Artigo.update', $article->id), $data);
        
        // Should fail validation
        $response->assertRedirect();
        $response->assertSessionHasErrors('slug_pt');
    }

    /** @test */
    public function it_validates_en_slug_format()
    {
        $this->actingAs($this->user);
        
        $article = factory(Artigo::class)->create([
            'idUser' => $this->user->id,
            'idCategoria' => $this->categoria->id
        ]);
        
        $data = [
            'tituloPT' => 'Test Article',
            'tituloEN' => 'Test Article EN',
            'ativado' => 'on',
            'publicar' => now()->format('Y-m-d'),
            'data_criacao' => now()->format('Y-m-d'),
            'idCategoria' => $this->categoria->id,
            'textopt' => 'Test content',
            'textoen' => 'Test content EN',
            'slug_en' => 'Invalid Slug With Spaces!' // Invalid EN slug format
        ];
        
        $response = $this->put(route('Artigo.update', $article->id), $data);
        
        // Should fail validation
        $response->assertRedirect();
        $response->assertSessionHasErrors('slug_en');
    }

    /** @test */
    public function it_allows_empty_slugs_for_auto_generation()
    {
        $this->actingAs($this->user);
        
        $article = factory(Artigo::class)->create([
            'idUser' => $this->user->id,
            'idCategoria' => $this->categoria->id,
            'slug' => json_encode(['pt' => 'slug-original-pt', 'en' => 'original-slug-en'])
        ]);
        
        $data = [
            'tituloPT' => 'Título Artigo Atualizado',
            'tituloEN' => 'Updated Article Title',
            'ativado' => 'on',
            'publicar' => now()->format('Y-m-d'),
            'data_criacao' => now()->format('Y-m-d'),
            'idCategoria' => $this->categoria->id,
            'textopt' => 'Updated content',
            'textoen' => 'Updated content EN',
            'slug_pt' => '', // Empty PT slug should trigger auto-generation
            'slug_en' => ''  // Empty EN slug should trigger auto-generation
        ];
        
        $response = $this->put(route('Artigo.update', $article->id), $data);
        
        $response->assertRedirect();
        $response->assertSessionHas('success');
        
        $article->refresh();
        $slugData = json_decode($article->slug, true);
        
        // Slugs should be auto-generated from titles, not empty
        $this->assertNotEmpty($slugData['pt']);
        $this->assertNotEmpty($slugData['en']);
        $this->assertNotEquals('slug-original-pt', $slugData['pt']);
        $this->assertNotEquals('original-slug-en', $slugData['en']);
        
        // Check that auto-generated slugs contain article ID and are based on titles
        $this->assertStringContains((string)$article->id, $slugData['pt']);
        $this->assertStringContains((string)$article->id, $slugData['en']);
    }

    /** @test */
    public function it_allows_same_slugs_for_same_article()
    {
        $this->actingAs($this->user);
        
        $article = factory(Artigo::class)->create([
            'idUser' => $this->user->id,
            'idCategoria' => $this->categoria->id,
            'slug' => json_encode(['pt' => 'slug-existente-pt', 'en' => 'existing-slug-en'])
        ]);
        
        $data = [
            'tituloPT' => 'Updated Article Title',
            'tituloEN' => 'Updated Article Title EN',
            'ativado' => 'on',
            'publicar' => now()->format('Y-m-d'),
            'data_criacao' => now()->format('Y-m-d'),
            'idCategoria' => $this->categoria->id,
            'textopt' => 'Updated content',
            'textoen' => 'Updated content EN',
            'slug_pt' => 'slug-existente-pt', // Same PT slug should be allowed for same article
            'slug_en' => 'existing-slug-en'   // Same EN slug should be allowed for same article
        ];
        
        $response = $this->put(route('Artigo.update', $article->id), $data);
        
        $response->assertRedirect();
        $response->assertSessionHas('success');
        
        $article->refresh();
        $slugData = json_decode($article->slug, true);
        $this->assertEquals('slug-existente-pt', $slugData['pt']);
        $this->assertEquals('existing-slug-en', $slugData['en']);
    }
}