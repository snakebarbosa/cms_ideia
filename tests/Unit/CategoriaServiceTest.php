<?php

namespace Tests\Unit;

use App\Model\Categoria;
use App\Services\CategoriaService;
use App\User;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class CategoriaServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $categoriaService;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->categoriaService = new CategoriaService();
        $this->user = User::factory()->create();
        Auth::login($this->user);
    }

    /** @test */
    public function it_can_get_categories_tree()
    {
        $parentCategory = Categoria::factory()->create([
            'categoria_tipo' => 'artigo',
            'parent' => 0,
            'ativado' => 1,
            'order' => 1
        ]);

        $childCategory = Categoria::factory()->create([
            'categoria_tipo' => 'artigo',
            'parent' => $parentCategory->id,
            'ativado' => 1,
            'order' => 2
        ]);

        $tree = $this->categoriaService->getCategoriesTree('artigo');

        $this->assertIsArray($tree);
        $this->assertCount(1, $tree);
        $this->assertEquals($parentCategory->id, $tree[0]['categoria']->id);
        $this->assertCount(1, $tree[0]['subcategorias']);
    }

    /** @test */
    public function it_can_build_tree_structure()
    {
        $categories = Categoria::factory()->count(2)->create([
            'categoria_tipo' => 'artigo',
            'order' => 1,
            'ativado' => 1
        ]);

        $tree = $this->categoriaService->buildTree($categories, 'artigo');

        $this->assertIsArray($tree);
        $this->assertCount(2, $tree);
        $this->assertArrayHasKey('categoria', $tree[0]);
        $this->assertArrayHasKey('subcategorias', $tree[0]);
    }

    /** @test */
    public function it_can_get_all_categories_for_management()
    {
        Categoria::factory()->count(3)->create([
            'categoria_tipo' => 'documento'
        ]);

        $categories = $this->categoriaService->getAllCategoriesForManagement('documento');

        $this->assertEquals(3, $categories->count());
    }

    /** @test */
    public function it_can_create_a_category()
    {
        $data = [
            'titulo' => 'Test Category',
            'categoria_tipo' => 'artigo',
            'order' => 1,
            'parent' => null,
            'ativado' => 1
        ];

        $categoria = $this->categoriaService->createCategory($data);

        $this->assertInstanceOf(Categoria::class, $categoria);
        $this->assertEquals('Test Category', $categoria->titulo);
        $this->assertEquals('artigo', $categoria->categoria_tipo);
        $this->assertEquals(1, $categoria->order);
        $this->assertNotNull($categoria->slug);
    }

    /** @test */
    public function it_can_create_a_subcategory()
    {
        $parentCategory = Categoria::factory()->create([
            'categoria_tipo' => 'artigo',
            'order' => 1
        ]);

        $data = [
            'titulo' => 'Test Subcategory',
            'categoria_tipo' => 'artigo',
            'order' => 2,
            'parent' => $parentCategory->id,
            'ativado' => 1
        ];

        $categoria = $this->categoriaService->createCategory($data);

        $this->assertInstanceOf(Categoria::class, $categoria);
        $this->assertEquals('Test Subcategory', $categoria->titulo);
        $this->assertEquals(2, $categoria->order);
        $this->assertEquals($parentCategory->id, $categoria->parent);
    }

    /** @test */
    public function it_validates_category_data_on_creation()
    {
        $data = [
            'titulo' => '', // Invalid: empty name
            'categoria_tipo' => 'invalid_type', // Invalid: not in allowed types
            'order' => 3, // Invalid: level too high
        ];

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Validation failed');

        $this->categoriaService->createCategory($data);
    }

    /** @test */
    public function it_prevents_duplicate_category_names()
    {
        Categoria::factory()->create([
            'titulo' => 'Duplicate Name',
            'categoria_tipo' => 'artigo',
            'parent' => null
        ]);

        $data = [
            'titulo' => 'Duplicate Name',
            'categoria_tipo' => 'artigo',
            'order' => 1,
            'parent' => null,
            'ativado' => 1
        ];

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Category name already exists');

        $this->categoriaService->createCategory($data);
    }

    /** @test */
    public function it_validates_category_levels()
    {
        // Test level 1 with parent (should fail)
        $data = [
            'titulo' => 'Invalid Level 1',
            'categoria_tipo' => 'artigo',
            'order' => 1,
            'parent' => 999, // Should not have parent
            'ativado' => 1
        ];

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Invalid category level');

        $this->categoriaService->createCategory($data);
    }

    /** @test */
    public function it_can_update_a_category()
    {
        $categoria = Categoria::factory()->create([
            'titulo' => 'Original Name',
            'categoria_tipo' => 'artigo'
        ]);

        $data = [
            'titulo' => 'Updated Name',
            'categoria_tipo' => 'artigo',
            'order' => 1,
            'parent' => null,
            'ativado' => 1
        ];

        $updatedCategory = $this->categoriaService->updateCategory($categoria->id, $data);

        $this->assertEquals('Updated Name', $updatedCategory->titulo);
        $this->assertNotEquals($categoria->slug, $updatedCategory->slug);
    }

    /** @test */
    public function it_can_delete_a_category()
    {
        $categoria = Categoria::factory()->create();

        $result = $this->categoriaService->deleteCategory($categoria->id);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('categorias', ['id' => $categoria->id]);
    }

    /** @test */
    public function it_prevents_deleting_category_with_subcategories()
    {
        $parentCategory = Categoria::factory()->create([
            'order' => 1
        ]);

        Categoria::factory()->create([
            'order' => 2,
            'parent' => $parentCategory->id
        ]);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Cannot delete category with subcategories');

        $this->categoriaService->deleteCategory($parentCategory->id);
    }

    /** @test */
    public function it_can_update_category_status()
    {
        $categoria = Categoria::factory()->create(['ativado' => 0]);

        $updatedCategory = $this->categoriaService->updateCategoryStatus($categoria->id, 1);

        $this->assertEquals(1, $updatedCategory->ativado);
    }

    /** @test */
    public function it_can_move_category_up()
    {
        $categoria1 = Categoria::factory()->create([
            'categoria_tipo' => 'artigo',
            'parent' => null,
            'order' => 1
        ]);

        $categoria2 = Categoria::factory()->create([
            'categoria_tipo' => 'artigo',
            'parent' => null,
            'order' => 2
        ]);

        $result = $this->categoriaService->moveUp($categoria2->id);

        $this->assertTrue($result);

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
            'parent' => null,
            'order' => 1
        ]);

        $categoria2 = Categoria::factory()->create([
            'categoria_tipo' => 'artigo',
            'parent' => null,
            'order' => 2
        ]);

        $result = $this->categoriaService->moveDown($categoria1->id);

        $this->assertTrue($result);

        $categoria1->refresh();
        $categoria2->refresh();

        $this->assertEquals(2, $categoria1->order);
        $this->assertEquals(1, $categoria2->order);
    }

    /** @test */
    public function it_handles_move_up_when_already_first()
    {
        $categoria = Categoria::factory()->create([
            'categoria_tipo' => 'artigo',
            'order' => 1
        ]);

        $result = $this->categoriaService->moveUp($categoria->id);

        $this->assertTrue($result); // Should not fail
        
        $categoria->refresh();
        $this->assertEquals(1, $categoria->order); // Should remain the same
    }

    /** @test */
    public function it_handles_move_down_when_already_last()
    {
        $categoria = Categoria::factory()->create([
            'categoria_tipo' => 'artigo',
            'order' => 1
        ]);

        $result = $this->categoriaService->moveDown($categoria->id);

        $this->assertTrue($result); // Should not fail
        
        $categoria->refresh();
        $this->assertEquals(1, $categoria->order); // Should remain the same
    }

    /** @test */
    public function it_can_generate_slugs_for_all_categories()
    {
        Categoria::factory()->count(3)->create([
            'titulo' => 'Test Category',
            'slug' => null
        ]);

        $updated = $this->categoriaService->generateSlugsForAll();

        $this->assertEquals(3, $updated);

        $categories = Categoria::whereNotNull('slug')->get();
        $this->assertEquals(3, $categories->count());
    }

    /** @test */
    public function it_can_get_category_by_id()
    {
        $categoria = Categoria::factory()->create();

        $foundCategory = $this->categoriaService->getCategoryById($categoria->id);

        $this->assertEquals($categoria->id, $foundCategory->id);
    }

    /** @test */
    public function it_throws_exception_for_non_existent_category()
    {
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

        $this->categoriaService->getCategoryById(999);
    }

    /** @test */
    public function it_can_get_parent_categories()
    {
        Categoria::factory()->count(2)->create([
            'categoria_tipo' => 'artigo',
            'order' => 1
        ]);

        Categoria::factory()->create([
            'categoria_tipo' => 'artigo',
            'order' => 2
        ]);

        $parentCategories = $this->categoriaService->getParentCategories('artigo');

        $this->assertEquals(2, $parentCategories->count());
        $parentCategories->each(function ($category) {
            $this->assertEquals(1, $category->order);
        });
    }

    /** @test */
    public function it_generates_unique_slugs()
    {
        Categoria::factory()->create([
            'titulo' => 'Test Category',
            'slug' => 'test-category'
        ]);

        $data = [
            'titulo' => 'Test Category', // Same name
            'categoria_tipo' => 'artigo',
            'order' => 1,
            'parent' => null,
            'ativado' => 1
        ];

        $categoria = $this->categoriaService->createCategory($data);

        $this->assertNotEquals('test-category', $categoria->slug);
        $this->assertStringStartsWith('test-category-', $categoria->slug);
    }

    /** @test */
    public function it_calculates_correct_order_for_new_categories()
    {
        Categoria::factory()->create([
            'categoria_tipo' => 'artigo',
            'parent' => null,
            'order' => 5
        ]);

        $data = [
            'titulo' => 'New Category',
            'categoria_tipo' => 'artigo',
            'order' => 1,
            'parent' => null,
            'ativado' => 1
        ];

        $categoria = $this->categoriaService->createCategory($data);

        $this->assertEquals(6, $categoria->order);
    }
}