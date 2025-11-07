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
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

// Administrator Controllers
use App\Http\Controllers\Administrator\{
    ActivityLogController,
    AdminController,
    ArtigoController,
    CategoriaController,
    ConfigController,
    ContadorController,
    ConteudoController,
    DocumentoController,
    EventoController,
    FaqController,
    ImagemController,
    ItemController,
    LanguageController,
    LinkController,
    ModuloController,
    ParceiroController,
    SlideController,
    TagController,
    TipoController,
    UserController,
};

// Frontend Controllers
use App\Http\Controllers\PagesController;

Route::get('/artigo/10002', function () {
	return view('home');
});


/***Administrator****/

Route::prefix('Administrator')->group(function () {

	Route::get('/', [AdminController::class, 'getIndex']);
	Route::resource('/Config', ConfigController::class);
	Route::resource('/Categoria', CategoriaController::class);
	Route::resource('/Tipo', TipoController::class);
	Route::resource('/User', UserController::class);
	Route::resource('/Artigo', ArtigoController::class);
	Route::resource('/Conteudo', ConteudoController::class);
	Route::resource('/Documento', DocumentoController::class);
	Route::resource('/Evento', EventoController::class);
	Route::resource('/Imagem', ImagemController::class);
	Route::resource('/Language', LanguageController::class);
	Route::resource('/Contador', ContadorController::class);
	Route::resource('/Tag', TagController::class);
	Route::resource('/Item', ItemController::class);
	Route::resource('/Slide', SlideController::class);
	Route::resource('/Faq', FaqController::class);
	Route::resource('/Link', LinkController::class);
	Route::resource('/Parceiro', ParceiroController::class);
	Route::resource('/Log', ActivityLogController::class);

	Route::get('/Config/Webconfig', [AdminController::class, 'getWebconfig']);
	Route::get('/Config/DeleteConfig/{key}', [ConfigController::class, 'deleteConfig'])->name('config.delete');

	/***Administrator/Config****/

	Route::group(['prefix' => '/Tipo/', 'middleware' => ['role:superadministrator']], function () {

		Route::get('delete/{id}/{type}', [TipoController::class, 'destroyMe']);
		Route::get('despublicar/{id}', [TipoController::class, 'despublicar']);
		Route::get('publicar/{id}', [TipoController::class, 'publicar']);
		Route::post('publicarCheck', [TipoController::class, 'publicarCheck'])->name('Menu.publicarcheck');
		Route::post('despublicarCheck', [TipoController::class, 'despublicarCheck'])->name('Menu.despublicarcheck');
		Route::post('removerCheck', [TipoController::class, 'removerCheck'])->name('Menu.removercheck');
	});

	Route::group(['prefix' => '/Language/'], function () {

		Route::get('delete/{id}', [LanguageController::class, 'destroy']);
		Route::get('despublicar/{id}', [LanguageController::class, 'despublicar']);
		Route::get('publicar/{id}', [LanguageController::class, 'publicar']);
		Route::post('publicarCheck', [LanguageController::class, 'publicarCheck'])->name('Lang.publicarcheck');
		Route::post('despublicarCheck', [LanguageController::class, 'despublicarCheck'])->name('Lang.despublicarcheck');
		Route::post('removerCheck', [LanguageController::class, 'removerCheck'])->name('Lang.removercheck');
	});

	Route::get('/Tipo/gettabletipo', [TipoController::class, 'getTable'])->name('gettabletipo');
	Route::get('/Config/Info', [AdminController::class, 'getInfo']);
	Route::get('/Tag/delete/{id}', [TagController::class, 'destroyMe']);
	Route::post('/Tag/removerCheck', [TagController::class, 'removerCheck'])->name('Tag.removercheck');

	/***Administrator/Artigo****/
	Route::get('/Artigos/json-list', [ArtigoController::class, 'getArtigosJson'])->name('Art.getjson');
	Route::group(['prefix' => '/Artigo/'], function () {

		
		Route::get('delete/{id}', [ArtigoController::class, 'destroy']);
		Route::get('despublicar/{id}', [ArtigoController::class, 'despublicar']);
		Route::get('publicar/{id}', [ArtigoController::class, 'publicar']);
		Route::get('destacar/{id}', [ArtigoController::class, 'destacar']);
		Route::get('rdestacar/{id}', [ArtigoController::class, 'rdestacar']);
		Route::post('publicarCheck', [ArtigoController::class, 'publicarCheck'])->name('Art.publicarcheck');
		Route::post('despublicarCheck', [ArtigoController::class, 'despublicarCheck'])->name('Art.despublicarcheck');
		Route::post('destaqueCheck', [ArtigoController::class, 'destaqueCheck'])->name('Art.destaquecheck');
		Route::post('rdestaqueCheck', [ArtigoController::class, 'rdestaqueCheck'])->name('Art.rdestaquecheck');
		Route::post('removerCheck', [ArtigoController::class, 'removerCheck'])->name('Art.removercheck');
		Route::post('search', [ArtigoController::class, 'search'])->name('Art.search');
	});

	//Route::get('/Administrator/Artigos/export', [ArtigoController::class, 'export'])->name('Artigo.export');
	Route::get('/Artigos/sluging', [DocumentoController::class, 'createSlugAll']);

	Route::group(['prefix' => '/Categoria/'], function () {

		Route::post('delete/{id}/{type}', [CategoriaController::class, 'destroyMe']);
		Route::get('despublicar/{id}/{type}', [CategoriaController::class, 'despublicar']);
		Route::get('publicar/{id}/{type}', [CategoriaController::class, 'publicar']);
		Route::get('categoriaart', [CategoriaController::class, 'showCatArt'])->name('showCatArt');
		Route::get('{id}/up', [CategoriaController::class, 'downOrder']);
		Route::get('{id}/down', [CategoriaController::class, 'upOrder']);
		Route::get('create/{tipo}', [CategoriaController::class, 'create']);
	});

	/***Administrator/Documentacao****/
	Route::group(['prefix' => '/Documentacao/'], function () {

		Route::get('categoria', [CategoriaController::class, 'catDoc'])->name('Documentacao.categoria');
		Route::get('{id}/{type}/editcat', [CategoriaController::class, 'editCat']);
		Route::get('createDoc', [CategoriaController::class, 'createDoc']);
		Route::get('json-list', [DocumentoController::class, 'getDocumentosJson'])->name('Doc.getjson');
		Route::get('delete/{id}', [DocumentoController::class, 'destroy']);
		Route::get('despublicar/{id}', [DocumentoController::class, 'despublicar']);
		Route::get('publicar/{id}', [DocumentoController::class, 'publicar']);
		Route::get('destacar/{id}', [DocumentoController::class, 'destacar']);
		Route::get('rdestacar/{id}', [DocumentoController::class, 'rdestacar']);
		Route::get('export', [DocumentoController::class, 'export'])->name('Documentacao.export');
		Route::get('tipo', [TipoController::class, 'iDoc'])->name('Tipo.idoc');
		Route::get('createtipo', [TipoController::class, 'createDoc']);
		Route::post('publicarCheck', [DocumentoController::class, 'publicarCheck'])->name('Doc.publicarcheck');
		Route::post('despublicarCheck', [DocumentoController::class, 'despublicarCheck'])->name('Doc.despublicarcheck');
		Route::post('destaqueCheck', [DocumentoController::class, 'destaqueCheck'])->name('Doc.destaquecheck');
		Route::post('rdestaqueCheck', [DocumentoController::class, 'rdestaqueCheck'])->name('Doc.rdestaquecheck');
		Route::post('removerCheck', [DocumentoController::class, 'removerCheck'])->name('Doc.removercheck');
	});

	/***Administrator/Evento***/
	Route::group(['prefix' => '/Evento/'], function () {
		Route::get('formulario/{id}', [EventoController::class, 'getFormulario']);
		Route::get('despublicar/{id}', [EventoController::class, 'despublicar']);
		Route::get('publicar/{id}', [EventoController::class, 'publicar']);
		Route::get('delete/{id}', [EventoController::class, 'destroy']);
		Route::get('destacar/{id}', [EventoController::class, 'destacar']);
		Route::get('rdestacar/{id}', [EventoController::class, 'rdestacar']);
		Route::post('publicarCheck', [EventoController::class, 'publicarCheck'])->name('Evento.publicarcheck');
		Route::post('despublicarCheck', [EventoController::class, 'despublicarCheck'])->name('Evento.despublicarcheck');
		Route::post('destaqueCheck', [EventoController::class, 'destaqueCheck'])->name('Evento.destaquecheck');
		Route::post('rdestaqueCheck', [EventoController::class, 'rdestaqueCheck'])->name('Evento.rdestaquecheck');
		Route::post('removerCheck', [EventoController::class, 'removerCheck'])->name('Evento.removercheck');
	});

	Route::group(['prefix' => '/Eventos/'], function () {
		Route::post('setFormulario', [EventoController::class, 'setFormulario'])->name('Evento.setFormulario');
		Route::get('categoria', [CategoriaController::class, 'catEvento'])->name('Evento.categoria');
		Route::get('{id}/{type}/editcat', [CategoriaController::class, 'editCat']);
		Route::get('createevento', [CategoriaController::class, 'createEvento']);
	});

	Route::group(['prefix' => '/Item/', 'middleware' => ['role:superadministrator']], function () {
		//Route::get('/Administrator/Menus/tipo', [TipoController::class, 'iMenu'])->name('Tipo.imenu');
		//Route::post('/Administrator/Menus/refresh', [ItemController::class, 'refresh'])->name('Item.refresh');
		//Route::get('/Administrator/Menus/createtipo', [TipoController::class, 'createMenu']);
		Route::post('getOptionsMenu', [ItemController::class, 'getOptionsMenu'])->name('Item.getOptionsMenu');
		Route::get('despublicar/{id}', [ItemController::class, 'despublicar']);
		Route::get('publicar/{id}', [ItemController::class, 'publicar']);
		Route::get('{id}/up', [ItemController::class, 'downOrder']);
		Route::get('{id}/down', [ItemController::class, 'upOrder']);
		// Route::get('/Administrator/Item/delete/{id}', [ItemController::class, 'destroy']);
		// Route::post('/Administrator/Item/publicarCheck', [ItemController::class, 'publicarCheck'])->name('Item.publicarcheck');
		// Route::post('/Administrator/Item/despublicarCheck', [ItemController::class, 'despublicarCheck'])->name('Item.despublicarcheck');
		// Route::post('/Administrator/Item/removerCheck', [ItemController::class, 'removerCheck'])->name('Item.removercheck');
	});

	/***Administrator/Midia****/
	Route::group(['prefix' => '/Midia/'], function () {

		Route::get('categoria', [CategoriaController::class, 'catImg'])->name('Midia.categoria');
		Route::get('{id}/{type}/editcat', [CategoriaController::class, 'editCat']);
		Route::get('createImg', [CategoriaController::class, 'createImg'])->name('Midia.create');
		Route::post('load', [ImagemController::class, 'load'])->name('Midia.load');
		Route::get('loadAjax', [ImagemController::class, 'loadAjax'])->name('Midia.loadAjax');
	});

	/***Administrator/Modulo****/
	Route::get('/Modulo/Documento', [AdminController::class, 'getModDoc']);
	Route::get('/Modulo/Galeria', [AdminController::class, 'getModGal']);

	Route::get('/Slide/despublicar/{id}', [SlideController::class, 'despublicar']);
	Route::get('/Slide/publicar/{id}', [SlideController::class, 'publicar']);
	Route::get('/Slide/delete/{id}', [SlideController::class, 'destroy']);
	Route::get('/Slides/export', [SlideController::class, 'export'])->name('Slide.export');
	Route::post('/Slides/removerCheck', [SlideController::class, 'removerCheck'])->name('Slide.removercheck');
	Route::post('/Slides/publicarCheck', [SlideController::class, 'publicarCheck'])->name('Slide.publicarcheck');
	Route::post('/Slides/despublicarCheck', [SlideController::class, 'despublicarCheck'])->name('Slide.despublicarcheck');
	Route::get('/Slide/ordenar/{id}/{order}', [SlideController::class, 'mudarOrdem'])->name('slide.ordenar');

	Route::group(['prefix' => '/Parceiro/'], function () {

		Route::get('despublicar/{id}', [ParceiroController::class, 'despublicar']);
		Route::get('publicar/{id}', [ParceiroController::class, 'publicar']);
		Route::get('delete/{id}', [ParceiroController::class, 'destroy']);
		Route::post('publicarCheck', [ParceiroController::class, 'publicarCheck'])->name('Parceiro.publicarcheck');
		Route::post('despublicarCheck', [ParceiroController::class, 'despublicarCheck'])->name('Parceiro.despublicarcheck');
		Route::post('removerCheck', [ParceiroController::class, 'removerCheck'])->name('Parceiro.removercheck');
	});

	Route::get('/Parceiros/export', [ParceiroController::class, 'export'])->name('Parceiro.export');

	Route::group(['prefix' => '/Faq/'], function () {

		Route::get('despublicar/{id}', [FaqController::class, 'despublicar']);
		Route::get('publicar/{id}', [FaqController::class, 'publicar']);
		Route::get('delete/{id}', [FaqController::class, 'destroy']);
		Route::get('destacar/{id}', [FaqController::class, 'destacar']);
		Route::get('rdestacar/{id}', [FaqController::class, 'rdestacar']);
		Route::post('publicarCheck', [FaqController::class, 'publicarCheck'])->name('Faq.publicarcheck');
		Route::post('despublicarCheck', [FaqController::class, 'despublicarCheck'])->name('Faq.despublicarcheck');
		Route::post('destaqueCheck', [FaqController::class, 'destaqueCheck'])->name('Faq.destaquecheck');
		Route::post('rdestaqueCheck', [FaqController::class, 'rdestaqueCheck'])->name('Faq.rdestaquecheck');
		Route::post('removerCheck', [FaqController::class, 'removerCheck'])->name('Faq.removercheck');
	});

	Route::group(['prefix' => '/Faqs/'], function () {

		Route::get('categoria', [CategoriaController::class, 'catFaq'])->name('Faq.categoria');
		Route::get('{id}/{type}/editcat', [CategoriaController::class, 'editCat']);
		Route::get('createfaq', [CategoriaController::class, 'createFaq']);
		Route::get('export', [FaqController::class, 'export'])->name('Faq.export');
	});

	Route::group(['prefix' => '/Link/'], function () {

		Route::get('despublicar/{id}', [LinkController::class, 'despublicar']);
		Route::get('publicar/{id}', [LinkController::class, 'publicar']);
		Route::get('delete/{id}', [LinkController::class, 'destroy']);
		Route::get('destacar/{id}', [LinkController::class, 'destacar']);
		Route::get('rdestacar/{id}', [LinkController::class, 'rdestacar']);
		Route::post('publicarCheck', [LinkController::class, 'publicarCheck'])->name('Link.publicarcheck');
		Route::post('despublicarCheck', [LinkController::class, 'despublicarCheck'])->name('Link.despublicarcheck');
		Route::post('destaqueCheck', [LinkController::class, 'destaqueCheck'])->name('Link.destaquecheck');
		Route::post('rdestaqueCheck', [LinkController::class, 'rdestaqueCheck'])->name('Link.rdestaquecheck');
		Route::post('removerCheck', [LinkController::class, 'removerCheck'])->name('Link.removercheck');
	});

	Route::group(['prefix' => '/Links/'], function () {

		Route::get('categoria', [CategoriaController::class, 'catLink'])->name('Link.categoria');
		Route::get('{id}/{type}/editcat', [CategoriaController::class, 'editCat']);
		Route::get('createlink', [CategoriaController::class, 'createLink']);
		Route::get('export', [LinkController::class, 'export'])->name('Link.export');
	});

	Route::group(['prefix' => '/Modulo/'], function () {

		Route::get('Video', [ModuloController::class, 'iVideo'])->name('Video.ivideo');
		Route::get('Menu', [ModuloController::class, 'iMenu'])->name('Menu.imenu');
		Route::get('Info', [AdminController::class, 'getModInfo']);
		Route::get('AddMenu', [AdminController::class, 'getModAddMenu']);
	});

	Route::post('/Video/store', [ModuloController::class, 'storeVideo'])->name('Video.storevideo');
	Route::post('/Menu/storemenu', [ModuloController::class, 'storeMenu'])->name('Menu.storemenu');

	Route::group(['prefix' => 'user', 'middleware' => ['role:superadministrator|administrator']], function () {
		Route::get('/Nav/historico/{id}', [UserController::class, 'historico']);
		Route::post('/ativar', [UserController::class, 'ativarCheck'])->name('User.ativarcheck');
		Route::post('/desactivar', [UserController::class, 'desativarCheck'])->name('User.desativarcheck');
		Route::get('/estado/{id}/{estado}', [UserController::class, 'updateEstado']);
		Route::post('removerCheck', [UserController::class, 'removerCheck'])->name('User.removercheck');
		Route::get('/resetpassworduser/{id}', [UserController::class, 'resetPasswordUser'])->name('user.resetpassword');
		Route::get('/password/{id}', [UserController::class, 'showPasswordForm'])->name('User.password.form');
		Route::post('/password/{id}', [UserController::class, 'updatePassword'])->name('User.password.update');
	});
});

