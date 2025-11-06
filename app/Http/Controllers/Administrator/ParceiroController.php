<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Http\Requests\Administrator\CheckRequest;
use App\Http\Requests\Administrator\ParceiroRequest;
use App\Model\Categoria;
use App\Model\Parceiro;
use DB;
use File;

use Session;

class ParceiroController extends Controller {
	public function __construct() {
		$this->middleware('auth');
	}
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {

		$parceiros = Parceiro::all();

		$lista_json = json_encode($parceiros, true);

		return view('Administrator.Modulo.parceiro.parceiro')->withData($lista_json)->withParceiros($parceiros);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create() {


		$img = DB::table('imagems')->select('id', 'url', 'titulo')->orderBy('titulo', 'asc')->get();

		$catimg = Categoria::where('categoria_tipo', 'imagem')->where('ativado', 1)->pluck('titulo', 'id');
		$cat      = new Categoria;
		$tree     = $cat->tree('imagem');

		return view('Administrator.Modulo.parceiro.parceiro_form', compact('catimg', 'tree'))->withImg($img);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(ParceiroRequest $request) {

		$Parceiro           = new Parceiro;
		$Parceiro->titulo   = $request->titulo;
		$Parceiro->ativado  = $request->ativado == 'on'?1:0;
		$Parceiro->idImagem = $request->idimagem;
		$Parceiro->url      = $request->url;

		$Parceiro->save();

		Session::flash('success', 'Inserção realizada com sucesso!');

		return redirect()->route('Parceiro.index', $Parceiro->id);
	}
	/**
	 * DEsativa o tipo
	 *
	 */
	public function despublicar($id) {
		$Parceiro          = Parceiro::find($id);
		$Parceiro->ativado = 0;
		$Parceiro->save();

		Session::flash('success', 'Parceiro Despublicado!');
		return redirect()->route('Parceiro.index', $Parceiro->id);
	}
	/**
	 * Activa o tipo
	 *
	 */
	public function publicar($id) {
		$Parceiro          = Parceiro::find($id);
		$Parceiro->ativado = 1;
		$Parceiro->save();

		Session::flash('success', 'Parceiro Publicado!');

		return redirect()->route('Parceiro.index', $Parceiro->id);
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
		$Parceiro = Parceiro::find($id);
		$cat      = new Categoria;
		$catimg = Categoria::where('categoria_tipo', 'imagem')->where('ativado', 1)->orderBy('titulo', 'desc')->pluck('titulo', 'id');
		$tree = $cat->tree('imagem');

		$img = DB::table('imagems')->select('id', 'url', 'titulo')->orderBy('titulo', 'asc')->get();


		return view('Administrator.Modulo.parceiro.parceiro_form', compact('catimg', 'tree'))->withParceiro($Parceiro)->withImg($img);

	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(ParceiroRequest $request, $id) {

		$Parceiro           = Parceiro::find($id);
		$Parceiro->titulo   = $request->titulo;
		$Parceiro->ativado  = $request->ativado == 'on' ? 1 : 0;
		$Parceiro->idImagem = $request->idimagem;
		$Parceiro->url      = $request->url;

		$Parceiro->save();

		Session::flash('success', 'Inserção realizada com sucesso!');

		return redirect()->route('Parceiro.index', $Parceiro->id);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id) {
		$Parceiro = Parceiro::find($id);

		$Parceiro->delete();

		Session::flash('success', 'Parceiro apagado!');

		return redirect()->route('Parceiro.index');
	}

	public function publicarCheck(CheckRequest $ids) {
		$Parceiro = Parceiro::whereIn('id', $ids->check)->update(['ativado' => 1]);
		return redirect()->route('Parceiro.index');
	}

	public function despublicarCheck(CheckRequest $ids) {
		$Parceiro = Parceiro::whereIn('id', $ids->check)->update(['ativado' => 0]);
		return redirect()->route('Parceiro.index');
	}

	public function removerCheck(CheckRequest $ids) {

		$Parceiro = Parceiro::whereIn('id', $ids->check)->delete();
		return redirect()->route('Parceiro.index');
	}

	/*****************
	Get Menu_backoffice.json
	 ******************/
	public function export() {

		$path = storage_path('json/parceiro.json');
		File::delete($path);

		$parceiros = Parceiro::where('ativado', 1)->get();

		if (isset($parceiros) && $parceiros->count() != 0) {
			foreach ($parceiros as $parceiro) {

				$imagems = $parceiro->imagems;

				unset($imagems['ativado']);
				unset($imagems['updated_at']);
				unset($imagems['titulo']);
				unset($imagems['created_at']);
				unset($imagems['id']);
				unset($imagems['idCategoria']);

				unset($parceiro['destaque']);
				unset($parceiro['updated_at']);
				unset($parceiro['idUser']);
				unset($parceiro['ativado']);
			}
		}

		file_put_contents($path, json_encode($parceiros, true));

		Session::flash('success', 'Parceiros exportados!');

		return redirect()->route('Parceiro.index');
	}
}
