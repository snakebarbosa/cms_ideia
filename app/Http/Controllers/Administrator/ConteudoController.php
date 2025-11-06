<?php
namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Http\Requests\Administrator\ConteudoRequest;
use App\Model\Conteudo;

class ConteudoController extends Controller {
	public function __construct() {
		$this->middleware('auth:api');
	}
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		//
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
	public function store(ConteudoRequest $request) {
		//Validate

		$conteudo = new Conteudo;

		if (isset($request->idArtig)) {
			$conteudo->idArtigo = $request->idArtigo;
		}

		$conteudo->titulo     = $request->titulo;
		$conteudo->texto      = $request->texto;
		$conteudo->idLanguage = $request->idLanguage;

		$conteudo->save();

		// if($request->type == "midia"){

		//     $cat->imagem = 1;

		// }elseif($request->type == "doc"){
		//      $cat->documento = 1;

		// }

		//send to controller conteudos to insert with new article id

		//save again for diferent language

		//send messsage ok return

		return redirect()->route('Conteudo.index', $conteudo->id);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id) {

		return view('Administrator.Artigos.artigo');
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
	public function update(ConteudoRequest $request, $id) {

		$conteudo = Conteudo::find($id);

		// if(isset($request->idArtig)){
		//    $conteudo->idArtigo= $request->idArtigo;
		// }

		$conteudo->titulo = $request->titulo;
		$conteudo->texto  = $request->texto;

		$conteudo->save();

		return redirect()->route('Conteudo.index', $conteudo->id);
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

}
