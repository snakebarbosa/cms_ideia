<?php

namespace App\Http\Controllers\Administrator;
use App\Http\Controllers\Controller;
use App\Http\Requests\Administrator\CheckRequest;
use App\Http\Requests\Administrator\EventoRequest;
use Illuminate\Http\Request;
use App\Model\Categoria;

//alterar todos para assim
use App\Model\Evento;

use App\Model\Tag;

use App\User;
use Auth;
use DB;


use Purifier;
use Session;



class EventoController extends Controller {
	public function __construct() {
		$this->middleware('auth');
	}
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {

		$eventos = Evento::all();

		return view('Administrator.Eventos.evento')->withEventos($eventos);

	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create() {

		$tag   = Tag::orderBy('name')->pluck('name', 'id');
		$items = Categoria::where('categoria_tipo', 'evento')->pluck('titulo', 'id');

		$itemsImg = Categoria::where('categoria_tipo', 'imagem')->pluck('titulo', 'id');
		$cat      = new Categoria;
		$tree     = $cat->tree('imagem');

		return view('Administrator.Eventos.evento_form')->withTag($tag)->withCat($items)->withCatimg($itemsImg)->withTree($tree);

	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(EventoRequest $request) {

		// dd($request->all());
		$evento                  = new evento;
		$evento->nome            = Purifier::clean($request->nome);
		$evento->alias           = Purifier::clean($request->nome);
		$evento->idUser          = Auth::user()->id;
		$evento->endereco        = Purifier::clean($request->endereco);
		$evento->descricao       = Purifier::clean($request->descricao);
		$evento->numeroInscricao = $request->numeroInscricao;
		$evento->dataInicio      = $request->dataInicio;
		$evento->dataFim         = $request->dataFim;
		$evento->precoIndividual = $request->precoIndividual;
		$evento->dataPagamento   = $request->dataPagamento;
		$evento->latitude        = $request->latitude;
		$evento->longitude       = $request->longitude;
		$evento->linkGoogleMaps  = $request->linkGoogleMaps;
		$evento->ativado         = $request->ativado == 'on'?1:0;
		$evento->destaque        = $request->destaque == 'on'?1:0;
		$evento->idCategoria     = $request->idCategoria;
		$evento->keyword         = $request->keyword;

		if (is_numeric($request->idimagem)) {
			$evento->idImagem = $request->idimagem;
		}

		$evento->save();

		if (isset($request->tag)) {
			$evento->tags()->sync($request->tag, false);
		} else {
			$evento->tags()->sync(array());
		}

		Session::flash('success', 'Inserção realizada com sucesso!');

		return redirect()->route('Evento.index', $evento->id);
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
		$Evento = Evento::find($id);
		$tag    = Tag::orderBy('name')->pluck('name', 'id');
		$items  = Categoria::where('categoria_tipo', 'evento')->pluck('titulo', 'id');

		$itemsImg = Categoria::where('categoria_tipo', 'imagem')->pluck('titulo', 'id');
		$cat      = new Categoria;
		$tree     = $cat->tree('imagem');

		return view('Administrator.Eventos.evento_form')->withEvento($Evento)->withTag($tag)->withCat($items)->withCatimg($itemsImg)->withTree($tree);
		// ->withContent($cont)
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(EventoRequest $request, $id) {

		$evento = Evento::find($id);

		$evento->nome            = Purifier::clean($request->nome);
		$evento->alias           = Purifier::clean($request->nome);
		$evento->endereco        = Purifier::clean($request->endereco);
		$evento->descricao       = Purifier::clean($request->descricao);
		$evento->idUser          = Auth::user()->id;
		$evento->numeroInscricao = $request->numeroInscricao;
		$evento->dataInicio      = $request->dataInicio;
		$evento->dataFim         = $request->dataFim;
		$evento->dataPagamento   = $request->dataPagamento;
		$evento->latitude        = $request->latitude;
		$evento->longitude       = $request->longitude;
		$evento->linkGoogleMaps  = $request->linkGoogleMaps;
		$evento->ativado         = $request->ativado == 'on'?1:0;
		$evento->destaque        = $request->destaque == 'on'?1:0;
		$evento->idCategoria     = $request->idCategoria;
		$evento->keyword         = $request->keyword;

		if (is_numeric($request->idimagem)) {
			$evento->idImagem = $request->idimagem;
		}

		if (is_numeric($request->precoIndividual)) {
			$evento->precoIndividual = $request->precoIndividual;
		}

		$evento->save();

		if (isset($request->tag)) {
			$evento->tags()->sync($request->tag, false);
		} else {
			$evento->tags()->sync(array());
		}

		Session::flash('success', 'Inserção realizada com sucesso!');

		return redirect()->route('Evento.index', $evento->id);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id) {

	}

	public function despublicar($id) {
		$Evento          = Evento::find($id);
		$Evento->ativado = 0;
		$Evento->save();

		Session::flash('success', 'Evento Despublicado!');
		return redirect()->route('Evento.index', $Evento->id);
	}
	/**
	 * Activa o tipo
	 *
	 */
	public function publicar($id) {
		$Evento          = Evento::find($id);
		$Evento->ativado = 1;
		$Evento->save();

		Session::flash('success', 'Evento Publicado!');

		return redirect()->route('Evento.index', $Evento->id);
	}

	public function publicarCheck(CheckRequest $ids) {
		$evento = Evento::whereIn('id', $ids->check)->update(['ativado' => 1]);
		return redirect()->route('Evento.index');
	}

	public function despublicarCheck(CheckRequest $ids) {
		$evento = Evento::whereIn('id', $ids->check)->update(['ativado' => 0]);
		return redirect()->route('Evento.index');
	}

	public function destaqueCheck(CheckRequest $ids) {
		$evento = Evento::whereIn('id', $ids->check)->update(['destaque' => 1]);
		return redirect()->route('Evento.index');
	}

	public function rdestaqueCheck(CheckRequest $ids) {
		$evento = Evento::whereIn('id', $ids->check)->update(['destaque' => 0]);
		return redirect()->route('Evento.index');
	}

	public function removerCheck(CheckRequest $ids) {

		foreach ($ids->check as $id) {
			$e = Evento::find($id);
			$e->tags()->detach();
			// $e->conteudos()->delete();
		}
		$evento = Evento::whereIn('id', $ids->check)->delete();

		return redirect()->route('Evento.index');
	}
	/**
	 * DEsativa o tipo
	 *
	 */
	public function destacar($id) {
		$Evento           = Evento::find($id);
		$Evento->destaque = 1;
		$Evento->save();

		Session::flash('success', 'Evento Destacao!');
		return redirect()->route('Evento.index', $Evento->id);
	}
	/**
	 * Activa o tipo
	 *
	 */
	public function rdestacar($id) {
		$Evento           = Evento::find($id);
		$Evento->destaque = 0;
		$Evento->save();

		Session::flash('success', 'Destaque Removido!');

		return redirect()->route('Evento.index', $Evento->id);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getFormulario($id) {

		//$eventos = Evento::all();

		// DD("teste");       
         $formulario = Evento::where('id', $id)->pluck('formulario');
         $eventoId = Evento::where('id', $id)->pluck('id');
         // $jsonObj = json_decode($formulario);
         // $jsonObj = json_encode($formulario);
		 // dd($jsonObj);
      
		return view('Administrator.Eventos.formulario', compact('formulario', 'eventoId'));

	}

	public function setFormulario(Request $request){
	      
	      $this->validate($request, array(
				'idEvent' => 'required|Integer|min:0',
				'pegaJson' => 'required|json',
			));
       
        try {
		        $evento = Evento::find($request->idEvent);
				$evento->formulario      = $request->pegaJson;
				$evento->save();
		        return "salvo";
          }

		//catch exception
		catch(Exception $e) {
		  echo 'Message: ' .$e->getMessage();
		}

	}

}
