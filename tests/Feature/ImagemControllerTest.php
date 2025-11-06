<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Model\Categoria;
use App\Model\Imagem;
use App\Model\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class ImagemControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create a user for authentication
        $this->user = factory(User::class)->create();
    }

    /** @test */
    public function it_displays_imagem_index_page()
    {
        // Create root category for imagem
        $rootCategory = factory(Categoria::class)->create([
            'titulo' => 'Imagens Root',
            'categoria_tipo' => 'imagem',
            'default' => 1,
            'parent' => 0,
            'ativado' => 1,
            'order' => 0
        ]);

        // Create child categories
        $childCategory1 = factory(Categoria::class)->create([
            'titulo' => 'Galeria 1',
            'categoria_tipo' => 'imagem',
            'default' => 0,
            'parent' => $rootCategory->id,
            'ativado' => 1,
            'order' => 1
        ]);

        $childCategory2 = factory(Categoria::class)->create([
            'titulo' => 'Galeria 2',
            'categoria_tipo' => 'imagem',
            'default' => 0,
            'parent' => $rootCategory->id,
            'ativado' => 1,
            'order' => 2
        ]);

        // Create sub-child category
        $subChildCategory = factory(Categoria::class)->create([
            'titulo' => 'Sub Galeria 1',
            'categoria_tipo' => 'imagem',
            'default' => 0,
            'parent' => $childCategory1->id,
            'ativado' => 1,
            'order' => 1
        ]);

        // Create some images
        $image1 = factory(Imagem::class)->create([
            'titulo' => 'Test Image 1',
            'idCategoria' => $childCategory1->id,
            'ativado' => 1
        ]);

        $image2 = factory(Imagem::class)->create([
            'titulo' => 'Test Image 2',
            'idCategoria' => $childCategory2->id,
            'ativado' => 1
        ]);

        // Act - Make authenticated request
        $response = $this->actingAs($this->user)
            ->get(route('Imagem.index'));

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('Administrator.Midia.midia');
        
        // Check if variables are passed to view
        $response->assertViewHas('tree');
        $response->assertViewHas('data');
        $response->assertViewHas('cat');

        // Get the tree variable
        $tree = $response->viewData('tree');

        // Verify tree structure
        $this->assertIsArray($tree);
        $this->assertArrayHasKey('id', $tree);
        $this->assertArrayHasKey('titulo', $tree);
        $this->assertArrayHasKey('childreen', $tree);

        // Verify root category
        $this->assertEquals($rootCategory->id, $tree['id']);
        $this->assertEquals('Imagens Root', $tree['titulo']);

        // Verify children exist
        $this->assertCount(2, $tree['childreen']);

        // Verify first child structure
        $firstChild = $tree['childreen'][0];
        $this->assertArrayHasKey('id', $firstChild);
        $this->assertArrayHasKey('titulo', $firstChild);
        $this->assertArrayHasKey('childreen', $firstChild);
        $this->assertArrayHasKey('ativado', $firstChild);

        // Verify first child has sub-children
        $this->assertCount(1, $firstChild['childreen']);
        
        // Verify sub-child structure
        $subChild = $firstChild['childreen'][0];
        $this->assertArrayHasKey('id', $subChild);
        $this->assertArrayHasKey('titulo', $subChild);
        $this->assertArrayHasKey('ativado', $subChild);
        $this->assertArrayHasKey('leaf', $subChild);
        $this->assertEquals(1, $subChild['leaf']);

        // Verify images data
        $images = $response->viewData('data');
        $this->assertCount(2, $images);

        // Verify categories list
        $categories = $response->viewData('cat');
        $this->assertCount(4, $categories); // Should include all non-default imagem categories
    }

    /** @test */
    public function it_requires_authentication_for_index()
    {
        $response = $this->get(route('Imagem.index'));
        
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function it_handles_missing_root_category_gracefully()
    {
        // Don't create any categories
        
        $response = $this->actingAs($this->user)
            ->get(route('Imagem.index'));

        $response->assertStatus(200);
        
        // Get the tree variable
        $tree = $response->viewData('tree');

        // Should return default structure
        $this->assertIsArray($tree);
        $this->assertArrayHasKey('id', $tree);
        $this->assertArrayHasKey('titulo', $tree);
        $this->assertArrayHasKey('childreen', $tree);
        $this->assertEquals(0, $tree['id']);
        $this->assertEquals('Root', $tree['titulo']);
        $this->assertEmpty($tree['childreen']);
    }

    /** @test */
    public function tree_structure_matches_view_expectations()
    {
        // Create categories matching the view's expected structure
        $rootCategory = factory(Categoria::class)->create([
            'titulo' => 'Root Category',
            'categoria_tipo' => 'imagem',
            'default' => 1,
            'parent' => 0,
            'ativado' => 1,
            'order' => 0
        ]);

        $childCategory = factory(Categoria::class)->create([
            'titulo' => 'Child Category',
            'categoria_tipo' => 'imagem',
            'default' => 0,
            'parent' => $rootCategory->id,
            'ativado' => 1,
            'order' => 1
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('Imagem.index'));

        $tree = $response->viewData('tree');

        // The view expects these exact keys
        $this->assertArrayHasKey('id', $tree);
        $this->assertArrayHasKey('titulo', $tree);
        $this->assertArrayHasKey('childreen', $tree); // Note: 'childreen' not 'children'

        // First level children should have these keys
        if (count($tree['childreen']) > 0) {
            $child = $tree['childreen'][0];
            $this->assertArrayHasKey('id', $child);
            $this->assertArrayHasKey('titulo', $child);
            $this->assertArrayHasKey('childreen', $child);
            $this->assertArrayHasKey('ativado', $child);
        }
    }
}
