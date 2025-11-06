<?php

namespace App\Http\Controllers\Administrator;
use App\Http\Controllers\Administrator\Imagem;

use App\Http\Controllers\Controller;
use App\Http\Requests\Administrator\CategoriaRequest;
use App\Model\Categoria;
use App\Model\Tag;
use App\Model\Artigo;
use App\Model\Conteudo;
use App\Services\CategoriaService;
use DB;
use Exception;
use Session;
use App\Helpers\Helpers;

class CategoriaController extends Controller {
	
	protected $categoriaService;
	
	public function __construct(CategoriaService $categoriaService) {
		$this->categoriaService = $categoriaService;
		$this->middleware('auth');
	}
	/**
	 * Display a listing of the resource.
	 * Artigos
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		try {
			$tree = $this->categoriaService->getCategoriesTreeLegacy('artigo');
			$treeDoc = $this->categoriaService->getCategoriesTreeLegacy('documento');
			$treeImg = $this->categoriaService->getCategoriesTreeLegacy('imagem');
			$treeLink = $this->categoriaService->getCategoriesTreeLegacy('link');
			$treeFaq = $this->categoriaService->getCategoriesTreeLegacy('faq');
			$treeEvento = $this->categoriaService->getCategoriesTreeLegacy('evento');

			return view('Administrator.Artigos.categoria')
				->withTreedoc($treeDoc)
				->withTreelink($treeLink)
				->withTree($tree)
				->withTreeImg($treeImg)
				->withTreefaq($treeFaq)
				->withTreeevento($treeEvento);
				
		} catch (Exception $e) {
			Session::flash('error', 'Error loading categories: ' . $e->getMessage());
			return redirect()->back();
		}
	}

	/**
	 * Show the form for creating a new resource.
	 * ARTIGOS
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create($tipo) {
		try {
			// Map old tipo names to new ones
			$tipoMap = [
				'art' => 'artigo',
				'documento' => 'documento',
				'faq' => 'faq',
				'evento' => 'evento',
				'link' => 'link'
			];
			
			$categoryType = $tipoMap[$tipo] ?? $tipo;
			
			$tag = Tag::orderBy('name')->pluck('name', 'id');
			$items = $this->categoriaService->getParentCategories($categoryType);
			$type = $tipo;

			return view('Administrator.Artigos.cat_form', compact('items'))
				->withTag($tag)
				->withType($type)
				->withCat($items);
				
		} catch (Exception $e) {
			Session::flash('error', 'Error loading create form: ' . $e->getMessage());
			return redirect()->back();
		}
	}
	
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function createImg() {
		try {
			$type = "imagem";
			$tag = Tag::orderBy('name')->pluck('name', 'id');
			$items = $this->categoriaService->getParentCategories('imagem');

			
			return view('Administrator.Artigos.cat_form',compact('items'))
				->withTag($tag)
				->withType($type)
				->withCat($items);
				
		} catch (Exception $e) {
			Session::flash('error', 'Error loading create form: ' . $e->getMessage());
			return redirect()->back();
		}
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(CategoriaRequest $request) {
		try {
			// Map request type to category type
			$tipoMap = [
				'img' => 'imagem',
				'documento' => 'documento',
				'faq' => 'faq',
				'evento' => 'evento',
				'link' => 'link',
				'art' => 'artigo'
			];
			
			$categoryType = $tipoMap[$request->type] ?? $request->type;
			
			// Prepare data for service
			$data = [
				'titulo' => $request->tituloPT ?? $request->titulo ?? $request->categoria_nome,
				'categoria_tipo' => $categoryType,
				'parent' => $request->parent ?? 0,
				'ativado' => $request->has('ativado') ? 1 : 0,
				'default' => $request->default ?? 0
			];
			
			$categoria = $this->categoriaService->createCategory($data);
			
			// Handle additional content and tags (keeping original helper calls for now)
			Helpers::guardarConteudos($request, $categoria);
			Helpers::salvarTags($request, $categoria);
			Helpers::criarSlug($request, $categoria);
			
			Session::flash('success', 'Category created successfully!');
			
			if ($request->type == "img") {
				return redirect()->route('Imagem.index');
			}
			
			return redirect()->route('Categoria.index', $categoria->id);
			
		} catch (Exception $e) {
			Session::flash('error', 'Error creating category: ' . $e->getMessage());
			return redirect()->back()->withInput();
		}
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */

	public function show($id) {

		// if($type == "midia"){
		//     return view('Administrator.Artigo.Midia')->withMenu($menu)->withBoinfo($boinfo);

		// }elseif($type == "doc"){
		//     return view('Administrator.Documento.documento')->withMenu($menu)->withBoinfo($boinfo);

		// }else{
		//      return view('Administrator.Artigos.Categoria')->withMenu($menu)->withBoinfo($boinfo);
		// }

	}

