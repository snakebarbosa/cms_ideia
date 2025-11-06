<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Http\Requests\Administrator\ModuloRequest;

use App\Model\Item;

use DB;
use File;
use Illuminate\Http\Request;
use Session;

class ModuloController extends Controller {
	public function __construct() {
		$this->middleware('auth');
	}
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		//
	}

	public function iVideo() {

		return view('Administrator.Modulo.video');

	}

	public function iMenu() {

		$tipo = DB::table('tipos')->where('menu', 1)->pluck('titulo', 'id');

		return view('Administrator.Modulo.menu')->withMenus($tipo);

	}

	public function storeSlide(Request $request) {

	}
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create() {
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request) {
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function storeMenu(ModuloRequest $request) {

		Session::flash('success', 'Menus guardados com sucesso!');

		return redirect()->route('Menu.imenu');
	}
	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function storeVideo(Request $request) {

		$this->validate($request, array(
				'url' => 'required',
			));

		File::delete(storage_path('json/video.json'));

		$video_array = array('url' => $request->url);

		file_put_contents(storage_path('json/video.json'), json_encode($video_array, true));

		Session::flash('success', 'Link de video guardado!');

		return redirect()->route('Video.ivideo');
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
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id) {
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id) {
		//
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
			"id"        => $catRaiz[0]->id,
			"titulo"    => $catRaiz[0]->titulo,
			'childreen' => Array()
		);

		$catChildren = Item::where('idTipo', $idTipo)->where('ativado', 1)->where('default', 0)->where('parent', $catRaiz[0]->id)->get();

		if (isset($catChildren)) {
			foreach ($catChildren as $value) {
				$value->conteudos;
				$catSubChildren = Item::where('idTipo', $idTipo)->where('ativado', 1)->where('default', 0)->where('parent', $value->id)->get();

				$conteudos1 = $value->conteudos;
				$imagem1    = $value->imagems;

				foreach ($conteudos1 as $conteudo) {
					$languages = $conteudo->languages;

					
				}

				if (isset($catSubChildren)) {
					$subtree2 = Array();

					foreach ($catSubChildren as $value2) {
						$conteudos = $value2->conteudos;

						foreach ($conteudos as $conteudo) {
							$languages = $conteudo->languages;
							
						}
						$value2['leaf'] = 1;
						$subtree2[]     = $value2;

						unset($value2['created_at']);
						unset($value2['updated_at']);
						unset($value2['default']);
						unset($value2['parent']);
						unset($value2['id']);
						unset($value2['idTipo']);
						unset($value2['ativado']);

					}//.Foreach
				}//.if
				$value['childreen'] = $subtree2;
				$subtree1[]         = $value;
				// DD($subtree2);

				//$subtree1['childreen'] = $subtree2;

				unset($value['created_at']);
				unset($value['updated_at']);
				unset($value['default']);
				unset($value['parent']);
				unset($value['id']);
				unset($value['idTipo']);
				unset($value['ativado']);
			}
		}

		$tree['childreen'] = $subtree1;

		return $tree;

	}
}
