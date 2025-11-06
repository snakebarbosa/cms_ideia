<?php

namespace App\Http\Controllers\Administrator;
use App\Http\Controllers\Controller;
use App\Http\Requests\Administrator\ImagemRequest;
use App\Model\Categoria;
use App\Model\Imagem;
use App\Services\CategoriaService;
use DB;
use Exception;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Image;
use Session;

class ImagemController extends Controller {
	
	protected $categoriaService;
	
	public function __construct(CategoriaService $categoriaService) {
		$this->categoriaService = $categoriaService;
		$this->middleware('auth');
	}
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		try {
			$items = Categoria::where('categoria_tipo', 'imagem')->pluck('titulo', 'id');
			$img   = Imagem::all();
			$tree = $this->categoriaService->getCategoriesTreeLegacy('imagem');

			return view('Administrator.Midia.midia')
				->withCat($items)
				->withData($img)
				->withTree($tree);
				
		} catch (Exception $e) {
			Session::flash('error', 'Error loading images: ' . $e->getMessage());
			return redirect()->back();
		}
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
	 * load list of images for a folder(category).
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function load(Request $request) {
		try {
			$this->validate($request, array(
				'categoria' => 'required',
			));

			$items = Categoria::where('categoria_tipo', 'imagem')->pluck('titulo', 'id');
			$img   = Imagem::select('titulo', 'url', 'id')
				->where('idCategoria', $request->categoria)
				->get();

			return view('Administrator.Midia.midia')
				->withCat($items)
				->withData($img)
				->withSelect($request->categoria);
				
		} catch (Exception $e) {
			Session::flash('error', 'Error loading images: ' . $e->getMessage());
			return redirect()->back();
		}
	}
	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request) {
		try {
			if (!$request->hasFile('file')) {
				Session::flash('warning', 'Não foi possível carregar a imagem!');
				return redirect()->route('Imagem.index');
			}

			$image = $request->file('file');
			$originalName = $image->getClientOriginalName();

			// Check if image with same name already exists
			$existingImage = Imagem::where('titulo', $originalName)->first();
			
			$img = new Imagem;
			$img->ativado     = 1;
			$img->idCategoria = $request->categoria;
			$img->titulo = $existingImage ? $originalName . '.' . time() : $originalName;

			// Store the file
			$path = $request->file('file')->store('images');
			$imgName = explode("/", $path)[1];
			$img->url = $imgName;

			$img->save();

			Session::flash('success', 'Inserção realizada com sucesso!');
			return redirect()->route('Imagem.index', $img->id);

		} catch (Exception $e) {
			Session::flash('error', 'Não foi possível carregar a imagem: ' . $e->getMessage());
			return redirect()->route('Imagem.index');
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
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(ImagemRequest $request, $id) {
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id) {
		try {
			$img = Imagem::findOrFail($id);

			// Delete file from storage
			Storage::delete('images/' . $img->url);

			// Delete database record
			$img->delete();

			Session::flash('success', 'Imagem apagada!');
			return redirect()->route('Imagem.index');

		} catch (Exception $e) {
			Session::flash('error', 'Não foi possível apagar a imagem: ' . $e->getMessage());
			return redirect()->route('Imagem.index');
		}
	}
	/**
	 * Remove Image with intervention (not been used)
	 *
	 * @param  string  $pathImg
	 * @return bool
	 */
	public function destroyImg($pathImg) {
		try {
			$img = Image::make($pathImg);
			$img->destroy();

			Session::flash('success', 'Imagem apagada!' . $pathImg);
			return true;

		} catch (Exception $e) {
			Session::flash('error', 'Não foi possível apagar a imagem: ' . $e->getMessage());
			return false;
		}
	}
	/**
	 * Return images from category via AJAX
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function loadAjax(Request $request) {
		try {
			$imagens = Imagem::where('idCategoria', $request->categoria)->get();
			return response()->json($imagens);
			
		} catch (Exception $e) {
			return response()->json(['error' => $e->getMessage()], 500);
		}
	}
}
