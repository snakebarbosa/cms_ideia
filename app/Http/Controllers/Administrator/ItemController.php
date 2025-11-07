<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Http\Requests\Administrator\CheckRequest;
use App\Http\Requests\Administrator\ItemRequest;
use App\Model\Categoria;
use App\Model\Item;
use App\Model\Tag;
use App\Model\Tipo;
use App\Helpers\Helpers;
use App\Model\Artigo;
use App\Model\Documento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ItemController extends Controller {
	
	const MAX_LEVEL_DEPTH = 2;
	
	public function __construct() {
		$this->middleware('auth');
	}

	/**
	 * Display a listing of the resource.
	 */
	public function index() {
		$menus = Tipo::where('ativado', 1)
			->where('menu', 1)
			->get();

		$trees = [];
		
		if ($menus->count() > 0) {
			foreach ($menus as $menu) {
				$trees[] = $this->tree($menu->id);
			}
		}

		$items = Item::all();

		return view('Administrator.Menu.item', compact('trees', 'items'));
	}

	/**
	 * Show the form for creating a new resource.
	 */
	public function create() {
		return view('Administrator.Menu.item_form', $this->getFormData());
	}

	/**
	 * Get all items from a menu type
	 */
	public function getOptionsMenu(Request $request) {
		$this->validate($request, [
			'idTipo' => 'required',
		]);

		return Item::where('idTipo', $request->idTipo)
			->pluck('titulo', 'id');
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(ItemRequest $request) {
		$item = new Item([
			'titulo'   => $request->tituloPT,
			'idTipo'   => $request->idTipo,
			'ativado'  => $request->ativado == 'on' ? 1 : 0,
			'parent'   => $request->parent,
			'order'    => $this->getLastOrder($request->idTipo) + 1,
			'url'      => $request->url,
		]);

		if (is_numeric($request->idimagem)) {
			$item->idImagem = $request->idimagem;
		}

		// Validate constraints
		if ($this->checkNivel($item->parent)) {
			Session::flash('warning', 'Limite máximo de níveis atingido.');
			return redirect()->route('Item.index');
		}

		$item->save();
		
		Helpers::guardarConteudos($request, $item);
		Helpers::salvarTags($request, $item);

		Session::flash('success', 'Item criado com sucesso!');
		return redirect()->route('Item.index', $item->id);
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit($id) {
		$item = Item::findOrFail($id);
		$content = $this->getContentArray($item);

		return view('Administrator.Menu.item_form', array_merge(
			$this->getFormData(),
			[
				'id' => $item,
				'content' => $content,
			]
		));
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(ItemRequest $request, $id) {
		$item = Item::findOrFail($id);

		if ($item->default == 1) {
			Session::flash('warning', 'A pasta raiz não pode ser editada!');
			return redirect()->route('Item.index', $item->id);
		}

		if ($item->idTipo != $request->idTipo) {
			Session::flash('warning', 'Não é possível mover um item de menu para outro menu.');
			return redirect()->route('Item.index', $item->id);
		}

		if ($this->checkNivel($request->parent)) {
			Session::flash('warning', 'Limite máximo de níveis atingido.');
			return redirect()->route('Item.index', $item->id);
		}

		$item->update([
			'titulo'  => $request->tituloPT,
			'ativado' => $request->ativado == 'on' ? 1 : 0,
			'parent'  => $request->parent,
			'url'     => $request->url,
		]);

		if (is_numeric($request->idimagem)) {
			$item->idImagem = $request->idimagem;
			$item->save();
		}

		Helpers::salvarTags($request, $item);
		Helpers::atualizarConteudo($item->conteudos, $request, $item);

		Session::flash('success', 'Item atualizado com sucesso!');
		return redirect()->route('Item.index', $item->id);
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy($id) {
		$item = Item::findOrFail($id);

		$item->tags()->detach();
		$item->conteudos()->delete();
		$item->delete();

		Session::flash('success', 'Item removido com sucesso!');
		return redirect()->route('Item.index');
	}

	/**
	 * Bulk publish items
	 */
	public function publicarCheck(CheckRequest $request) {
		Item::whereIn('id', $request->check)->update(['ativado' => 1]);
		Session::flash('success', 'Itens publicados com sucesso!');
		return redirect()->route('Item.index');
	}

	/**
	 * Bulk unpublish items
	 */
	public function despublicarCheck(CheckRequest $request) {
		Item::whereIn('id', $request->check)->update(['ativado' => 0]);
		Session::flash('success', 'Itens despublicados com sucesso!');
		return redirect()->route('Item.index');
	}

	/**
	 * Bulk remove items
	 */
	public function removerCheck(CheckRequest $request) {
		foreach ($request->check as $id) {
			$item = Item::find($id);
			if ($item) {
				$item->tags()->detach();
				$item->conteudos()->delete();
			}
		}
		
		Item::whereIn('id', $request->check)->delete();
		Session::flash('success', 'Itens removidos com sucesso!');
		return redirect()->route('Item.index');
	}

	/**
	 * Unpublish single item
	 */
	public function despublicar($id) {
		$item = Item::findOrFail($id);
		$item->update(['ativado' => 0]);

		Session::flash('success', 'Item despublicado!');
		return redirect()->route('Item.index');
	}

	/**
	 * Publish single item
	 */
	public function publicar($id) {
		$item = Item::findOrFail($id);
		$item->update(['ativado' => 1]);

		Session::flash('success', 'Item publicado!');
		return redirect()->route('Item.index', $item->id);
	}

	/**
	 * Move item order up
	 */
	public function upOrder($id) {
		$item = Item::findOrFail($id);

		$next = Item::where('order', '>', $item->order)
			->where('id', '<>', $item->id)
			->where('parent', $item->parent)
			->orderBy('order', 'ASC')
			->first();

		if ($next) {
			$this->swapOrders($item, $next);
		}

		return redirect()->route('Item.index');
	}

	/**
	 * Move item order down
	 */
	public function downOrder($id) {
		$item = Item::findOrFail($id);

		$previous = Item::where('order', '<', $item->order)
			->where('id', '<>', $item->id)
			->where('parent', $item->parent)
			->orderBy('order', 'DESC')
			->first();

		if ($previous) {
			$this->swapOrders($item, $previous);
		}

		return redirect()->route('Item.index');
	}

	/**
	 * Check if level depth is within limits
	 */
	private function checkNivel($parentId) {
		if ($parentId == 0) {
			return false;
		}

		$depth = 0;
		$currentId = $parentId;

		while ($currentId != 0) {
			$item = Item::select('parent', 'id')
				->where('id', $currentId)
				->first();

			if (!$item) {
				break;
			}

			$currentId = $item->parent;
			$depth++;

			if ($depth > self::MAX_LEVEL_DEPTH) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Get last order number for menu
	 */
	private function getLastOrder($menuId) {
		$item = Item::orderBy('order', 'desc')->first();
		return $item ? $item->order : 0;
	}

	/**
	 * Swap order between two items
	 */
	private function swapOrders($item1, $item2) {
		$tempOrder = $item1->order;
		$item1->update(['order' => $item2->order]);
		$item2->update(['order' => $tempOrder]);
	}

	/**
	 * Get form data for create/edit
	 */
	private function getFormData() {
		$cat = new Categoria;
		
		return [
			'item'     => Item::all()->pluck('titulo', 'id'),
			'menus'    => Tipo::where('menu', 1)->where('ativado', 1)->pluck('titulo', 'id'),
			'tag'      => Tag::orderBy('name')->pluck('name', 'id'),
			'catimg'   => Categoria::where('categoria_tipo', 'imagem')->pluck('titulo', 'id'),
			'tree'     => $cat->tree('imagem'),
			'art'      => Artigo::all()->pluck('alias', 'id'),
			'artPasta' => Categoria::where('categoria_tipo', 'artigo')->pluck('titulo', 'id'),
			'doc'      => Documento::all()->pluck('nome', 'id'),
			'docPasta' => Categoria::where('categoria_tipo', 'documento')->pluck('titulo', 'id'),
		];
	}

	/**
	 * Get content array from item
	 */
	private function getContentArray($item) {
		$content = [];

		foreach ($item->conteudos as $conteudo) {
			if ($conteudo->languages->tag == "pt") {
				$content['conteudoPT'] = $conteudo->texto;
				$content['tituloPT']   = $conteudo->titulo;
			} else {
				$content['conteudoEN'] = $conteudo->texto;
				$content['tituloEN']   = $conteudo->titulo;
			}
		}

		return $content;
	}

	/**
	 * Create the folder tree structure
	 */
	public function tree($idTipo) {
		$rootCategory = DB::table('items')
			->where('idTipo', $idTipo)
			->where('default', 1)
			->first();

		if (!$rootCategory) {
			return null;
		}

		$tree = [
			'id'        => $idTipo,
			'titulo'    => $rootCategory->titulo,
			'childreen' => $this->buildTreeChildren($idTipo, $rootCategory->id),
		];

		return $tree;
	}

	/**
	 * Build tree children recursively
	 */
	private function buildTreeChildren($idTipo, $parentId, $level = 1) {
		$children = DB::table('items')
			->where('idTipo', $idTipo)
			->where('default', 0)
			->where('parent', $parentId)
			->orderBy('order', 'asc')
			->get();

		if ($children->isEmpty()) {
			return [];
		}

		$result = [];

		foreach ($children as $child) {
			$node = [
				'id'      => $child->id,
				'titulo'  => $child->titulo,
				'ativado' => $child->ativado,
				'childreen' => [], // Initialize empty array
			];

			if ($level < self::MAX_LEVEL_DEPTH) {
				$subChildren = $this->buildTreeChildren($idTipo, $child->id, $level + 1);
				if (!empty($subChildren)) {
					$node['childreen'] = $subChildren;
				}
				$node['leaf'] = empty($subChildren) ? 1 : 0;
			} else {
				$node['leaf'] = 1;
			}

			$result[] = $node;
		}

		return $result;
	}
}
