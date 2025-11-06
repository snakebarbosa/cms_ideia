<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

use App\Model\Artigo;
use App\Model\Documento;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

Route::get('/artigo/10002', function () {
	return view('home');
});


/***Administrator****/

Route::group(['namespace' => 'Administrator'], function () {

	Route::get('/Administrator', 'AdminController@getIndex');
	Route::resource('/Administrator/Config', 'ConfigController');
	Route::resource('/Administrator/Categoria', 'CategoriaController');
	Route::resource('/Administrator/Tipo', 'TipoController');
	Route::resource('/Administrator/User', 'UserController');
	Route::resource('/Administrator/Artigo', 'ArtigoController');
	Route::resource('/Administrator/Conteudo', 'ConteudoController');
	Route::resource('/Administrator/Documento', 'DocumentoController');
	Route::resource('/Administrator/Evento', 'EventoController');
	Route::resource('/Administrator/Imagem', 'ImagemController');
	Route::resource('/Administrator/Language', 'LanguageController');
	Route::resource('/Administrator/Contador', 'ContadorController');
	Route::resource('/Administrator/Tag', 'TagController');
	Route::resource('/Administrator/Item', 'ItemController');
	Route::resource('/Administrator/Slide', 'SlideController');
	Route::resource('/Administrator/Faq', 'FaqController');
	Route::resource('/Administrator/Link', 'LinkController');
	Route::resource('/Administrator/Parceiro', 'ParceiroController');
	Route::resource('/Administrator/Log', 'ActivityLogController');

	Route::get('/Administrator/Config/Webconfig', 'AdminController@getWebconfig');
	Route::get('/Administrator/Config/DeleteConfig/{key}', 'ConfigController@deleteConfig')->name('config.delete');

	/***Administrator/Config****/

	Route::group(['prefix' => '/Administrator/Tipo/', 'middleware' => ['role:superadministrator']], function () {

		Route::get('delete/{id}/{type}', 'TipoController@destroyMe');
		Route::get('despublicar/{id}', 'TipoController@despublicar');
		Route::get('publicar/{id}', 'TipoController@publicar');
		Route::post('publicarCheck', 'TipoController@publicarCheck')->name('Menu.publicarcheck');
		Route::post('despublicarCheck', 'TipoController@despublicarCheck')->name('Menu.despublicarcheck');
		Route::post('removerCheck', 'TipoController@removerCheck')->name('Menu.removercheck');
	});

	Route::group(['prefix' => '/Administrator/Language/'], function () {

		Route::get('delete/{id}', 'LanguageController@destroy');
		Route::get('despublicar/{id}', 'LanguageController@despublicar');
		Route::get('publicar/{id}', 'LanguageController@publicar');
		Route::post('publicarCheck', 'LanguageController@publicarCheck')->name('Lang.publicarcheck');
		Route::post('despublicarCheck', 'LanguageController@despublicarCheck')->name('Lang.despublicarcheck');
		Route::post('removerCheck', 'LanguageController@removerCheck')->name('Lang.removercheck');
	});

	Route::get('/Administrator/Tipo/gettabletipo', 'TipoController@getTable')->name('gettabletipo');
	Route::get('/Administrator/Config/Info', 'AdminController@getInfo');
	Route::get('/Administrator/Tag/delete/{id}', 'TagController@destroyMe');
	Route::post('/Administrator/Tag/removerCheck', 'TagController@removerCheck')->name('Tag.removercheck');

	/***Administrator/Artigo****/
	Route::group(['prefix' => '/Administrator/Artigo/'], function () {

		Route::get('delete/{id}', 'ArtigoController@destroy');
		Route::get('despublicar/{id}', 'ArtigoController@despublicar');
		Route::get('publicar/{id}', 'ArtigoController@publicar');
		Route::get('destacar/{id}', 'ArtigoController@destacar');
		Route::get('rdestacar/{id}', 'ArtigoController@rdestacar');
		Route::post('publicarCheck', 'ArtigoController@publicarCheck')->name('Art.publicarcheck');
		Route::post('despublicarCheck', 'ArtigoController@despublicarCheck')->name('Art.despublicarcheck');
		Route::post('destaqueCheck', 'ArtigoController@destaqueCheck')->name('Art.destaquecheck');
		Route::post('rdestaqueCheck', 'ArtigoController@rdestaqueCheck')->name('Art.rdestaquecheck');
		Route::post('removerCheck', 'ArtigoController@removerCheck')->name('Art.removercheck');
		Route::post('search', 'ArtigoController@search')->name('Art.search');
	});

	//Route::get('/Administrator/Artigos/export', 'ArtigoController@export')->name('Artigo.export');
	Route::get('/Administrator/Artigos/sluging', 'DocumentoController@createSlugAll');

	Route::group(['prefix' => '/Administrator/Categoria/'], function () {

		Route::post('delete/{id}/{type}', 'CategoriaController@destroyMe');
		Route::get('despublicar/{id}/{type}', 'CategoriaController@despublicar');
		Route::get('publicar/{id}/{type}', 'CategoriaController@publicar');
		Route::get('categoriaart', 'CategoriaController@showCatArt')->name('showCatArt');
		Route::get('{id}/up', 'CategoriaController@downOrder');
		Route::get('{id}/down', 'CategoriaController@upOrder');
		Route::get('create/{tipo}', 'CategoriaController@create');
	});

	/***Administrator/Documentacao****/
	Route::group(['prefix' => '/Administrator/Documentacao/'], function () {

		Route::get('categoria', 'CategoriaController@catDoc')->name('Documentacao.categoria');
		Route::get('{id}/{type}/editcat', 'CategoriaController@editCat');
		Route::get('createDoc', 'CategoriaController@createDoc');
		Route::get('delete/{id}', 'DocumentoController@destroy');
		Route::get('despublicar/{id}', 'DocumentoController@despublicar');
		Route::get('publicar/{id}', 'DocumentoController@publicar');
		Route::get('destacar/{id}', 'DocumentoController@destacar');
		Route::get('rdestacar/{id}', 'DocumentoController@rdestacar');
		Route::get('export', 'DocumentoController@export')->name('Documentacao.export');
		Route::get('tipo', 'TipoController@iDoc')->name('Tipo.idoc');
		Route::get('createtipo', 'TipoController@createDoc');
		Route::post('publicarCheck', 'DocumentoController@publicarCheck')->name('Doc.publicarcheck');
		Route::post('despublicarCheck', 'DocumentoController@despublicarCheck')->name('Doc.despublicarcheck');
		Route::post('destaqueCheck', 'DocumentoController@destaqueCheck')->name('Doc.destaquecheck');
		Route::post('rdestaqueCheck', 'DocumentoController@rdestaqueCheck')->name('Doc.rdestaquecheck');
		Route::post('removerCheck', 'DocumentoController@removerCheck')->name('Doc.removercheck');
	});

	/***Administrator/Evento***/
	Route::group(['prefix' => '/Administrator/Evento/'], function () {
		Route::get('formulario/{id}', 'EventoController@getFormulario');
		Route::get('despublicar/{id}', 'EventoController@despublicar');
		Route::get('publicar/{id}', 'EventoController@publicar');
		Route::get('delete/{id}', 'EventoController@destroy');
		Route::get('destacar/{id}', 'EventoController@destacar');
		Route::get('rdestacar/{id}', 'EventoController@rdestacar');
		Route::post('publicarCheck', 'EventoController@publicarCheck')->name('Evento.publicarcheck');
		Route::post('despublicarCheck', 'EventoController@despublicarCheck')->name('Evento.despublicarcheck');
		Route::post('destaqueCheck', 'EventoController@destaqueCheck')->name('Evento.destaquecheck');
		Route::post('rdestaqueCheck', 'EventoController@rdestaqueCheck')->name('Evento.rdestaquecheck');
		Route::post('removerCheck', 'EventoController@removerCheck')->name('Evento.removercheck');
	});

	Route::group(['prefix' => '/Administrator/Eventos/'], function () {
		Route::post('setFormulario', 'EventoController@setFormulario')->name('Evento.setFormulario');
		Route::get('categoria', 'CategoriaController@catEvento')->name('Evento.categoria');
		Route::get('{id}/{type}/editcat', 'CategoriaController@editCat');
		Route::get('createevento', 'CategoriaController@createEvento');
	});

	Route::group(['prefix' => '/Administrator/Item/', 'middleware' => ['role:superadministrator']], function () {
		//Route::get('/Administrator/Menus/tipo', 'TipoController@iMenu')->name('Tipo.imenu');
		//Route::post('/Administrator/Menus/refresh', 'ItemController@refresh')->name('Item.refresh');
		//Route::get('/Administrator/Menus/createtipo', 'TipoController@createMenu');
		Route::post('getOptionsMenu', 'ItemController@getOptionsMenu')->name('Item.getOptionsMenu');
		Route::get('despublicar/{id}', 'ItemController@despublicar');
		Route::get('publicar/{id}', 'ItemController@publicar');
		Route::get('{id}/up', 'ItemController@downOrder');
		Route::get('{id}/down', 'ItemController@upOrder');
		// Route::get('/Administrator/Item/delete/{id}', 'ItemController@destroy');
		// Route::post('/Administrator/Item/publicarCheck', 'ItemController@publicarCheck')->name('Item.publicarcheck');
		// Route::post('/Administrator/Item/despublicarCheck', 'ItemController@despublicarCheck')->name('Item.despublicarcheck');
		// Route::post('/Administrator/Item/removerCheck', 'ItemController@removerCheck')->name('Item.removercheck');
	});

	/***Administrator/Midia****/
	Route::group(['prefix' => '/Administrator/Midia/'], function () {

		Route::get('categoria', 'CategoriaController@catImg')->name('Midia.categoria');
		Route::get('{id}/{type}/editcat', 'CategoriaController@editCat');
		Route::get('createImg', 'CategoriaController@createImg')->name('Midia.create');
		Route::post('load', 'ImagemController@load')->name('Midia.load');
		Route::get('loadAjax', 'ImagemController@loadAjax')->name('Midia.loadAjax');
	});

	/***Administrator/Modulo****/
	Route::get('/Administrator/Modulo/Documento', 'AdminController@getModDoc');
	Route::get('/Administrator/Modulo/Galeria', 'AdminController@getModGal');

	Route::get('/Administrator/Slide/despublicar/{id}', 'SlideController@despublicar');
	Route::get('/Administrator/Slide/publicar/{id}', 'SlideController@publicar');
	Route::get('/Administrator/Slide/delete/{id}', 'SlideController@destroy');
	Route::get('/Administrator/Slides/export', 'SlideController@export')->name('Slide.export');
	Route::post('/Administrator/Slides/removerCheck', 'SlideController@removerCheck')->name('Slide.removercheck');
	Route::post('/Administrator/Slides/publicarCheck', 'SlideController@publicarCheck')->name('Slide.publicarcheck');
	Route::post('/Administrator/Slides/despublicarCheck', 'SlideController@despublicarCheck')->name('Slide.despublicarcheck');
	Route::get('/Administrator/Slide/ordenar/{id}/{order}', 'SlideController@mudarOrdem')->name('slide.ordenar');

	Route::group(['prefix' => '/Administrator/Parceiro/'], function () {

		Route::get('despublicar/{id}', 'ParceiroController@despublicar');
		Route::get('publicar/{id}', 'ParceiroController@publicar');
		Route::get('delete/{id}', 'ParceiroController@destroy');
		Route::post('publicarCheck', 'ParceiroController@publicarCheck')->name('Parceiro.publicarcheck');
		Route::post('despublicarCheck', 'ParceiroController@despublicarCheck')->name('Parceiro.despublicarcheck');
		Route::post('removerCheck', 'ParceiroController@removerCheck')->name('Parceiro.removercheck');
	});

	Route::get('/Administrator/Parceiros/export', 'ParceiroController@export')->name('Parceiro.export');

	Route::group(['prefix' => '/Administrator/Faq/'], function () {

		Route::get('despublicar/{id}', 'FaqController@despublicar');
		Route::get('publicar/{id}', 'FaqController@publicar');
		Route::get('delete/{id}', 'FaqController@destroy');
		Route::get('destacar/{id}', 'FaqController@destacar');
		Route::get('rdestacar/{id}', 'FaqController@rdestacar');
		Route::post('publicarCheck', 'FaqController@publicarCheck')->name('Faq.publicarcheck');
		Route::post('despublicarCheck', 'FaqController@despublicarCheck')->name('Faq.despublicarcheck');
		Route::post('destaqueCheck', 'FaqController@destaqueCheck')->name('Faq.destaquecheck');
		Route::post('rdestaqueCheck', 'FaqController@rdestaqueCheck')->name('Faq.rdestaquecheck');
		Route::post('removerCheck', 'FaqController@removerCheck')->name('Faq.removercheck');
	});

	Route::group(['prefix' => '/Administrator/Faqs/'], function () {

		Route::get('categoria', 'CategoriaController@catFaq')->name('Faq.categoria');
		Route::get('{id}/{type}/editcat', 'CategoriaController@editCat');
		Route::get('createfaq', 'CategoriaController@createFaq');
		Route::get('export', 'FaqController@export')->name('Faq.export');
	});

	Route::group(['prefix' => '/Administrator/Link/'], function () {

		Route::get('despublicar/{id}', 'LinkController@despublicar');
		Route::get('publicar/{id}', 'LinkController@publicar');
		Route::get('delete/{id}', 'LinkController@destroy');
		Route::get('destacar/{id}', 'LinkController@destacar');
		Route::get('rdestacar/{id}', 'LinkController@rdestacar');
		Route::post('publicarCheck', 'LinkController@publicarCheck')->name('Link.publicarcheck');
		Route::post('despublicarCheck', 'LinkController@despublicarCheck')->name('Link.despublicarcheck');
		Route::post('destaqueCheck', 'LinkController@destaqueCheck')->name('Link.destaquecheck');
		Route::post('rdestaqueCheck', 'LinkController@rdestaqueCheck')->name('Link.rdestaquecheck');
		Route::post('removerCheck', 'LinkController@removerCheck')->name('Link.removercheck');
	});

	Route::group(['prefix' => '/Administrator/Links/'], function () {

		Route::get('categoria', 'CategoriaController@catLink')->name('Link.categoria');
		Route::get('{id}/{type}/editcat', 'CategoriaController@editCat');
		Route::get('createlink', 'CategoriaController@createLink');
		Route::get('export', 'LinkController@export')->name('Link.export');
	});

	Route::group(['prefix' => '/Administrator/Modulo/'], function () {

		Route::get('Video', 'ModuloController@iVideo')->name('Video.ivideo');
		Route::get('Menu', 'ModuloController@iMenu')->name('Menu.imenu');
		Route::get('Info', 'AdminController@getModInfo');
		Route::get('AddMenu', 'AdminController@getModAddMenu');
	});

	Route::post('/Administrator/Video/store', 'ModuloController@storeVideo')->name('Video.storevideo');
	Route::post('/Administrator/Menu/storemenu', 'ModuloController@storeMenu')->name('Menu.storemenu');

	Route::group(['prefix' => 'Administrator/user', 'middleware' => ['role:superadministrator|administrator']], function () {
		Route::get('/Nav/historico/{id}', 'UserController@historico');
		Route::post('/ativar', 'UserController@ativarCheck')->name('User.ativarcheck');
		Route::post('/desactivar', 'UserController@desativarCheck')->name('User.desativarcheck');
		Route::get('/estado/{id}/{estado}', 'UserController@updateEstado');
		Route::post('removerCheck', 'UserController@removerCheck')->name('User.removercheck');

		Route::get('/create', 'UserController@create');
		Route::get('/{id}/edit', 'UserController@edit')->name('User.edit');
		Route::get('/{id}', 'UserController@show');
		Route::get('/resetpassworduser/{id}', 'UserController@resetPasswordUser')->name('user.resetpassword');
	});
});

