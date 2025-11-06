<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Http\Requests\Administrator\CheckRequest;
use App\Http\Requests\Administrator\ItemRequest;
use App\Model\Categoria;
use App\Model\Conteudo;
use App\Model\Item;
use App\Model\Tag;
use App\Model\Tipo;
use DB;
use Illuminate\Http\Request;
use Session;

use App\Helpers\Helpers;
use App\Model\Artigo;
use App\Model\Documento;

class ItemController extends Controller {
	public function __construct() {
		$this->middleware('auth');
	}
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		$tree = null;

		$items = Item::all();
		$menus = Tipo::where('ativado', 1)->where('menu', 1)->get();

		$tree  = 1;
		$trees = array();

		if (count($menus) != 0) {
			foreach ($menus as $key => $item) {
				$trees[] = $this->tree($item->id);
			}
		}

		return view('Administrator.Menu.item')->withTrees($trees)->withItems($items);

	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create() {

		$tipo = Tipo::where('menu', 1)->where('ativado', 1)->pluck('titulo', 'id');

		$items = Item::all()->pluck('titulo', 'id');
		$tag   = Tag::orderBy('name')->pluck('name', 'id');

		$itemsImg = Categoria::where('categoria_tipo', 'imagem')->pluck('titulo', 'id');
		$cat      = new Categoria;
		$tree     = $cat->tree('imagem');

		$art = Artigo::all()->pluck('alias', 'id');
		$artPasta = Categoria::where('categoria_tipo', 'artigo')->pluck('titulo', 'id');

		$doc = Documento::all()->pluck('nome', 'id');
		$docPasta = Categoria::where('categoria_tipo', 'documento')->pluck('titulo', 'id');

		return view('Administrator.Menu.item_form', compact('art', 'artPasta', 'doc', 'docPasta'))->withItem($items)->withMenus($tipo)->withTag($tag)->withCatimg($itemsImg)->withTree($tree);

	}
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function refresh(Request $request) {
		// $tree   = null;
		// $menu   = $this->getMenu();
		// $boinfo = $this->getBOinfo();

		// $this->validate($request, array(
		// 		'tipo' => 'required',
		// 	));

		// $items = DB::table('items')->where('idTipo', $request->tipo)->get();
		// $menus = DB::table('tipos')->where('menu', 1)->pluck('titulo', 'id');

		// $tree = $this->tree($request->tipo);

		// if (isset($items) && $items->count() != 0) {
		// 	foreach ($items as $item) {

		// 		$item01 = Item::find($item->id);

		// 		$tipoNome     = $item01->tipos->titulo;
		// 		$item->idTipo = $tipoNome;

		// 	}
		// }

		// $lista_json2 = json_encode($items, true);

		// return view('Administrator.Menu.item')->withMenu($menu)->withBoinfo($boinfo)->withMenus($menus)->withData($lista_json2)->withTree($tree);

	}

