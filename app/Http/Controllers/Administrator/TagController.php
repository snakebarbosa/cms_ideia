<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;

use App\Http\Requests\Administrator\TagRequest;

use App\Model\Tag;
use Auth;

use Illuminate\Http\Request;
use Session;

class TagController extends Controller {
	public function __construct() {
		$this->middleware('auth');
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		// $menu   = $this->getMenu();
		// $boinfo = $this->getBOinfo();

		$tag = Tag::all();
		// $lista_json2 = json_encode($tag, true);

		return view('Administrator.Config.tag')->withTag($tag);

	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create() {

		$tags = Tag::orderBy('name')->pluck('name', 'id');

		return view('Administrator.Config.tag_form')->withTags($tags);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(TagRequest $request) {

		$Tag         = new Tag;
		$Tag->name   = $request->name;
		$Tag->idUser = Auth::user()->id;

		$Tag->save();

		if (isset($request->tag)) {
			$Tag->tags()->sync($request->tag, false);
		} else {
			$Tag->tags()->sync(array());
		}

		Session::flash('success', 'Inserção realizada com sucesso!');

		return redirect()->route('Tag.index', $Tag->id);
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
		$tag = Tag::find($id);

		// $menu   = $this->getMenu();
		// $boinfo = $this->getBOinfo();
		$tags   = Tag::all()->pluck('name', 'id');

		return view('Administrator.Config.tag_form')->withTag($tag)->withTags($tags);

	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(TagRequest $request, $id) {

		$Tag = Tag::find($id);

		$Tag->name = $request->name;

		$Tag->save();

		Session::flash('success', 'Dados guardados!');

		if (isset($Tag->tags)) {
			$Tag->tags()->detach();
		}

		if (isset($request->tag)) {
			$Tag->tags()->sync($request->tag, false);
		} else {
			$Tag->tags()->sync(array());
		}

		//$request->session()->flash('alert-success', 'User was successful added!');

		return redirect()->route('Tag.index', $Tag->id);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id) {
		$Tag = Tag::find($id);

		if (isset($Tag->tags)) {
			$Tag->tags()->detach();
		}

		$Tag->delete();

		$Tag->documentos()->detach();

		Session::flash('success', 'Dados apagados!');

		return redirect()->route('Tag.index');
	}

	public function destroyMe($id) {
		$this->destroy($id);
		Session::flash('success', 'Dados apagados!');

		return redirect()->route('Tag.index');
	}

	public function removerCheck(Request $ids) {
		foreach ($ids->check as $id) {
			$tag = Tag::find($id);
			$tag->tags()->detach();
			$tag->artigos()->detach();
			$tag->faqs()->detach();
			$tag->links()->detach();
			$tag->categorias()->detach();
			$tag->items()->detach();
			$tag->documentos()->detach();
		}

		$tag = Tag::whereIn('id', $ids->check)->delete();
		return redirect()->route('Tag.index');
	}

}
