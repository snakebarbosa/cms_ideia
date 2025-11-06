<?php

namespace Tests\Unit;

use App\Http\Controllers\Administrator\CategoriaController;
use App\Model\Categoria;
use App\Services\CategoriaService;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Mockery;
use Tests\TestCase;

class CategoriaControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $controller;
    protected $categoriaService;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->categoriaService = Mockery::mock(CategoriaService::class);
        $this->controller = new CategoriaController($this->categoriaService);
        $this->user = User::factory()->create();
        Auth::login($this->user);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function index_returns_view_with_category_trees()
    {
        $mockTree = [
            [
                'categoria' => (object)['id' => 1, 'titulo' => 'Test'],
                'subcategorias' => []
            ]
        ];

        $this->categoriaService->shouldReceive('getCategoriesTree')
            ->with('artigo')->once()->andReturn($mockTree);
        $this->categoriaService->shouldReceive('getCategoriesTree')
            ->with('documento')->once()->andReturn($mockTree);
        $this->categoriaService->shouldReceive('getCategoriesTree')
            ->with('imagem')->once()->andReturn($mockTree);
        $this->categoriaService->shouldReceive('getCategoriesTree')
            ->with('link')->once()->andReturn($mockTree);
        $this->categoriaService->shouldReceive('getCategoriesTree')
            ->with('faq')->once()->andReturn($mockTree);
        $this->categoriaService->shouldReceive('getCategoriesTree')
            ->with('evento')->once()->andReturn($mockTree);

        $response = $this->controller->index();

        $this->assertEquals('Administrator.Artigos.categoria', $response->getName());
        $this->assertArrayHasKey('tree', $response->getData());
    }

    /** @test */
    public function index_handles_service_exception()
    {
        $this->categoriaService->shouldReceive('getCategoriesTree')
            ->andThrow(new Exception('Service error'));

        Session::shouldReceive('flash')->with('error', Mockery::any())->once();

        $response = $this->controller->index();

        $this->assertEquals(302, $response->getStatusCode());
    }

    /** @test */
    public function create_returns_view_for_artigo_type()
    {
        $mockCategories = collect([
            (object)['id' => 1, 'titulo' => 'Parent Category']
        ]);

        $this->categoriaService->shouldReceive('getParentCategories')
            ->with('artigo')->once()->andReturn($mockCategories);

        $response = $this->controller->create('art');

        $this->assertEquals('Administrator.Artigos.cat_form', $response->getName());
        $this->assertArrayHasKey('type', $response->getData());
        $this->assertEquals('art', $response->getData()['type']);
    }

    /** @test */
    public function create_returns_view_for_documento_type()
    {
        $mockCategories = collect([]);

        $this->categoriaService->shouldReceive('getParentCategories')
            ->with('documento')->once()->andReturn($mockCategories);

        $response = $this->controller->create('documento');

        $this->assertEquals('Administrator.Artigos.cat_form', $response->getName());
        $this->assertEquals('documento', $response->getData()['type']);
    }

    /** @test */
    public function create_handles_service_exception()
    {
        $this->categoriaService->shouldReceive('getParentCategories')
            ->andThrow(new Exception('Service error'));

        Session::shouldReceive('flash')->with('error', Mockery::any())->once();

        $response = $this->controller->create('art');

        $this->assertEquals(302, $response->getStatusCode());
    }

    /** @test */
    public function createImg_returns_correct_view()
    {
        $mockCategories = collect([]);

        $this->categoriaService->shouldReceive('getParentCategories')
            ->with('imagem')->once()->andReturn($mockCategories);

        $response = $this->controller->createImg();

        $this->assertEquals('Administrator.Artigos.addcategoria', $response->getName());
        $this->assertEquals('img', $response->getData()['type']);
    }

    /** @test */
    public function store_creates_category_successfully()
    {
        $request = new Request([
            'tituloPT' => 'Test Category',
            'ativado' => 'on',
            'parent' => null,
            'type' => 'art'
        ]);

        $mockCategory = new Categoria([
            'id' => 1,
            'titulo' => 'Test Category'
        ]);

        $this->categoriaService->shouldReceive('createCategory')
            ->with(Mockery::on(function ($data) {
                return $data['titulo'] === 'Test Category' &&
                       $data['categoria_tipo'] === 'artigo' &&
                       $data['order'] === 1;
            }))
            ->once()
            ->andReturn($mockCategory);

        Session::shouldReceive('flash')->with('success', Mockery::any())->once();

        $response = $this->controller->store($request);

        $this->assertEquals(302, $response->getStatusCode());
    }

    /** @test */
    public function store_creates_subcategory_successfully()
    {
        $request = new Request([
            'tituloPT' => 'Test Subcategory',
            'ativado' => 'on',
            'parent' => 1,
            'type' => 'art'
        ]);

        $mockCategory = new Categoria([
            'id' => 2,
            'titulo' => 'Test Subcategory'
        ]);

        $this->categoriaService->shouldReceive('createCategory')
            ->with(Mockery::on(function ($data) {
                return $data['titulo'] === 'Test Subcategory' &&
                       $data['order'] === 2 &&
                       $data['parent'] === 1;
            }))
            ->once()
            ->andReturn($mockCategory);

        Session::shouldReceive('flash')->with('success', Mockery::any())->once();

        $response = $this->controller->store($request);

        $this->assertEquals(302, $response->getStatusCode());
    }

    /** @test */
    public function store_redirects_to_imagem_index_for_img_type()
    {
        $request = new Request([
            'tituloPT' => 'Test Image Category',
            'ativado' => 'on',
            'parent' => null,
            'type' => 'img'
        ]);

        $mockCategory = new Categoria(['id' => 1]);

        $this->categoriaService->shouldReceive('createCategory')->andReturn($mockCategory);
        Session::shouldReceive('flash')->with('success', Mockery::any())->once();

        $response = $this->controller->store($request);

        $this->assertStringContains('Imagem.index', $response->getTargetUrl());
    }

    /** @test */
    public function store_handles_service_exception()
    {
        $request = new Request([
            'tituloPT' => 'Test Category',
            'type' => 'art'
        ]);

        $this->categoriaService->shouldReceive('createCategory')
            ->andThrow(new Exception('Service error'));

        Session::shouldReceive('flash')->with('error', Mockery::any())->once();

        $response = $this->controller->store($request);

        $this->assertEquals(302, $response->getStatusCode());
    }

    /** @test */
    public function edit_returns_view_with_category_data()
    {
        $mockCategory = new Categoria([
            'id' => 1,
            'titulo' => 'Test Category',
            'categoria_tipo' => 'artigo',
            'titulo' => 'Test Category'
        ]);
        $mockCategory->conteudos = collect([]);

        $mockParentCategories = collect([]);

        $this->categoriaService->shouldReceive('getCategoryById')
            ->with(1)->once()->andReturn($mockCategory);
        $this->categoriaService->shouldReceive('getParentCategories')
            ->with('artigo')->once()->andReturn($mockParentCategories);

        $response = $this->controller->edit(1);

        $this->assertEquals('Administrator.Artigos.cat_form', $response->getName());
        $this->assertArrayHasKey('categoria', $response->getData());
    }

    /** @test */
    public function edit_handles_service_exception()
    {
        $this->categoriaService->shouldReceive('getCategoryById')
            ->andThrow(new Exception('Category not found'));

        Session::shouldReceive('flash')->with('error', Mockery::any())->once();

        $response = $this->controller->edit(1);

        $this->assertEquals(302, $response->getStatusCode());
    }

    /** @test */
    public function update_updates_category_successfully()
    {
        $request = new Request([
            'tituloPT' => 'Updated Category',
            'ativado' => 'on',
            'parent' => null,
            'type' => 'art'
        ]);

        $mockCategory = new Categoria([
            'id' => 1,
            'default' => 0
        ]);
        $mockCategory->conteudos = collect([]);

        $mockUpdatedCategory = new Categoria([
            'id' => 1,
            'titulo' => 'Updated Category'
        ]);

        $this->categoriaService->shouldReceive('getCategoryById')
            ->with(1)->once()->andReturn($mockCategory);
        $this->categoriaService->shouldReceive('updateCategory')
            ->with(1, Mockery::any())->once()->andReturn($mockUpdatedCategory);

        Session::shouldReceive('flash')->with('success', Mockery::any())->once();

        $response = $this->controller->update($request, 1);

        $this->assertEquals(302, $response->getStatusCode());
    }

    /** @test */
    public function update_prevents_updating_default_category()
    {
        $request = new Request([
            'tituloPT' => 'Updated Category',
            'parent' => null,
            'type' => 'art'
        ]);

        $mockCategory = new Categoria([
            'id' => 1,
            'default' => 1 // Default category
        ]);

        $this->categoriaService->shouldReceive('getCategoryById')
            ->with(1)->once()->andReturn($mockCategory);

        Session::shouldReceive('flash')->with('warning', Mockery::any())->once();

        $response = $this->controller->update($request, 1);

        $this->assertEquals(302, $response->getStatusCode());
    }

    /** @test */
    public function destroy_deletes_category_successfully()
    {
        $mockCategory = new Categoria([
            'id' => 1,
            'imagem' => 0
        ]);
        $mockCategory->tags = collect([]);
        $mockCategory->shouldReceive('conteudos')->andReturn(collect([]));

        $this->categoriaService->shouldReceive('getCategoryById')
            ->with(1)->once()->andReturn($mockCategory);
        $this->categoriaService->shouldReceive('deleteCategory')
            ->with(1)->once()->andReturn(true);

        Session::shouldReceive('flash')->with('success', Mockery::any())->once();

        $response = $this->controller->destroy(1);

        $this->assertEquals(302, $response->getStatusCode());
    }

    /** @test */
    public function publicar_publishes_category_successfully()
    {
        $mockCategory = new Categoria(['id' => 1]);

        $this->categoriaService->shouldReceive('updateCategoryStatus')
            ->with(1, 1)->once()->andReturn($mockCategory);

        Session::shouldReceive('flash')->with('success', Mockery::any())->once();

        $response = $this->controller->publicar(1, 'artigo');

        $this->assertEquals(302, $response->getStatusCode());
    }

    /** @test */
    public function despublicar_unpublishes_category_successfully()
    {
        $mockCategory = new Categoria(['id' => 1]);

        $this->categoriaService->shouldReceive('updateCategoryStatus')
            ->with(1, 0)->once()->andReturn($mockCategory);

        Session::shouldReceive('flash')->with('success', Mockery::any())->once();

        $response = $this->controller->despublicar(1, 'artigo');

        $this->assertEquals(302, $response->getStatusCode());
    }

    /** @test */
    public function upOrder_moves_category_up_successfully()
    {
        $this->categoriaService->shouldReceive('moveUp')
            ->with(1)->once()->andReturn(true);

        Session::shouldReceive('flash')->with('success', Mockery::any())->once();

        $response = $this->controller->upOrder(1);

        $this->assertEquals(302, $response->getStatusCode());
    }

    /** @test */
    public function downOrder_moves_category_down_successfully()
    {
        $this->categoriaService->shouldReceive('moveDown')
            ->with(1)->once()->andReturn(true);

        Session::shouldReceive('flash')->with('success', Mockery::any())->once();

        $response = $this->controller->downOrder(1);

        $this->assertEquals(302, $response->getStatusCode());
    }

    /** @test */
    public function createSlugAll_generates_slugs_successfully()
    {
        $this->categoriaService->shouldReceive('generateSlugsForAll')
            ->once()->andReturn(5);

        Session::shouldReceive('flash')->with('success', Mockery::any())->once();

        $response = $this->controller->createSlugAll();

        $this->assertEquals(302, $response->getStatusCode());
    }

    /** @test */
    public function createSlugAll_handles_service_exception()
    {
        $this->categoriaService->shouldReceive('generateSlugsForAll')
            ->andThrow(new Exception('Slug generation failed'));

        Session::shouldReceive('flash')->with('error', Mockery::any())->once();

        $response = $this->controller->createSlugAll();

        $this->assertEquals(302, $response->getStatusCode());
    }

    /** @test */
    public function destroyMe_calls_destroy_method()
    {
        $mockCategory = new Categoria([
            'id' => 1,
            'imagem' => 0
        ]);
        $mockCategory->tags = collect([]);
        $mockCategory->shouldReceive('conteudos')->andReturn(collect([]));

        $this->categoriaService->shouldReceive('getCategoryById')
            ->with(1)->once()->andReturn($mockCategory);
        $this->categoriaService->shouldReceive('deleteCategory')
            ->with(1)->once()->andReturn(true);

        Session::shouldReceive('flash')->with('success', Mockery::any())->once();

        $response = $this->controller->destroyMe(1);

        $this->assertEquals(302, $response->getStatusCode());
    }
}