	/**
	 * Get all items from with a idTipo
	 * returns all items from a menu (tipo)
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getOptionsMenu(Request $request) {

		$this->validate($request, array(
				'idTipo' => 'required',
			));

		$items = Item::where('idTipo', $request->idTipo)->pluck('titulo', 'id');

		return $items;
	}
	/**
	 * Check if there is other row with same name.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function checkName($name, $idTipo) {

		// $items = Item::where('idTipo', $idTipo)->where('titulo', $name)->count();

		// if ($items != 0) {
		// 	return 1;
		// } else {
		// 	return 0;
		// }
		return 0;
	}
	/**
	 * Check if the max number of subfolder is not over
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function checkNivel($id) {
		$i      = 0;
		$parent = 1;

		while ($parent != 0) {
			$item = DB::table('items')->select('parent', 'id')->where('id', $id)->get();

			$parent = $item[0]->parent;

			$i++;
			$id = $parent;
		}

		if ($i > 2) {
			return 1;
		} else {
			return 0;
		}
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(ItemRequest $request) {

		$Item = new Item;

		$Item->titulo  = $request->tituloPT;
		$Item->idTipo  = $request->idTipo;
		$Item->ativado = $request->ativado == 'on' ? 1 : 0;
		$Item->parent  = $request->parent;
		$Item->order   = $this->getLastOrder($request->idTipo) + 1;

		if (is_numeric($request->idimagem)) {
			$Item->idImagem = $request->idimagem;
		}

		$Item->url = $request->url;

		$cn = $this->checkName($Item->titulo, $Item->idTipo);
		$n  = $this->checkNivel($Item->parent);

		if ($cn || $n) {
			Session::flash('warning', 'Já existe um item com o mesmo nome ou é limite máximo de níveis.');
		} else {
			Session::flash('success', 'Inserção realizada com sucesso!');

			$Item->save();
			Helpers::guardarConteudos($request, $Item);
			Helpers::salvarTags($request, $Item);
		}

		return redirect()->route('Item.index', $Item->id);

	}
	/**
	 * get lst number accordingly to order.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function getLastOrder($menu) {

		$items = Item::orderBy('order', 'desc')->first();

		if (isset($items) && $items->count() != 0) {
			return $items->order;
		} else {
			return 1;
		}

	}

	/**
	 * get lst number accordingly to order in the same level.
	 *
	 * @param  $item a ser reordenado, $itemI indicado para o campo a seguir
	 * @return \Illuminate\Http\Response
	 */
	public function upOrder($id) {

		$item = Item::find($id);

		$next = Item::where('order', '>', $item->order)->where('id', '<>', $item->id)->where('parent', $item->parent)->orderBy('order', 'ASC')->first();

		if ($next) {
			$orderOld = $item->order;
			$orderNew = $next->order;

			$item->order = $orderNew;
			$next->order = $orderOld;

			$item->save();
			$next->save();
		}

		return redirect()->route('Item.index');

	}

