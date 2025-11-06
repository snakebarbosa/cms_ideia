<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Model\Configuration;
use Session;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ConfigController extends Controller {
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {

		$config = Configuration::all()->first();

		$val = $config != null ? json_decode($config->config, true) : $config;

		return view('Administrator.Config.webconfig', compact('val'))->withConfig($config);
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
		$validator = Validator::make($request->all(), [
			'valor_campo' => 'required',
			'chave_campo' => 'required',
		]);

		if ($validator->fails()) {
			return redirect()
				->route('Config.index')
				->withErrors($validator)
				->withInput();
		}

		$config = Configuration::all()->first();

		if ($config) {
			$val = json_decode($config->config, true);
			$val[$request->chave_campo] = $request->valor_campo;
			$config->config = json_encode($val);

			$config->save();
		} else {
			$val[$request->chave_campo] = $request->valor_campo;
			$config1 = json_encode($val);
			DB::insert('insert into configurations (config) values (?)', [$config1]);
		}

		Session::flash('success', 'Campo adicionado com sucesso');

		return redirect()->route('Config.index', $config->id);
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
		$config         = Configuration::find($id);
		$data = [];
		$dataRequest = array_values($request->except(['_method', '_token']));

		for ($i = 0; $i < count($dataRequest); $i++) {
			$data += [$dataRequest[$i] => $dataRequest[++$i]];
		}

		$js = json_encode($data);

		$config->config = $js;

		$config->save();

		return redirect()->route('Config.index', $config->id);
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

	public function deleteConfig($key)
	{
		$config = Configuration::all()->first();

		$val = json_decode($config->config, true);

		unset($val[$key]);

		$config->config = json_encode($val);

		$config->save();

		Session::flash('success', 'Campo eliminado com sucesso');

		return redirect()->route('Config.index', $config->id);
	}
}
