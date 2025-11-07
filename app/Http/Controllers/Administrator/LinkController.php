<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Http\Requests\Administrator\CheckRequest;
use App\Http\Requests\Administrator\LinkRequest;
use App\Model\Categoria;
use App\Model\Link;
use App\Model\Tag;
use Exception;
use File;
use Session;

class LinkController extends Controller {
	
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
			$links = Link::with(['categorias', 'tags', 'contadores'])->get();
			return view('Administrator.Links.link')->withLinks($links);
			
		} catch (Exception $e) {
			Session::flash('error', 'Error loading links: ' . $e->getMessage());
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
			$items = Categoria::where('categoria_tipo', 'link')->pluck('titulo', 'id');

			return view('Administrator.Links.link_form')
				->withTag($tag)
				->withCat($items);
				
		} catch (Exception $e) {
			Session::flash('error', 'Error loading create form: ' . $e->getMessage());
			return redirect()->back();
		}
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  LinkRequest  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(LinkRequest $request) {
		try {
			$link = new Link;
			$link->titulo      = $request->titulo;
			$link->ativado     = $request->ativado == 'on' ? 1 : 0;
			$link->destaque    = $request->destaque == 'on' ? 1 : 0;
			$link->url         = $request->url;
			$link->idCategoria = $request->idCategoria;

			$link->save();

			// Sync tags
			$link->tags()->sync($request->tag ?? []);

			Session::flash('success', 'Link criado com sucesso!');
			return redirect()->route('Link.index', $link->id);
			
		} catch (Exception $e) {
			Session::flash('error', 'Error creating link: ' . $e->getMessage());
			return redirect()->back()->withInput();
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
	 * Deactivate link
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function despublicar($id) {
		try {
			$link = Link::findOrFail($id);
			$link->ativado = 0;
			$link->save();

			Session::flash('success', 'Link despublicado!');
			return redirect()->route('Link.index', $link->id);
			
		} catch (Exception $e) {
			Session::flash('error', 'Error unpublishing link: ' . $e->getMessage());
			return redirect()->back();
		}
	}
	
	/**
	 * Activate link
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function publicar($id) {
		try {
			$link = Link::findOrFail($id);
			$link->ativado = 1;
			$link->save();

			Session::flash('success', 'Link publicado!');
			return redirect()->route('Link.index', $link->id);
			
		} catch (Exception $e) {
			Session::flash('error', 'Error publishing link: ' . $e->getMessage());
			return redirect()->back();
		}
	}
	
	/**
	 * Highlight link
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destacar($id) {
		try {
			$link = Link::findOrFail($id);
			$link->destaque = 1;
			$link->save();

			Session::flash('success', 'Link destacado!');
			return redirect()->route('Link.index', $link->id);
			
		} catch (Exception $e) {
			Session::flash('error', 'Error highlighting link: ' . $e->getMessage());
			return redirect()->back();
		}
	}
	
	/**
	 * Remove highlight from link
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function rdestacar($id) {
		try {
			$link = Link::findOrFail($id);
			$link->destaque = 0;
			$link->save();

			Session::flash('success', 'Destaque removido!');
			return redirect()->route('Link.index', $link->id);
			
		} catch (Exception $e) {
			Session::flash('error', 'Error removing highlight: ' . $e->getMessage());
			return redirect()->back();
		}
	}

	/**
	 * Publish multiple links
	 *
	 * @param  CheckRequest  $ids
	 * @return \Illuminate\Http\Response
	 */
	public function publicarCheck(CheckRequest $ids) {
		try {
			Link::whereIn('id', $ids->check)->update(['ativado' => 1]);
			Session::flash('success', 'Links publicados com sucesso!');
			return redirect()->route('Link.index');
			
		} catch (Exception $e) {
			Session::flash('error', 'Error publishing links: ' . $e->getMessage());
			return redirect()->back();
		}
	}

	/**
	 * Unpublish multiple links
	 *
	 * @param  CheckRequest  $ids
	 * @return \Illuminate\Http\Response
	 */
	public function despublicarCheck(CheckRequest $ids) {
		try {
			Link::whereIn('id', $ids->check)->update(['ativado' => 0]);
			Session::flash('success', 'Links despublicados com sucesso!');
			return redirect()->route('Link.index');
			
		} catch (Exception $e) {
			Session::flash('error', 'Error unpublishing links: ' . $e->getMessage());
			return redirect()->back();
		}
	}

	/**
	 * Highlight multiple links
	 *
	 * @param  CheckRequest  $ids
	 * @return \Illuminate\Http\Response
	 */
	public function destaqueCheck(CheckRequest $ids) {
		try {
			Link::whereIn('id', $ids->check)->update(['destaque' => 1]);
			Session::flash('success', 'Links destacados com sucesso!');
			return redirect()->route('Link.index');
			
		} catch (Exception $e) {
			Session::flash('error', 'Error highlighting links: ' . $e->getMessage());
			return redirect()->back();
		}
	}

	/**
	 * Remove highlight from multiple links
	 *
	 * @param  CheckRequest  $ids
	 * @return \Illuminate\Http\Response
	 */
	public function rdestaqueCheck(CheckRequest $ids) {
		try {
			Link::whereIn('id', $ids->check)->update(['destaque' => 0]);
			Session::flash('success', 'Destaques removidos com sucesso!');
			return redirect()->route('Link.index');
			
		} catch (Exception $e) {
			Session::flash('error', 'Error removing highlights: ' . $e->getMessage());
			return redirect()->back();
		}
	}

	/**
	 * Remove multiple links
	 *
	 * @param  CheckRequest  $ids
	 * @return \Illuminate\Http\Response
	 */
	public function removerCheck(CheckRequest $ids) {
		try {
			foreach ($ids->check as $id) {
				$link = Link::find($id);
				if ($link) {
					$link->tags()->detach();
				}
			}
			Link::whereIn('id', $ids->check)->delete();
			
			Session::flash('success', 'Links removidos com sucesso!');
			return redirect()->route('Link.index');
			
		} catch (Exception $e) {
			Session::flash('error', 'Error removing links: ' . $e->getMessage());
			return redirect()->back();
		}
	}
	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id) {
		try {
			$link = Link::findOrFail($id);
			$tag = Tag::orderBy('name')->pluck('name', 'id');
			$items = Categoria::where('categoria_tipo', 'link')->pluck('titulo', 'id');

			return view('Administrator.Links.link_form')
				->withLink($link)
				->withTag($tag)
				->withCat($items);
				
		} catch (Exception $e) {
			Session::flash('error', 'Error loading edit form: ' . $e->getMessage());
			return redirect()->back();
		}
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  LinkRequest  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(LinkRequest $request, $id) {
		try {
			$link = Link::findOrFail($id);
			$link->titulo      = $request->titulo;
			$link->ativado     = $request->ativado == 'on' ? 1 : 0;
			$link->destaque    = $request->destaque == 'on' ? 1 : 0;
			$link->url         = $request->url;
			$link->idCategoria = $request->idCategoria;

			$link->save();

			// Sync tags
			$link->tags()->sync($request->tag ?? []);

			Session::flash('success', 'Link atualizado com sucesso!');
			return redirect()->route('Link.index', $link->id);
			
		} catch (Exception $e) {
			Session::flash('error', 'Error updating link: ' . $e->getMessage());
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
			$link = Link::findOrFail($id);
			
			// Detach tags
			$link->tags()->detach();
			
			// Delete link
			$link->delete();

			Session::flash('success', 'Link apagado!');
			return redirect()->route('Link.index');
			
		} catch (Exception $e) {
			Session::flash('error', 'Error deleting link: ' . $e->getMessage());
			return redirect()->back();
		}
	}

	/**
	 * Export featured links to JSON
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function export() {
		try {
			$path_link = storage_path('json/links.json');
			
			// Delete existing file
			if (File::exists($path_link)) {
				File::delete($path_link);
			}

			// Get featured and active links
			$links = Link::with('categorias')
				->where('destaque', 1)
				->where('ativado', 1)
				->get();

			// Clean up data for export
			$exportData = $links->map(function ($link) {
				$linkData = $link->toArray();
				
				// Remove unnecessary fields from link
				unset($linkData['destaque']);
				unset($linkData['updated_at']);
				unset($linkData['ativado']);
				
				// Clean up categoria data
				if (isset($linkData['categorias'])) {
					$categoria = $linkData['categorias'];
					unset($categoria['updated_at']);
					unset($categoria['created_at']);
					unset($categoria['ativado']);
					unset($categoria['categoria_tipo']);
					unset($categoria['parent']);
					unset($categoria['default']);
					unset($categoria['order']);
					
					$linkData['categorias'] = $categoria;
				}
				
				return $linkData;
			});

			// Write to file
			file_put_contents($path_link, json_encode($exportData, JSON_PRETTY_PRINT));

			Session::flash('success', 'Links em destaque exportados!');
			return redirect()->route('Link.index');
			
		} catch (Exception $e) {
			Session::flash('error', 'Error exporting links: ' . $e->getMessage());
			return redirect()->back();
		}
	}
}