	/**
	 * Show the form for editing the specified resource.
	 * Categoria
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id) {
		$cat = Categoria::find($id);

		$conteudoPT = $cat->conteudos;
		
		if (count($conteudoPT) != 0) {

			foreach ($conteudoPT as $conteudo) {

				if ($conteudo->languages->tag == "pt") {
					$cont['tituloPT']   = $conteudo->titulo;
				} else {
					$cont['tituloEN']   = $conteudo->titulo;
				}

			}
		} else {
			$cont['tituloPT']   = $cat->titulo;
			$cont['tituloEN']   = $cat->titulo;
		}

		$tag = Tag::orderBy('name')->pluck('name', 'id');

		$items = $this->categoriaService->getParentCategories('artigo');

		return view('Administrator.Artigos.cat_form', compact('items'))->withTag($tag)->withType("art")->withCategoria($cat)->withItems($items)->withContent($cont);
	}

	/************Editar Categoria Documentacao************/
	public function editCat($id, $type) {
		try {
			$cat = $this->categoriaService->getCategoryById($id);
			
			$conteudoPT = $cat->conteudos;
			
			if (count($conteudoPT) != 0) {
				foreach ($conteudoPT as $conteudo) {
					if ($conteudo->languages->tag == "pt") {
						$cont['tituloPT'] = $conteudo->titulo;
					} else {
						$cont['tituloEN'] = $conteudo->titulo;
					}
				}
			} else {
				$cont['tituloPT'] = $cat->titulo;
				$cont['tituloEN'] = $cat->titulo;
			}

			$tag = Tag::orderBy('name')->pluck('name', 'id');
			$items = $this->categoriaService->getParentCategories($type);

			return view('Administrator.Artigos.cat_form')
				->withTag($tag)
				->withType($type)
				->withCategoria($cat)
				->withItems($items)
				->withContent($cont);
				
		} catch (Exception $e) {
			Session::flash('error', 'Error loading edit form: ' . $e->getMessage());
			return redirect()->back();
		}
	}

	
	/**
	 * Update the specified resource in storage.
	 * Categoria
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(CategoriaRequest $request, $id) {
		try {
			$cat = $this->categoriaService->getCategoryById($id);

			if ($cat->default == 0 && ($id != $request->parent)) {
				$data = [
					'titulo' => $request->tituloPT,
					'parent' => $request->parent,
					'ativado' => $request->ativado == 'on' ? 1 : 0,
				];

				$updatedCategory = $this->categoriaService->updateCategory($id, $data);

				// Handle content and tags
				$conteudos = $cat->conteudos;
				if (count($conteudos) != 0) {
					Helpers::atualizarConteudo($conteudos, $request, $cat);
				} else {
					Helpers::guardarConteudos($request, $cat);
				}
				Helpers::salvarTags($request, $cat);

				Session::flash('success', 'Category updated successfully!');

				if ($request->type == "img") {
					return redirect()->route('Imagem.index');
				}

				return redirect()->route('Categoria.index', $cat->id);
			} else {
				Session::flash('warning', 'Root category cannot be edited or invalid parent reference!');
				return redirect()->back();
			}

		} catch (Exception $e) {
			Session::flash('error', 'Error updating category: ' . $e->getMessage());
			return redirect()->back()->withInput();
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
			$cat = $this->categoriaService->getCategoryById($id);
			
			// Detach tags if they exist
			if (isset($cat->tags)) {
				$cat->tags()->detach();
			}

			// Delete associated content
			$cat->conteudos()->delete();

			// Use service to delete category
			$this->categoriaService->deleteCategory($id);

			Session::flash('success', 'Category deleted successfully!');

			if ($cat->imagem == 1) {
				return redirect()->route('Imagem.index');
			}

			return redirect()->route('Categoria.index');

		} catch (Exception $e) {
			Session::flash('error', 'Error deleting category: ' . $e->getMessage());
			return redirect()->back();
		}
	}

	/**
	 * Remove specified category (alternative method)
	 */
	public function destroyMe($id) {
		return $this->destroy($id);
	}

	/**
	 * Deactivate category
	 *
	 */
	public function despublicar($id, $type) {
		try {
			$this->categoriaService->updateCategoryStatus($id, 0);

			Session::flash('success', 'Category unpublished successfully!');

			if ($type == "imagem") {
				return redirect()->route('Imagem.index');
			}

			return redirect()->route('Categoria.index', $id);

		} catch (Exception $e) {
			Session::flash('error', 'Error unpublishing category: ' . $e->getMessage());
			return redirect()->back();
		}
	}

	/**
	 * Activate category
	 *
	 */
	public function publicar($id, $type) {
		try {
			$this->categoriaService->updateCategoryStatus($id, 1);

			Session::flash('success', 'Category published successfully!');

			if ($type == "imagem") {
				return redirect()->route('Imagem.index');
			}

			return redirect()->route('Categoria.index', $id);

		} catch (Exception $e) {
			Session::flash('error', 'Error publishing category: ' . $e->getMessage());
			return redirect()->back();
		}
	}

	/**************************************/

	/**
	 * get last number accordingly to order in the same level.
	 *
	 * @param  $item to be reordered
	 * @return \Illuminate\Http\Response
	 */
	public function upOrder($id) {
		try {
			$this->categoriaService->moveUp($id);
			Session::flash('success', 'Category moved up successfully!');
			return redirect()->route('Categoria.index');

		} catch (Exception $e) {
			Session::flash('error', 'Error moving category: ' . $e->getMessage());
			return redirect()->back();
		}
	}

	/**
	 * order down
	 *
	 * @param  $item to be reordered
	 * @return \Illuminate\Http\Response
	 */
	public function downOrder($id) {
		try {
			$this->categoriaService->moveDown($id);
			Session::flash('success', 'Category moved down successfully!');
			return redirect()->route('Categoria.index');

		} catch (Exception $e) {
			Session::flash('error', 'Error moving category: ' . $e->getMessage());
			return redirect()->back();
		}
	}

	
	 /**
     * @param $title
     * @param int $id
     * @return ok
     * @throws \Exception
     */
    public function createSlugAll()
    {
		try {
			$updated = $this->categoriaService->generateSlugsForAll();
			
			Session::flash('success', "Slugs generated for {$updated} categories!");
			return redirect()->route('Categoria.index');
			
		} catch (Exception $e) {
			Session::flash('error', 'Error generating slugs: ' . $e->getMessage());
			return redirect()->back();
		}
    }
}
