<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Model\Artigo;

//use App\Model\Categoria;
use App\Model\Documento;
use App\Model\Tag;
use App\User;
use Spatie\Activitylog\Models\Activity;

// use DB;
// use File;

// use Purifier;
// use Session;

class AdminController extends Controller {

	public function __construct() {
		$this->middleware('auth');
	}

	public function getIndex() {
		
		$artRec = Artigo::where('ativado','<>',3)->orderBy('created_at', 'desc')->take(7)->get();
		$docRec = Documento::where('ativado','<>',3)->orderBy('created_at', 'desc')->take(7)->get();
		$tagRec = Tag::where('idUser','<>',0)->orderBy('created_at', 'desc')->take(5)->get();
		$users  = User::where('ativado','<>',3)->orderBy('data_last_login', 'desc')->take(5)->get();

		$logs = Activity::with('causer')->latest()->take(5)->get();

		return view('Administrator.admin', compact('artRec', 'docRec', 'tagRec', 'users', 'logs'));
	}

}