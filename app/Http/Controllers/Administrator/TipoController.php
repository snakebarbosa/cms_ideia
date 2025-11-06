<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Http\Requests\Administrator\CheckRequest;
use App\Http\Requests\Administrator\TipoRequest;
use App\Model\Item;
use App\Model\Tipo;

use App\Model\User;
use DB;

use Session;

class TipoController extends Controller {
	public function __construct() {
		$this->middleware('auth');
	}
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {

		$tipo = Tipo::where('menu', 1)->get();

		return view('Administrator.Menu.tipo')->withData($tipo);
	}

	public function iMenu() {

		$tipo = Tipo::all();

		$tipo = DB::table('tipos')->where('menu', 1)->get();

		$lista_json2 = json_encode($tipo, true);

		return view('Administrator.Menu.tipo')->withMenu($menu)->withBoinfo($boinfo)->withData($lista_json2);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create() {
		//

		$menu = Tipo::all();

		return view('Administrator.Menu.tipo_form');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(TipoRequest $request) {

		$Tipo = new Tipo;

		$Tipo->titulo = $request->titulo;
		// $Tipo->tipo    = $request->tipo;
		$Tipo->ativado = $request->ativado == 'on'?1:0;
		;

		$cn = $this->checkName($Tipo->titulo);

		if ($request->tipo == "doc") {
			$Tipo->doc = 1;
			$redirect  = 'Tipo.idoc';

		} elseif ($request->tipo == "menu") {
			$Tipo->menu    = 1;
			$Tipo->posicao = $request->posicao;
			$redirect      = 'Tipo.imenu';

		} else {
			$request->user = 1;

		}

		if ($cn) {
			Session::flash('warning', 'Já existe uma pasta com o mesmo nome!');

		} else {
			Session::flash('success', 'Inserção realizada com sucesso!');
			$Tipo->save();

			if ($Tipo->menu == 1) {
				$items          = new Item();
				$items->titulo  = 'Raiz '.$Tipo->titulo;
				$items->idTipo  = $Tipo->id;
				$items->url     = '#';
				$items->ativado = 1;
				$items->default = 1;

				$Tipo->items()->saveMany([$items]);
			}
		}

		return redirect()->route('Tipo.index', $Tipo->id);
	}

	public function checkName($name) {
		$items = Tipo::where('titulo', $name)->count();

		if ($items != 0) {
			return 1;
		} else {
			return 0;
		}
	}
	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id) {

		$tipo = Tipo::find($id);

		return view('Administrator.Config.tipo')->withTipo($tipo);

	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id) {
		$tipo = Tipo::find($id);

		// $menu   = $this->getMenu();
		// $boinfo = $this->getBOinfo();

		return view('Administrator.Menu.tipo_form')->withTipo($tipo);
	}
	/**
	 * DEsativa o tipo
	 *
	 */
	public function despublicar($id) {
		$tipo = Tipo::find($id);

		$tipo->ativado = 0;

		$tipo->save();

		Session::flash('success', 'Dados Despublicados!');

		return redirect()->route('Tipo.index', $tipo->id);

	}
	/**
	 * Activa o tipo
	 *
	 */
	public function publicar($id) {
		$tipo = Tipo::find($id);

		$tipo->ativado = 1;

		$tipo->save();

		Session::flash('success', 'Dados Publicados!');

		return redirect()->route('Tipo.index', $tipo->id);

	}
	/**
	 * Activa o tipo
	 *
	 */
	public function publicarCheck(CheckRequest $ids) {

		$art = Tipo::whereIn('id', $ids->check)->update(['ativado' => 1]);

		return redirect()->route('Tipo.index');
	}
	/**
	 * Activa o tipo
	 *
	 */
	public function despublicarCheck(CheckRequest $ids) {
		$Tipo = Tipo::whereIn('id', $ids->check)->update(['ativado' => 0]);
		return redirect()->route('Tipo.index');
	}

	/**
	 * Activa o tipo
	 *
	 */
	public function removerCheck(CheckRequest $ids) {

		foreach ($ids->check as $id) {
			$tipo = Tipo::find($id);

			$tipo->items()->delete();
		}

		$Tipo = Tipo::whereIn('id', $ids->check)->delete();

		return redirect()->route('Tipo.index');
	}
	/**
	 * Check name for edit action.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function checkNameEdit($name, $id) {
		$items = Item::where('id', '<>', $id)->where('titulo', $name)->count();

		if ($items != 0) {
			return 1;
		} else {
			return 0;
		}

	}
	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(TipoRequest $request, $id) {
		//

		$tipo = Tipo::find($id);

		$tipo->titulo  = $request->titulo;
		$tipo->ativado = $request->ativado == 'on'?1:0;
		$tipo->posicao = $request->posicao;

		$cn = $this->checkNameEdit($tipo->titulo, $id);

		if ($cn) {
			Session::flash('warning', 'Já existe um menu com o mesmo nome!');

		} else {
			Session::flash('success', 'Dados guardados!');
			$tipo->save();

			if ($tipo->menu == 1) {
				$items = $tipo->items;

				$tipo->save();

				foreach ($items as $item) {
					if ($item->default == 1) {
						$item->titulo = 'Raiz '.$tipo->titulo;

					}
				}

				$tipo->items()->saveMany($items);
			}
		}

		return redirect()->route('Tipo.index', $tipo->id);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id) {
		$tipo = Tipo::find($id);

		$tipo->items()->delete();

		$tipo->delete();

		Session::flash('success', 'Dados apagados!');

		return redirect()->route('Tipo.index');
	}

	/**************************************/
	public function destroyMe($id, $type) {
		// $tipo = Tipo::find($id);

		// $this->destroy($id);
		// Session::flash('success', 'Dados apagados!');

		// if ($type == "menu") {
		// 	return redirect()->route('Tipo.imenu', $tipo->id);

		// } elseif ($type == "doc") {
		// 	return redirect()->route('Tipo.idoc', $tipo->id);
		// } else {
		// 	// return redirect()->route('Midia.categoria',$tipo->id);
		// }

	}

}