/**********FrontEnd**********/
Route::get('/artigo/teste', [PagesController::class, 'getArtigoTeste']);
Route::get('/setLanguage/{id}', [PagesController::class, 'setLanguage']);
Route::get('/', [PagesController::class, 'getIndex'])->name('Pages.index');
Route::get('/home', [PagesController::class, 'getIndex']);
Route::get('/nav/{idTag1}/{idTag2?}/{idTag3?}', [PagesController::class, 'getNav']);

Route::get('/documento/{id}', [PagesController::class, 'getDocumento']);
Route::get('/documento/opendoc/{id}', [PagesController::class, 'openDocumento']);
Route::get('/navdoc/{idPasta}', [PagesController::class, 'navDocumento']);
Route::get('/dash', [PagesController::class, 'getDash']);
Route::get('/artigos/{slug}', [PagesController::class, 'getArtigoSlug'])->name('slug');

Route::get('/artigo/{id}', [PagesController::class, 'getArtigo'])->name('artigo');
Route::get('/navart/{idPasta}', [PagesController::class, 'navArtigo']);
Route::get('/navsite/{slug}/{type}', [PagesController::class, 'navCatSlug'])->name('slugnavcat');


Route::get('/artigomenu/{id}', [PagesController::class, 'getArtigoMenu']);
Route::get('/artigosmenu/{slug}', [PagesController::class, 'getArtigoMenuSlug'])->name('slugartigo');
Route::get('/documentos/{slug}', [PagesController::class, 'getDocSlug'])->name('slugdoc');


Route::get('/faq/{id}', [PagesController::class, 'getFaq']);
Route::get('/navfaq/{idPasta}', [PagesController::class, 'navFaq']);
Route::get('/faqs/{slug}', [PagesController::class, 'getFaqSlug'])->name('slugfaq');

Route::get('/link/{id}', [PagesController::class, 'clickLink'])->name('link.click');

Route::get('Contact', [PagesController::class, 'getContact'])->name('Pages.contacto');

Route::post('Contact', [PagesController::class, 'postContact'])->name('Pages.postContact');
Route::get('/organigrama', [PagesController::class, 'getOrg']);
Route::get('About', [PagesController::class, 'getAbout']);

Route::get('/navtag/{idTag}', [PagesController::class, 'getTagNav']);
Route::get('/search', [PagesController::class, 'getSearch'])->name('Pages.getSearch');
// Route::get('/sresult', [PagesController::class, 'navSearch'])->name('Pages.search');
Route::get('files/{path}/{file}', ['as' => 'files', 'uses' => PagesController::class.'@files']);
Route::post('/newslletter', [PagesController::class, 'save_email_newsletter']);
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