/**********FrontEnd**********/
Route::get('/artigo/teste', 'PagesController@getArtigoTeste');
Route::get('/setLanguage/{id}', 'PagesController@setLanguage');
Route::get('/', 'PagesController@getIndex')->name('Pages.index');
Route::get('/home', 'PagesController@getIndex');
Route::get('/nav/{idTag1}/{idTag2?}/{idTag3?}', 'PagesController@getNav');

Route::get('/documento/{id}', 'PagesController@getDocumento');
Route::get('/documento/opendoc/{id}', 'PagesController@openDocumento');
Route::get('/navdoc/{idPasta}', 'PagesController@navDocumento');
Route::get('/dash', 'PagesController@getDash');
Route::get('/artigos/{slug}', 'PagesController@getArtigoSlug')->name('slug');

Route::get('/artigo/{id}', 'PagesController@getArtigo')->name('artigo');
Route::get('/navart/{idPasta}', 'PagesController@navArtigo');
Route::get('/navsite/{slug}/{type}', 'PagesController@navCatSlug')->name('slugnavcat');


Route::get('/artigomenu/{id}', 'PagesController@getArtigoMenu');
Route::get('/artigosmenu/{slug}', 'PagesController@getArtigoMenuSlug')->name('slugartigo');
Route::get('/documentos/{slug}', 'PagesController@getDocSlug')->name('slugdoc');