	/**
	 * order down
	 *
	 * @param  $item a ser reordenado, $itemI indicado para o campo a seguir
	 * @return \Illuminate\Http\Response
	 */
	public function downOrder($id) {

		$item = Item::find($id);

		$previous = Item::where('order', '<', $item->order)->where('id', '<>', $item->id)->where('parent', $item->parent)->orderBy('order', 'DESC')->first();

		$orderOld = $item->order;

		if ($previous) {
			$orderNew = $previous->order;

			$item->order     = $orderNew;
			$previous->order = $orderOld;

			$item->save();
			$previous->save();
		}

		return redirect()->route('Item.index');

	}
	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id) {
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id) {
		$Item = Item::find($id);

		$tag = Tag::all()->pluck('name', 'id');

		$cont = array();

		$conteudoPT = $Item->conteudos;

		foreach ($conteudoPT as $conteudo) {
			if ($conteudo->languages->tag == "pt") {
				$cont['conteudoPT'] = $conteudo->texto;
				$cont['tituloPT']   = $conteudo->titulo;
			} else {
				$cont['conteudoEN'] = $conteudo->texto;
				$cont['tituloEN']   = $conteudo->titulo;
			}
		}

		$tipo = Tipo::where('menu', 1)->where('ativado', 1)->pluck('titulo', 'id');

		$items = Item::all()->pluck('titulo', 'id');

		$itemsImg = Categoria::where('categoria_tipo', 'imagem')->pluck('titulo', 'id');
		$cat      = new Categoria;
		$tree     = $cat->tree('imagem');

		$art = Artigo::all()->pluck('alias', 'id');
		$artPasta = Categoria::where('categoria_tipo', 'artigo')->pluck('titulo', 'id');

		$doc = Documento::all()->pluck('nome', 'id');
		$docPasta = Categoria::where('categoria_tipo', 'documento')->pluck('titulo', 'id');

		return view('Administrator.Menu.item_form', compact('art', 'artPasta', 'doc', 'docPasta'))->withItem($items)->withMenus($tipo)->withId($Item)->withContent($cont)->withTag($tag)->withCatimg($itemsImg)->withTree($tree);

	}
	/**
	 * Check name for edit action.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function checkNameEdit($name, $idTipo, $id) {
      	return 0;

	}
	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(ItemRequest $request, $id) {

		$Item = Item::find($id);

		if ($Item->default == 0) {
			if ($Item->idTipo == $request->idTipo) {
				$Item->titulo  = $request->tituloPT;
				$Item->ativado = $request->ativado == 'on' ? 1 : 0;
				$Item->parent  = $request->parent;
				if (is_numeric($request->idimagem)) {
					$Item->idImagem = $request->idimagem;
				}
				$Item->url = $request->url;

				$cn = $this->checkNameEdit($Item->titulo, $Item->idTipo, $id);
				$n  = $this->checkNivel($Item->parent);

				if ($cn || $n) {
					Session::flash('warning', 'Já existe uma pasta com o mesmo nome ou é limite máximo de níves.');
				} else {
					Session::flash('success', 'Inserção realizada com sucesso!');

					$Item->save();

					Helpers::salvarTags($request, $Item);

					$conteudos = $Item->conteudos;

					Helpers::atualizarConteudo($conteudos, $request, $Item);
				}
			} else { //END
				Session::flash('warning', 'Não é possível mover um item de menu para outro menu.');
			}
		} else {
			Session::flash('warning', 'A pasta raiz não pode ser editada!');
		}

		return redirect()->route('Item.index', $Item->id);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id) {

		$Item = Item::find($id);

		if (isset($Item->tags)) {
			$Item->tags()->detach();
		}

		$Item->conteudos()->delete();
		$Item->delete();

		Session::flash('success', 'Item foi removido!');

		return redirect()->route('Item.index');

	}

	//////////// funcao de selecao /////////
	public function publicarCheck(CheckRequest $ids) {
		$item = Item::whereIn('id', $ids->check)->update(['ativado' => 1]);
		return redirect()->route('Item.index');
	}

	public function despublicarCheck(CheckRequest $ids) {
		$item = Item::whereIn('id', $ids->check)->update(['ativado' => 0]);
		return redirect()->route('Item.index');
	}

	public function removerCheck(CheckRequest $ids) {

		foreach ($ids->check as $id) {
			$item = Item::find($id);
			$item->tags()->detach();
			$item->conteudos()->delete();
		}
		$item = Item::whereIn('id', $ids->check)->delete();
		return redirect()->route('Item.index');
	}

	/**
	 * DEsativa o tipo
	 *
	 */
	public function despublicar($id) {
		$Item          = Item::find($id);
		$Item->ativado = 0;

		//send to controller conteudos to insert with new article id

		$Item->save();

		Session::flash('success', 'Item Despublicado!');

		return redirect()->route('Item.index');

	}
	/**
	 * Activa o tipo
	 *
	 */
	public function publicar($id) {
		$Item          = Item::find($id);
		$Item->ativado = 1;

		//send to controller conteudos to insert with new article id

		$Item->save();

		Session::flash('success', 'Dados Publicados!');

		return redirect()->route('Item.index', $Item->id);

	}

	/**
	 * Create the folder tree
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function tree($idTipo) {

		$subtree2 = null;
		$subtree1 = null;

		$catRaiz = DB::table('items')->where('idTipo', $idTipo)->where('default', 1)->get();

		$tree = array(
			//	"id"        => $catRaiz[0]->id,
			"id"        => $idTipo,
			"titulo"    => $catRaiz[0]->titulo,
			'childreen' => Array()
		);

		$catChildren = DB::table('items')->where('idTipo', $idTipo)->where('default', 0)->where('parent', $catRaiz[0]->id)->orderBy('order', 'asc')->get();

		if (isset($catChildren)) {
			foreach ($catChildren as $value) {

				$catSubChildren = DB::table('items')->where('idTipo', $idTipo)->where('default', 0)->where('parent', $value->id)->orderBy('order', 'asc')->get();

				if (isset($catSubChildren)) {
					$subtree2 = Array();

					foreach ($catSubChildren as $value2) {
						$subtree2[] = array(
							"id"      => $value2->id,
							"titulo"  => $value2->titulo,
							'ativado' => $value2->ativado,
							'leaf'    => 1,
						);

					}
				}

				$subtree1[] = array(
					"id"        => $value->id,
					"titulo"    => $value->titulo,
					'ativado'   => $value->ativado,
					'childreen' => $subtree2,
				);
			}
		}

		$tree['childreen'] = $subtree1;

		return $tree;

	}
}
