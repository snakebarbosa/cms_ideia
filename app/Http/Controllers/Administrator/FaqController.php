<?php

namespace App\Http\Controllers\Administrator;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\Administrator\CheckRequest;
use App\Http\Requests\Administrator\FaqRequest;
use App\Model\Categoria;
use App\Model\Conteudo;
use App\Model\Faq;
use App\Model\Artigo;
use App\Model\Tag;
use DB;
use Exception;
use File;
use Purifier;
use Session;

class FaqController extends Controller {
	
	public function __construct() {
		$this->middleware('auth');
	}
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		try {
			$faqs = Faq::with(['categorias', 'tags'])->get();
			return view('Administrator.Faqs.faq')->withFaqs($faqs);
			
		} catch (Exception $e) {
			Session::flash('error', 'Error loading FAQs: ' . $e->getMessage());
			return redirect()->back();
		}
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create() {
		try {
			$tag = Tag::orderBy('name')->pluck('name', 'id');
			$img = DB::table('imagems')->select('id', 'url', 'titulo')->get();
			$items = Categoria::where('categoria_tipo', 'faq')->pluck('titulo', 'id');

			return view('Administrator.Faqs.faq_form')
				->withTag($tag)
				->withCat($items)
				->withImg($img);
				
		} catch (Exception $e) {
			Session::flash('error', 'Error loading create form: ' . $e->getMessage());
			return redirect()->back();
		}
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  FaqRequest  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(FaqRequest $request) {
		try {
			$faq = new Faq;
			$faq->alias       = $request->tituloPT;
			$faq->ativado     = $request->ativado == 'on' ? 1 : 0;
			$faq->destaque    = $request->destaque == 'on' ? 1 : 0;
			$faq->idCategoria = $request->idCategoria;
			$faq->keyword     = $request->keyword;

			$faq->save();

			// Save related data
			Helpers::guardarConteudos($request, $faq);
			Helpers::salvarTags($request, $faq);
			Helpers::criarSlug($request, $faq);

			Session::flash('success', 'FAQ criado com sucesso!');
			return redirect()->route('Faq.index', $faq->id);
			
		} catch (Exception $e) {
			Session::flash('error', 'Error creating FAQ: ' . $e->getMessage());
			return redirect()->back()->withInput();
		}
	}
	/**
	 * Deactivate FAQ
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function despublicar($id) {
		try {
			$faq = Faq::findOrFail($id);
			$faq->ativado = 0;
			$faq->save();

			Session::flash('success', 'FAQ despublicado!');
			return redirect()->route('Faq.index', $faq->id);
			
		} catch (Exception $e) {
			Session::flash('error', 'Error unpublishing FAQ: ' . $e->getMessage());
			return redirect()->back();
		}
	}
	
	/**
	 * Activate FAQ
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function publicar($id) {
		try {
			$faq = Faq::findOrFail($id);
			$faq->ativado = 1;
			$faq->save();

			Session::flash('success', 'FAQ publicado!');
			return redirect()->route('Faq.index', $faq->id);
			
		} catch (Exception $e) {
			Session::flash('error', 'Error publishing FAQ: ' . $e->getMessage());
			return redirect()->back();
		}
	}

	/**
	 * Publish multiple FAQs
	 *
	 * @param  CheckRequest  $ids
	 * @return \Illuminate\Http\Response
	 */
	public function publicarCheck(CheckRequest $ids) {
		try {
			Faq::whereIn('id', $ids->check)->update(['ativado' => 1]);
			Session::flash('success', 'FAQs publicados com sucesso!');
			return redirect()->route('Faq.index');
			
		} catch (Exception $e) {
			Session::flash('error', 'Error publishing FAQs: ' . $e->getMessage());
			return redirect()->back();
		}
	}

	/**
	 * Unpublish multiple FAQs
	 *
	 * @param  CheckRequest  $ids
	 * @return \Illuminate\Http\Response
	 */
	public function despublicarCheck(CheckRequest $ids) {
		try {
			Faq::whereIn('id', $ids->check)->update(['ativado' => 0]);
			Session::flash('success', 'FAQs despublicados com sucesso!');
			return redirect()->route('Faq.index');
			
		} catch (Exception $e) {
			Session::flash('error', 'Error unpublishing FAQs: ' . $e->getMessage());
			return redirect()->back();
		}
	}

	/**
	 * Highlight multiple FAQs
	 *
	 * @param  CheckRequest  $ids
	 * @return \Illuminate\Http\Response
	 */
	public function destaqueCheck(CheckRequest $ids) {
		try {
			Faq::whereIn('id', $ids->check)->update(['destaque' => 1]);
			Session::flash('success', 'FAQs destacados com sucesso!');
			return redirect()->route('Faq.index');
			
		} catch (Exception $e) {
			Session::flash('error', 'Error highlighting FAQs: ' . $e->getMessage());
			return redirect()->back();
		}
	}

	/**
	 * Remove highlight from multiple FAQs
	 *
	 * @param  CheckRequest  $ids
	 * @return \Illuminate\Http\Response
	 */
	public function rdestaqueCheck(CheckRequest $ids) {
		try {
			Faq::whereIn('id', $ids->check)->update(['destaque' => 0]);
			Session::flash('success', 'Destaques removidos com sucesso!');
			return redirect()->route('Faq.index');
			
		} catch (Exception $e) {
			Session::flash('error', 'Error removing highlights: ' . $e->getMessage());
			return redirect()->back();
		}
	}

	/**
	 * Remove multiple FAQs
	 *
	 * @param  CheckRequest  $ids
	 * @return \Illuminate\Http\Response
	 */
	public function removerCheck(CheckRequest $ids) {
		try {
			foreach ($ids->check as $id) {
				$faq = Faq::find($id);
				if ($faq) {
					$faq->tags()->detach();
					$faq->conteudos()->delete();
				}
			}
			Faq::whereIn('id', $ids->check)->delete();

			Session::flash('success', 'FAQs removidos com sucesso!');
			return redirect()->route('Faq.index');
			
		} catch (Exception $e) {
			Session::flash('error', 'Error removing FAQs: ' . $e->getMessage());
			return redirect()->back();
		}
	}
	/**
	 * Highlight FAQ
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destacar($id) {
		try {
			$faq = Faq::findOrFail($id);
			$faq->destaque = 1;
			$faq->save();

			Session::flash('success', 'FAQ destacado!');
			return redirect()->route('Faq.index', $faq->id);
			
		} catch (Exception $e) {
			Session::flash('error', 'Error highlighting FAQ: ' . $e->getMessage());
			return redirect()->back();
		}
	}
	
	/**
	 * Remove highlight from FAQ
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function rdestacar($id) {
		try {
			$faq = Faq::findOrFail($id);
			$faq->destaque = 0;
			$faq->save();

			Session::flash('success', 'Destaque removido!');
			return redirect()->route('Faq.index', $faq->id);
			
		} catch (Exception $e) {
			Session::flash('error', 'Error removing highlight: ' . $e->getMessage());
			return redirect()->back();
		}
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
		try {
			$faq = Faq::findOrFail($id);

			$cont = [];
			$conteudoPT = $faq->conteudos;

			foreach ($conteudoPT as $conteudo) {
				if ($conteudo->languages->tag == "pt") {
					$cont['conteudoPT'] = Purifier::clean($conteudo->texto);
					$cont['tituloPT']   = $conteudo->titulo;
				} else {
					$cont['conteudoEN'] = Purifier::clean($conteudo->texto);
					$cont['tituloEN']   = $conteudo->titulo;
				}
			}

			$tag = Tag::orderBy('name')->pluck('name', 'id');
			$img = DB::table('imagems')->select('id', 'url', 'titulo')->get();
			$items = Categoria::where('categoria_tipo', 'faq')->pluck('titulo', 'id');

			return view('Administrator.Faqs.faq_form')
				->withTag($tag)
				->withFaq($faq)
				->withCat($items)
				->withImg($img)
				->withContent($cont);
				
		} catch (Exception $e) {
			Session::flash('error', 'Error loading edit form: ' . $e->getMessage());
			return redirect()->back();
		}
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  FaqRequest  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(FaqRequest $request, $id) {
		try {
			$faq = Faq::findOrFail($id);

			$faq->alias       = $request->tituloPT;
			$faq->ativado     = $request->ativado == 'on' ? 1 : 0;
			$faq->destaque    = $request->destaque == 'on' ? 1 : 0;
			$faq->idCategoria = $request->idCategoria;
			$faq->keyword     = $request->keyword;

			$conteudos = $faq->conteudos;
			$faq->save();

			// Update related data
			Helpers::criarSlug($request, $faq);
			Helpers::atualizarConteudo($conteudos, $request, $faq);
			Helpers::salvarTags($request, $faq);

			Session::flash('success', 'FAQ atualizado com sucesso!');
			return redirect()->route('Faq.index', $faq->id);
			
		} catch (Exception $e) {
			Session::flash('error', 'Error updating FAQ: ' . $e->getMessage());
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
			$faq = Faq::findOrFail($id);

			// Delete related data
			$faq->tags()->detach();
			$faq->conteudos()->delete();

			// Delete FAQ
			$faq->delete();

			Session::flash('success', 'FAQ apagado!');
			return redirect()->route('Faq.index');
			
		} catch (Exception $e) {
			Session::flash('error', 'Error deleting FAQ: ' . $e->getMessage());
			return redirect()->back();
		}
	}

	/**
	 * Export featured FAQs to JSON
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function export() {
		try {
			$path_faq = storage_path('json/faq.json');
			
			// Delete existing file
			if (File::exists($path_faq)) {
				File::delete($path_faq);
			}

			// Get featured and active FAQs
			$faqs = Faq::with(['conteudos.languages', 'categorias'])
				->where('destaque', 1)
				->where('ativado', 1)
				->limit(7)
				->get();

			// Clean up data for export
			$exportData = $faqs->map(function ($faq) {
				$faqData = $faq->toArray();
				
				// Process conteudos
				if (isset($faqData['conteudos'])) {
					foreach ($faqData['conteudos'] as &$conteudo) {
						// Truncate long text
						if (isset($conteudo['texto']) && strlen($conteudo['texto']) > 100) {
							$conteudo['texto'] = substr($conteudo['texto'], 0, 100) . '...';
						}
					}
				}
				
				return $faqData;
			});

			// Write to file
			file_put_contents($path_faq, json_encode($exportData, JSON_PRETTY_PRINT));

			Session::flash('success', 'FAQs em destaque exportados!');
			return redirect()->route('Faq.index');
			
		} catch (Exception $e) {
			Session::flash('error', 'Error exporting FAQs: ' . $e->getMessage());
			return redirect()->back();
		}
	}
}