Route::get('/faq/{id}', 'PagesController@getFaq');
Route::get('/navfaq/{idPasta}', 'PagesController@navFaq');
Route::get('/faqs/{slug}', 'PagesController@getFaqSlug')->name('slugfaq');

Route::get('/link/{id}', 'PagesController@clickLink')->name('link.click');

Route::get('Contact', 'PagesController@getContact')->name('Pages.contacto');

Route::post('Contact', 'PagesController@postContact')->name('Pages.postContact');
Route::get('/organigrama', 'PagesController@getOrg');
Route::get('About', 'PagesController@getAbout');

Route::get('/navtag/{idTag}', 'PagesController@getTagNav');
Route::get('/search', 'PagesController@getSearch')->name('Pages.getSearch');
// Route::get('/sresult', 'PagesController@navSearch')->name('Pages.search');
Route::get('files/{path}/{file}', ['as' => 'files', 'uses' => 'PagesController@files']);
Route::post('/newslletter', 'PagesController@save_email_newsletter');
Route::group(['middleware' => ['web']], function () {
});

// Route::controller('datatables', 'DatatablesController', [
//     'anyData'  => 'datatables.data',
//     'getIndex' => 'datatables',
// ]);

Auth::routes();

//Route::get('/home', 'HomeController@index')->name('home');

