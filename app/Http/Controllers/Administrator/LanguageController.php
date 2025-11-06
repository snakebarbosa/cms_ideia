<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Http\Requests\Administrator\CheckRequest;
use App\Http\Requests\Administrator\LanguageRequest;
use App\Model\Language;

use Session;

class LanguageController extends Controller {
	public function __construct() {
		$this->middleware('auth');
	}
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {

		$language    = Language::all();
		$lista_json2 = json_encode($language, true);

		return view('Administrator.Config.language')->withData($lista_json2)->withLanguage($language);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create() {

		return view('Administrator.Config.language_form');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(LanguageRequest $request) {
		//Validate

		$lang = new Language;

		$lang->titulo  = $request->titulo;
		$lang->tag     = $request->tag;
		$lang->ativado = $request->ativado == 'on'?1:0;

		$lang->save();

		Session::flash('success', 'Inserção realizada com sucesso!');

		return redirect()->route('Language.index', $lang->id);
	}
	/**
	 * DEsativa o tipo
	 *
	 */
	public function despublicar($id) {
		$language          = Language::find($id);
		$language->ativado = 0;
		$language->save();

		Session::flash('success', 'Idioma Despublicado!');
		return redirect()->route('Language.index', $language->id);
	}
	/**
	 * Activa o tipo
	 *
	 */
	public function publicar($id) {
		$language          = Language::find($id);
		$language->ativado = 1;
		$language->save();

		Session::flash('success', 'Idioma Publicado!');

		return redirect()->route('Language.index', $language->id);
	}
	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id) {

		// return view('Administrator.Config.idioma')->withMenu($menu)->withBoinfo($boinfo);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id) {
		$language = Language::find($id);

		return view('Administrator.Config.language_form')->withLanguage($language);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(LanguageRequest $request, $id) {

		$lang = Language::find($id);

		$lang->titulo  = $request->titulo;
		$lang->tag     = $request->tag;
		$lang->ativado = $request->ativado == 'on'?1:0;
		$lang->save();

		Session::flash('success', 'Dados guardados!');

		//$request->session()->flash('alert-success', 'User was successful added!');

		return redirect()->route('Language.index', $lang->id);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id) {
		$language = Language::find($id);

		$language->delete();

		//$language->documentos()->detach();

		Session::flash('success', 'Dados apagados!');

		return redirect()->route('Language.index');
	}

	//////////// funcao de selecao /////////
	public function publicarCheck(CheckRequest $ids) {
		$lang = Language::whereIn('id', $ids->check)->update(['ativado' => 1]);
		return redirect()->route('Language.index');
	}

	public function despublicarCheck(CheckRequest $ids) {
		$lang = Language::whereIn('id', $ids->check)->update(['ativado' => 0]);
		return redirect()->route('Language.index');
	}

	public function removerCheck(CheckRequest $ids) {
		$lang = Language::whereIn('id', $ids->check)->delete();
		return redirect()->route('Language.index');
	}

}
