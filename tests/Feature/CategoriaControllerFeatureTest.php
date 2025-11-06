<?php

namespace Tests\Feature;

use App\Model\Categoria;
use App\Model\Tag;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class CategoriaControllerFeatureTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create a user and authenticate
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    /** @test */
    public function it_can_display_categories_index()
    {
        // Create some test categories
        $categoria1 = Categoria::create([
            'titulo' => 'Test Category 1',
            'categoria_tipo' => 'artigo',
            'order' => 1,
            'ativado' => 1
        ]);

        $categoria2 = Categoria::create([
            'titulo' => 'Test Category 2',
            'categoria_tipo' => 'artigo',
            'order' => 1,
            'ativado' => 1
        ]);

        $response = $this->get(route('Categoria.index'));

        $response->assertStatus(200);
        $response->assertViewIs('Administrator.Artigos.categoria');
        $response->assertViewHas(['tree', 'treeDoc', 'treeImg', 'treeLink', 'treeFaq', 'treeEvento']);
    }

    /** @test */
    public function it_can_show_create_form_for_artigo()
    {
        $response = $this->get(route('Categoria.create', ['tipo' => 'art']));

        $response->assertStatus(200);
        $response->assertViewIs('Administrator.Artigos.cat_form');
        $response->assertViewHas(['tag', 'type', 'cat']);
    }

    /** @test */
    public function it_can_show_create_form_for_documento()
    {
        $response = $this->get(route('Categoria.create', ['tipo' => 'documento']));

        $response->assertStatus(200);
        $response->assertViewIs('Administrator.Artigos.cat_form');
        $response->assertViewHas(['tag', 'type', 'cat']);
    }

    /** @test */
    public function it_can_show_create_img_form()
    {
        $response = $this->get(route('Categoria.createImg'));

        $response->assertStatus(200);
        $response->assertViewIs('Administrator.Artigos.addcategoria');
        $response->assertViewHas(['tag', 'type', 'cat']);
    }

    /** @test */
    public function it_can_create_a_new_category()
    {
        $categoryData = [
            'tituloPT' => 'Test Category',
            'ativado' => 'on',
            'parent' => null,
            'type' => 'art'
        ];

        $response = $this->post(route('Categoria.store'), $categoryData);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('categorias', [
            'titulo' => 'Test Category',
            'categoria_tipo' => 'artigo',
            'order' => 1,
            'ativado' => 1
        ]);
    }

    /** @test */
    public function it_can_create_a_subcategory()
    {
        $parentCategory = Categoria::factory()->create([
            'categoria_tipo' => 'artigo',
            'order' => 1
        ]);

        $categoryData = [
            'tituloPT' => 'Test Subcategory',
            'ativado' => 'on',
            'parent' => $parentCategory->id,
            'type' => 'art'
        ];

        $response = $this->post(route('Categoria.store'), $categoryData);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('categorias', [
            'titulo' => 'Test Subcategory',
            'categoria_tipo' => 'artigo',
            'order' => 2,
            'parent' => $parentCategory->id
        ]);
    }

    /** @test */
    public function it_validates_required_fields_when_creating_category()
    {
        $response = $this->post(route('Categoria.store'), []);

        $response->assertSessionHasErrors(['tituloPT']);
    }

    /** @test */
    public function it_can_show_edit_form()
    {
        $categoria = Categoria::factory()->create([
            'categoria_tipo' => 'artigo'
        ]);

        $response = $this->get(route('Categoria.edit', $categoria->id));

        $response->assertStatus(200);
        $response->assertViewIs('Administrator.Artigos.cat_form');
        $response->assertViewHas(['categoria', 'tag', 'items', 'content']);
    }

    /** @test */
    public function it_can_update_a_category()
    {
        $categoria = Categoria::factory()->create([
            'titulo' => 'Original Name',
            'categoria_tipo' => 'artigo',
            'default' => 0
        ]);

        $updateData = [
            'tituloPT' => 'Updated Name',
            'ativado' => 'on',
            'parent' => null,
            'type' => 'art'
        ];

        $response = $this->put(route('Categoria.update', $categoria->id), $updateData);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $categoria->refresh();
        $this->assertEquals('Updated Name', $categoria->titulo);
    }

    /** @test */
    public function it_cannot_update_default_category()
    {
        $categoria = Categoria::factory()->create([
            'titulo' => 'Default Category',
            'default' => 1
        ]);

        $updateData = [
            'tituloPT' => 'Updated Name',
            'ativado' => 'on',
            'parent' => null,
            'type' => 'art'
        ];

        $response = $this->put(route('Categoria.update', $categoria->id), $updateData);

        $response->assertRedirect();
        $response->assertSessionHas('warning');

        $categoria->refresh();
        $this->assertEquals('Default Category', $categoria->titulo);
    }

    /** @test */
    public function it_can_delete_a_category()
    {
        $categoria = Categoria::factory()->create([
            'categoria_tipo' => 'artigo'
        ]);

        $response = $this->delete(route('Categoria.destroy', $categoria->id));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('categorias', [
            'id' => $categoria->id
        ]);
    }

    /** @test */
    public function it_can_publish_a_category()
    {
        $categoria = Categoria::factory()->create([
            'categoria_tipo' => 'artigo',
            'ativado' => 0
        ]);

        $response = $this->patch(route('Categoria.publicar', [$categoria->id, 'artigo']));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $categoria->refresh();
        $this->assertEquals(1, $categoria->ativado);
    }

    /** @test */
    public function it_can_unpublish_a_category()
    {
        $categoria = Categoria::factory()->create([
            'categoria_tipo' => 'artigo',
            'ativado' => 1
        ]);

        $response = $this->patch(route('Categoria.despublicar', [$categoria->id, 'artigo']));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $categoria->refresh();
        $this->assertEquals(0, $categoria->ativado);
    }

    /** @test */
    public function it_can_move_category_up()
    {
        $categoria1 = Categoria::factory()->create([
            'categoria_tipo' => 'artigo',
            'order' => 1
        ]);

        $categoria2 = Categoria::factory()->create([
            'categoria_tipo' => 'artigo',
            'order' => 2
        ]);

        $response = $this->patch(route('Categoria.upOrder', $categoria2->id));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $categoria1->refresh();
        $categoria2->refresh();

        $this->assertEquals(2, $categoria1->order);
        $this->assertEquals(1, $categoria2->order);
    }

    /** @test */
    public function it_can_move_category_down()
    {
        $categoria1 = Categoria::factory()->create([
            'categoria_tipo' => 'artigo',
            'order' => 1
        ]);

        $categoria2 = Categoria::factory()->create([
            'categoria_tipo' => 'artigo',
            'order' => 2
        ]);

        $response = $this->patch(route('Categoria.downOrder', $categoria1->id));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $categoria1->refresh();
        $categoria2->refresh();

        $this->assertEquals(2, $categoria1->order);
        $this->assertEquals(1, $categoria2->order);
    }

    /** @test */
    public function it_can_generate_slugs_for_all_categories()
    {
        Categoria::factory()->count(5)->create([
            'titulo' => 'Test Category',
            'slug' => null
        ]);

        $response = $this->post(route('Categoria.createSlugAll'));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $categories = Categoria::whereNotNull('slug')->count();
        $this->assertGreaterThan(0, $categories);
    }

    /** @test */
    public function it_handles_invalid_category_type_gracefully()
    {
        $response = $this->get(route('Categoria.create', ['tipo' => 'invalid']));

        $response->assertStatus(200);
        // Should still load the form but with default values
    }

    /** @test */
    public function it_redirects_to_imagem_index_for_image_categories()
    {
        $categoryData = [
            'tituloPT' => 'Test Image Category',
            'ativado' => 'on',
            'parent' => null,
            'type' => 'img'
        ];

        $response = $this->post(route('Categoria.store'), $categoryData);

        $response->assertRedirect(route('Imagem.index'));
    }

    /** @test */
    public function it_prevents_creating_duplicate_category_names()
    {
        Categoria::factory()->create([
            'titulo' => 'Duplicate Name',
            'categoria_tipo' => 'artigo'
        ]);

        $categoryData = [
            'tituloPT' => 'Duplicate Name',
            'ativado' => 'on',
            'parent' => null,
            'type' => 'art'
        ];

        $response = $this->post(route('Categoria.store'), $categoryData);

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }
}