Route::match(['get', 'post'], 'register', function () {
	return redirect('/');
});

Route::get('/clear', function () {
	Artisan::call('cache:clear');
	Artisan::call('config:cache');
	return 'DONE'; //Return anything
});


// Route::get('/clear-cache', function () {

// 	// Artisan::call('view:clear');
// 	// Artisan::call('key:generate');
// 	Artisan::call('cache:clear');
// 	Artisan::call('config:cache');
// 	return 'DONE'; //Return anything
// });


// Route::get('/criacao', function () {
// 	$a = Artigo::all();
// 	foreach ($a as $art) {
// 		DB::table('artigos')->where('id', $art->id)->update(['data_criacao' => $art->created_at]);
// 	}

// 	$d = Documento::all();
// 	foreach ($d as $doc) {
// 		DB::table('documentos')->where('id', $doc->id)->update(['data_criacao' => $doc->created_at]);
// 	}
// });

// Route::get('/changes-db', function () {
// 	Schema::table('artigos', function () {
// 		DB::statement('ALTER TABLE artigos ADD COLUMN data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP');
// 	});
// 	Schema::table('documentos', function () {
// 		DB::statement('ALTER TABLE documentos ADD COLUMN data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP');
// 	});

// 	$a = Artigo::all();
// 	foreach ($a as $art) {
// 		DB::table('artigos')->where('id', $art->id)->update(['data_criacao' => $art->created_at]);
// 	}

// 	$d = Documento::all();
// 	foreach ($d as $doc) {
// 		DB::table('documentos')->where('id', $doc->id)->update(['data_criacao' => $doc->created_at]);
// 	}
// });