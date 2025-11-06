<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Http\Requests\Administrator\CheckRequest;
use App\Http\Requests\Administrator\SlideRequest;
use App\Model\Categoria;

use App\Model\Conteudo;
use App\Model\Slide;
use DB;
use File;

use Session;
use App\Helpers\Helpers;

class SlideController extends Controller {
	public function __construct() {
		$this->middleware('auth');
	}
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {

		$slides = Slide::orderBy('order', 'asc')->get();
		return view('Administrator.Modulo.slide.slide')->withData($slides);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create() {
		// $menu = $this->getMenu();
		// $boinfo = $this->getBOinfo();
		$itemsImg = Categoria::where('categoria_tipo', 'imagem')->pluck('titulo', 'id');
		$cat      = new Categoria;
		$tree     = $cat->tree('imagem');

		//$img      = DB::table('imagems')->select('id', 'url', 'titulo')->orderBy('titulo', 'asc')->get();

		return view('Administrator.Modulo.slide.slide_form')->withCatimg($itemsImg)->withTree($tree);

	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(SlideRequest $request) {

		$this->ordenarSlide(2);
		$slide           = new Slide;
		$slide->alias    = $request->tituloPT;
		$slide->ativado  = $request->ativado == 'on' ? 1 : 0;
		$slide->idImagem = $request->idimagem;
		$slide->url      = $request->url;
		$slide->posicao  = $request->posicao;
		$slide->publicar  = $request->publicar;
		$slide->despublicar  = $request->despublicar == null ? config('custom.LAST_DATA_FIELD') : $request->despublicar;
		$slide->order  =  1;


		$slide->save();
		Helpers::guardarConteudos($request, $slide);

		Session::flash('success', 'Inserção realizada com sucesso!');

		return redirect()->route('Slide.index', $slide->id);
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
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function publicarCheck(CheckRequest $ids) {

		$slide = Slide::whereIn('id', $ids->check)->update(['ativado' => 1]);

		return redirect()->route('Slide.index');
	}
	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function despublicarCheck(CheckRequest $ids) {
		$slide = Slide::whereIn('id', $ids->check)->update(['ativado' => 0]);
		return redirect()->route('Slide.index');
	}
	/**
	 * DEsativa o tipo
	 *
	 */
	public function despublicar($id) {
		$Slide          = Slide::find($id);
		$Slide->ativado = 0;
		$Slide->save();

		Session::flash('success', 'Slide Despublicado!');
		return redirect()->route('Slide.index', $Slide->id);
	}
	/**
	 * Activa o tipo
	 *
	 */
	public function publicar($id) {
		$Slide          = Slide::find($id);
		$Slide->ativado = 1;
		$Slide->save();

		Session::flash('success', 'Slide Publicado!');

		return redirect()->route('Slide.index', $Slide->id);
	}
	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id) {
		$itemsImg = Categoria::where('categoria_tipo', 'imagem')->pluck('titulo', 'id');

		$slide = Slide::find($id);

		$cont = array();

		$conteudoPT = $slide->conteudos;
		// ->withMenu($menu)

		foreach ($conteudoPT as $conteudo) {

			if ($conteudo->languages->tag == "pt") {
				$cont['conteudoPT'] = $conteudo->texto;
				$cont['tituloPT']   = $conteudo->titulo;
			} else {
				$cont['conteudoEN'] = $conteudo->texto;
				$cont['tituloEN']   = $conteudo->titulo;
			}
		}

		//$img = DB::table('imagems')->select('id', 'url', 'titulo')->orderBy('titulo', 'asc')->get();
		$cat  = new Categoria;
		$tree = $cat->tree('imagem');

		return view('Administrator.Modulo.slide.slide_form')->withSlide($slide)->withCatimg($itemsImg)->withTree($tree)->withContent($cont);

	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(SlideRequest $request, $id) {

		$Slide           = Slide::find($id);
		$Slide->alias    = $request->tituloPT;
		$Slide->ativado  = $request->ativado == 'on' ? 1 : 0;
		$Slide->idImagem = $request->idimagem;
		$Slide->url      = $request->url;
		$Slide->posicao  = $request->posicao;
		$Slide->publicar  = $request->publicar;
		$Slide->despublicar  = $request->despublicar == null ? config('custom.LAST_DATA_FIELD') : $request->despublicar;

		$conteudos = $Slide->conteudos;

		$Slide->save();

		Helpers::atualizarConteudo($conteudos, $request, $Slide);

		Session::flash('success', 'Inserção realizada com sucesso!');

		return redirect()->route('Slide.index', $Slide->id);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id) {
		$slide = Slide::find($id);
		$slide->conteudos()->delete();

		$slide->delete();

		Session::flash('success', 'Slide apagado!');

		return redirect()->route('Slide.index');
	}

	/**
	 * remover slide por check.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */

	public function removerCheck(CheckRequest $ids) {

		foreach ($ids->check as $id) {
			$s = Slide::find($id);
			$s->conteudos()->delete();
		}
		$slide = Slide::whereIn('id', $ids->check)->delete();
		$this->ordenarSlide(1);

		return redirect()->route('Slide.index');
	}

	/*****************
	Get Menu_backoffice.json
	 ******************/
	public function export() {

		$path_slide = storage_path('json/slideHome.json');
		File::delete($path_slide);

		$slides = Slide::where('ativado', 1)->get();

		if (isset($slides) && $slides->count() != 0) {
			foreach ($slides as $slide) {
				$conteudos = $slide->conteudos;
				$imagems   = $slide->imagems;

				foreach ($conteudos as $conteudo) {
					$languages = $conteudo->languages;

					//$conteudo['texto'] = substr($conteudo['texto'], 0,40).'...';

					unset($languages['ativado']);
					unset($languages['updated_at']);
					unset($languages['titulo']);
					unset($languages['created_at']);

					unset($imagems['ativado']);
					unset($imagems['updated_at']);
					unset($imagems['titulo']);
					unset($imagems['created_at']);
					unset($imagems['id']);
					unset($imagems['idCategoria']);

					unset($conteudo['created_at']);
					unset($conteudo['idArtigo']);
					unset($conteudo['updated_at']);
					unset($conteudo['idDocumento']);
					unset($conteudo['idFaq']);
					unset($conteudo['idSlide']);
					unset($conteudo['idItem']);
					unset($conteudo['ativado']);
				}
				unset($slide['destaque']);
				unset($slide['created_at']);
				unset($slide['updated_at']);
				unset($slide['ativado']);
				unset($slide['id']);
			}
		}

		file_put_contents($path_slide, json_encode($slides, true));

		Session::flash('success', 'Slides em destaque exportados!');

		return redirect()->route('Slide.index');
	}

	public function ordenarSlide($i)
	{
		$orderSlide = Slide::orderBy('order', 'asc')->get();
		foreach ($orderSlide as $value) {
			Slide::where('id', $value->id)->update(['order' => $i++]);
		}
	}

	public function mudarOrdem($id, $order)
	{

		if ($order == 1) {
			return redirect()->back();
		}

		$posicaoAtual = Slide::find($id);
		$posicaoAntes = Slide::where('order', '<', $order)->orderby('order', 'desc')->first();

		$posicaoAtual->order--;
		$posicaoAntes->order++;

		$posicaoAtual->save();
		$posicaoAntes->save();

		return redirect()->back();
	}
}
