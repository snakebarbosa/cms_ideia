<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\HomeService;
use App\Services\ConfigurationService;
use App\Services\NavigationService;
use App\Services\ArtigoPublicService;
use App\Services\DocumentoPublicService;
use App\Services\FaqService;
use App\Services\SearchService;
use App\Services\LinkService;

use App\Model\Artigo;
use App\Model\Categoria;
use App\Model\Documento;
use App\Model\Faq;
use App\Model\Item;
use App\Model\Language;
use App\Model\Configuration;
use App\Model\Link;
use App\Model\Parceiro;
use App\Model\Tag;
use App\Model\Slide;
use Breadcrumbs;
use Carbon\Carbon;
use File;
use Illuminate\Http\Request;
use Mail;
use Session;
use Newsletter;
use URL;

class PagesController extends Controller {

	protected $homeService;
	protected $configService;
	protected $navigationService;
	protected $artigoService;
	protected $documentoService;
	protected $faqService;
	protected $searchService;
	protected $linkService;

	public function __construct(
		HomeService $homeService,
		ConfigurationService $configService,
		NavigationService $navigationService,
		ArtigoPublicService $artigoService,
		DocumentoPublicService $documentoService,
		FaqService $faqService,
		SearchService $searchService,
		LinkService $linkService
	) {
		$this->homeService = $homeService;
		$this->configService = $configService;
		$this->navigationService = $navigationService;
		$this->artigoService = $artigoService;
		$this->documentoService = $documentoService;
		$this->faqService = $faqService;
		$this->searchService = $searchService;
		$this->linkService = $linkService;
	}

	/**
	 * Display home page
	 */
	public function getIndex() {
		Session::has('lan') ? session()->get('lan') : session()->put('lan', '0');

		$data = $this->homeService->getHomePageData();

		return view('Pages.home', $data);
	}

	/**
	 * Get configuration value
	 */
	public function getconfig($type) {
		return $this->configService->getConfig($type);
	}

	/**
	 * Set language preference
	 */
	public function setLanguage($id) {
		Session::put('lan', $id - 1);
		return redirect()->back();
	}
	/**
	 * Generate breadcrumbs navigation
	 */
	public function breadcrumbs($type, $id = 0, $obj = 0){
		Session::has('lan') ? session()->get('lan') : session()->put('lan', '1');

		$breadcrumbs = Breadcrumbs::addCrumb('Home', URL::to('/'))->setDivider(' ')->setCssClasses('breadcrumb');

		if ($type != 'tag' && $type != 'search') {
			$route = $this->getRouteByType($type);
			$parent = Categoria::find($id);

			if ($parent) {
				$p2 = $parent->parent;

				if ($p2 != 0) {
					$p2 = Categoria::find($p2);
					if ($p2 && count($p2->conteudos) > 0) {
						$breadcrumbs = Breadcrumbs::addCrumb(
							$p2->conteudos[Session::get('lan')]->titulo,
							URL::to('/') . $route . $p2->id
						)->setDivider(' ');
					}
				}

				if (count($parent->conteudos) > 0) {
					$breadcrumbs = Breadcrumbs::addCrumb(
						$parent->conteudos[Session::get('lan')]->titulo,
						URL::to('/') . $route . $id
					);
				}
			}
		} else if ($type == 'tag') {
			$tag1 = Tag::find($id);
			if ($tag1) {
				$breadcrumbs = Breadcrumbs::addCrumb($tag1->name, URL::to('/'));
			}
		} else if ($type == 'search') {
			$breadcrumbs = Breadcrumbs::addCrumb('Pesquisar', URL::to('/') . '/search/' . $obj->s);
		}

		return $breadcrumbs->render();
	}

	/**
	 * Get route by content type
	 */
	private function getRouteByType($type)
	{
		$routes = [
			'faq' => '/navfaq/',
			'doc' => '/navdoc/',
			'art' => '/navart/',
		];

		return $routes[$type] ?? '/';
	}

	/**
	 * 
	 */
	public function getDefaults(){
		if (!Session::has('lan')) {
			Session::put('lan', '0');
		}
	
		$phone = self::getconfig('phone');
		$address = self::getconfig('address');
		$email = self::getconfig('email');
        $email_not = self::getconfig('email_not');

		$slides        = self::getSlides();
		$menuTopo      = self::getMenuTopo();
		$menuLeft      = self::getMenuLeft();
		
		$menuRodape    = self::getMenuRodape();
		$menuPrincipal = self::getMenuPrincipal();
		
		$parceiros = self::getParceiros();

		$links_footer = self::getLinks();
            
		$lang = Language::all();


		return array("slides" => $slides, "menuTopo" => $menuTopo, "menuLeft" => $menuLeft, "menuRodape" => $menuRodape, "menuPrincipal" => $menuPrincipal, "parceiros" => $parceiros, "links_footer" => $links_footer, "lang" => $lang, "phone" => $phone, "address" => $address, "email" => $email, "email_not" => $email_not);
	}
	
	/*****************
	Get Artigo

	 ******************/
	public function getLinks() {

		$link = Link::where('ativado',1)->get();
       
		return $link;


	}
	/**
	 * 
	 */
	public function getNav($idTag, $idTag1 = 0, $idTag2 = 0) {

		$tag1 = Tag::find($idTag);

		$breadcrumbs2 =  self::breadcrumbs('tag',$idTag, 0);

		$tagrel   = array();
		$tagrel[] = $tag1;

		$outros     = $this->ContentUrl($this->getDocTag($idTag));
		$tagLastDoc = $this->ContentUrl($this->getDocRecenteTag($idTag));
		$tagFolder  = $this->getFolderTag($idTag);
		$links      = $this->getLinksTag($idTag);
		$faqs       = $this->getFaqsTag($idTag);
		$items      = $this->getItemTag($idTag, $idTag1);

		$docs   = $this->getDocDestaque();
		$docl   = $this->getDocLast();
		$random = $this->getDocRandom();

		$news      = $this->getNewsTag($idTag);
		

		
		return view('Pages.nav')->withTagrel($tagrel)->withOutros($outros)->withRecentestag($tagLastDoc)->withPastas($tagFolder)->withCrumbs2($breadcrumbs2)->withLinks($links)->withFaqs($faqs)->withNews($news)->withDrandom($random)->withDlast($docl)->withDocs($docs)->withItems($items);
	}

	/*****************
	getTagNav
	- return all content(artigo, doc and faqs) with tag
	 ******************/

	public function getTagNav($idTag) {

		$tag = Tag::find($idTag);

		$breadcrumbs2 =  self::breadcrumbs('tag',$idTag, 0);

		$artigos    = $this->getFolderTagNav($idTag, 'artigo');
		$documentos = $this->getFolderTagNav($idTag, 'documento');
		$faqs       = $this->getFolderTagNav($idTag, 'faq');

		

		$docs      = $this->getDocDestaque();
		$docl      = $this->getDocLast();
		$parceiros = $this->getParceiros();

		$artrel = Artigo::take(6)->get();
			// Artigo::whereHas('tags', function ($query) use ($tag) {

			// 	$query->whereIn('idTag', $tag);
			// })->where('ativado', 1)->limit(7)->orderBy('created_at', 'desc')->get();

		$docrel = Documento::take(6)->orderBy('created_at')->get();
			// Documento::whereHas('tags', function ($query) use ($tag) {

			// 	$query->whereIn('idTag', $tag);
			// })->where('ativado', 1)->limit(7)->orderBy('created_at', 'desc')->get();

		$docrel = $this->ContentUrl($docrel);

		return view('Pages.navtag')->withCrumbs($breadcrumbs2)->withFaqs($faqs)->withArtigos($artigos)->withDocumentos($documentos)->withDlast($docl)->withDocs($docs)->withDocrel($docrel)->withArtrel($artrel);

	}

	/**
	 * Return a collection of documento with URL according to language
	 */
	public function ContentUrl($content){
		if (isset($content) && count($content)) {
			$lang = Session::get('lan') == 'pt' ? 'pt' : 'en';

			foreach ($content as $key => $doc) {
				$files = $doc->url;

				if ($this->is_json($files)) {
					$files = json_decode($doc->url);
					$doc['url'] = $files->{$lang} ?? $files->pt;
				}
			}
		}

		return $content;
	}

	/**
	 * Search across all content types
	 */
	public function getSearch(Request $request) {
		$this->validate($request, [
			's' => 'required|min:2|max:255',
		]);

		$breadcrumbs2 = $this->breadcrumbs('search', 0, $request);
		
		$results = $this->searchService->search($request->s);
		$documentos = $this->ContentUrl($results['documentos']);
		
		$docs = $this->documentoService->getFeaturedDocuments();
		$docl = $this->documentoService->getLatestDocuments();

		return view('Pages.search', [
			'crumbs' => $breadcrumbs2,
			's' => $request->s,
			'documentos' => $documentos,
			'artigos' => $results['artigos'] ?? collect(),
			'faqs' => $results['faqs'] ?? collect(),
			'dlast' => $docl,
			'docs' => $docs
		]);
	}
	/*****************
	Get Slides
	- View de defnicoes web
	 ******************/

	public function getOrg() {

		$breadcrumbs = Breadcrumbs::addCrumb('Home', URL::to('/'))->setDivider(' ')->setCssClasses('breadcrumb');

		$breadcrumbs  = Breadcrumbs::addCrumb('Organização e Estrutura', '');
		$breadcrumbs2 = $breadcrumbs->render();
		
		$docs          = $this->getDocDestaque();
		$docl          = $this->getDocLast();


		
		
		return view('Pages.org')->withCrumbs($breadcrumbs2)->withDlast($docl)->withDocs($docs);

	}
	/**
	 * Get slug from ID
	 */
	public function getSlugWithId($id, $obj){
		$item = $obj::find($id);
		
		if (!$item || !$item->slug) {
			return null;
		}

		$slug_json = json_decode($item->slug);
		$lang = Session::get('lan', 0);
		
		return $lang == 0 ? ($slug_json->pt ?? null) : ($slug_json->en ?? null);
	}

	/**
	 * Get ID from slug
	 */
	public function getIdWithSlug($slug, $obj){
		$allslugs = $obj::select(['slug', 'id'])
			->where('slug', 'like', '%' . $slug . '%')
			->get();

		foreach ($allslugs as $value) {
			$slug_json = json_decode($value->slug);
			
			if (($slug_json->pt ?? null) == $slug || ($slug_json->en ?? null) == $slug) {
				return $value->id;
			}
		}

		return null;
	}

	/**
	 * Check if string is valid JSON
	 */
	public function is_json($string, $return_data = false) {
		$data = json_decode($string);
		return (json_last_error() == JSON_ERROR_NONE) ? ($return_data ? $data : true) : false;
	}

	/**
	 * Get document by slug
	 */
	public function getDocSlug($slug) {
		$doc = $this->documentoService->getDocumentBySlug($slug);

		if (!$doc) {
			abort(404, 'Document not found');
		}

		$docrel = $this->documentoService->getDocumentsByCategory($doc->idCategoria);
		$docrel = $docrel->where('id', '<>', $doc->id)->take(9);
		$docrel = $this->ContentUrl($docrel);
		
		$breadcrumbs2 = $this->breadcrumbs('doc', $doc->idCategoria, 0);

		// Prepare document URL and size
		$lang = Session::get('lan') == 0 ? 'pt' : 'en';
		$files = json_decode($doc->url);
		
		if ($this->is_json($doc->url)) {
			$doc['url'] = $files->{$lang} ?? $files->pt;
			$doc['size'] = \App\Helpers\Helpers::getDocumentFileSize($doc->url, $lang);
		} else {
			$doc['size'] = \App\Helpers\Helpers::getDocumentFileSize($doc->url);
		}

		$this->documentoService->incrementDownloads($doc->id);

		$docs = $this->documentoService->getFeaturedDocuments();
		$docl = $this->documentoService->getLatestDocuments();

		return view('Pages.documento', [
			'crumbs' => $breadcrumbs2,
			'docrel' => $docrel,
			'doc' => $doc,
			'dlast' => $docl,
			'docs' => $docs
		]);
	}

	/**
	 * Get document by ID and redirect to slug route
	 */
	public function getDocumento($id) {
		$doc = Documento::find($id);

		if (!$doc) {
			abort(404, 'Document not found');
		}

		$slug = json_decode($doc->slug);
		$sl = Session::get('lan') == 0 ? $slug->pt : $slug->en;

		return redirect()->route('slugdoc', $sl);
	}

	/**
	 * Open image file
	 */
	public function openImage($url) {
		return response()->file(public_path('images/' . $url));
	}

	/**
	 * Open document file
	 */
	public function openDocumento($url) {
		return $this->documentoService->openDocument($url);
	}
	/**
	 * Navigate documents by category ID
	 */
	public function navDocumento($idPasta) {
		$cat = Categoria::find($idPasta);
		
		if (!$cat) {
			abort(404, 'Category not found');
		}

		$slug = json_decode($cat->slug);
		$sl = Session::get('lan') == 'pt' ? $slug->pt : $slug->en;

		return redirect()->route('slugnavcat', [$sl, 'doc']);

		
		
	}
	/*****************
	Transforma o tamanho do documento para um formato legivel
	 ******************/
	public static function bytesToHuman($bytes) {
		$units = ['B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB'];

		for ($i = 0; $bytes > 1024; $i++) {
			$bytes /= 1024;
		}

		return round($bytes, 2).' '.$units[$i];
	}

	/**
	 * Navigate FAQs by category ID
	 */
	public function navFaq($idPasta) {
		$cat = Categoria::find($idPasta);
		
		if (!$cat) {
			abort(404, 'Category not found');
		}

		$slug = json_decode($cat->slug);
		$sl = Session::get('lan') == 'pt' ? $slug->pt : $slug->en;

		return redirect()->route('slugnavcat', [$sl, 'faq']);
	}

	/**
	 * Get FAQ by slug
	 */
	public function getFaqSlug($slug) {
		$faq = $this->faqService->getFaqBySlug($slug);
		
		if (!$faq) {
			abort(404, 'FAQ not found');
		}

		// Track FAQ view
		$this->faqService->incrementViews($faq->id);

		$faqrel = $this->faqService->getFaqsByCategory($faq->idCategoria)
			->where('id', '<>', $faq->id)
			->take(9);
		
		$breadcrumbs2 = $this->breadcrumbs('faq', $faq->idCategoria, 0);
		
		$docs = $this->documentoService->getFeaturedDocuments();
		$docl = $this->documentoService->getLatestDocuments();

		return view('Pages.faq', [
			'crumbs' => $breadcrumbs2,
			'faqrel' => $faqrel,
			'faq' => $faq,
			'dlast' => $docl,
			'docs' => $docs
		]);
	}

	/**
	 * Get FAQ by ID and redirect to slug
	 */
	public function getFaq($id) {
		$faq = $this->faqService->getFaq($id);

		if (!$faq) {
			abort(404, 'FAQ not found');
		}

		return redirect()->route('slugfaq', $faq->slug);
	}
	/**
	 * 
	 */
	public function getNavIdSlug($slug){
	
		$all = Categoria::select(['slug','id'])->where('slug', 'like', '%'.$slug.'%')->get();
			//where('ativado',1)->get();
		

		foreach ($all as $key => $value) {
			
			$slug_json = json_decode($value->slug);
			
			if ($slug_json->pt == $slug || $slug_json->en == $slug){
					
					return $value->id;
	        }
		
		}
		
		
	}
	/**
	 * 
	 */
	/**
	 * Navigate category by slug
	 */
	public function navCatSlug($sl, $type) {
		$viewMap = [
			'art' => 'Pages.navartigo',
			'faq' => 'Pages.navfaq',
			'doc' => 'Pages.navdoc'
		];

		$modelMap = [
			'art' => new Artigo,
			'faq' => new Faq,
			'doc' => new Documento
		];

		$typeMap = [
			'art' => 'artigo',
			'faq' => 'faq',
			'doc' => 'documento'
		];

		$view = $viewMap[$type] ?? abort(404);
		$obj = $modelMap[$type] ?? abort(404);
		$fullType = $typeMap[$type] ?? $type;

		$category = $this->navigationService->getCategoryBySlug($sl, $fullType);
		
		if (!$category) {
			abort(404, 'Category not found');
		}

		$idPasta = $category->id;
		$breadcrumbs2 = $this->breadcrumbs($type, $idPasta, 0);
		
		$pastas = Categoria::where('parent', $idPasta)
			->where('ativado', 1)
			->orderBy('titulo', 'ASC')
			->get();
		
		$content = $obj::where('idCategoria', $idPasta)
			->where('ativado', 1)
			->orderBy('created_at', 'desc')
			->paginate(15);

		if (($obj instanceof Documento) && isset($content) && count($content)) {
			$content = $this->ContentUrl($content);
		}

		return view($view, [
			'crumbs' => $breadcrumbs2,
			'pastas' => $pastas,
			'artigos' => $content,
			'faqs' => $content,
			'documentos' => $content
		]);
	}

	/**
	 * Navigate articles by category ID
	 */
	public function navArtigo($idPasta) {
		$cat = Categoria::find($idPasta);
		
		if (!$cat) {
			abort(404, 'Category not found');
		}

		$slug = json_decode($cat->slug);
		$sl = Session::get('lan') == 'pt' ? $slug->pt : $slug->en;

		return redirect()->route('slugnavcat', [$sl, 'art']);
	}

	/**
	 * Get article from menu by ID
	 */
	public function getArtigoMenu($id) {
		$art = Artigo::find($id);

		if (!$art) {
			abort(404, 'Article not found');
		}

		$slug = json_decode($art->slug);
		$sl = Session::get('lan') == 0 ? $slug->pt : $slug->en;
		
		return redirect()->route('slugartigo', $sl);
	}

	/**
	 * Get article from menu by slug
	 */
	public function getArtigoMenuSlug($slug) {
		$art = Artigo::where('slug', $slug)->first();

		if (!$art) {
			abort(404, 'Article not found');
		}

		$breadcrumbs2 = $this->breadcrumbs('art', $art->idCategoria, 0);

		$tags = $art->tags->pluck('id')->toArray();

		$itemrel = Item::whereHas('tags', function ($query) use ($tags) {
			$query->whereIn('idTag', $tags);
		})->where('ativado', 1)->limit(10)->orderBy('created_at', 'desc')->get();

		$artrel = Artigo::whereHas('tags', function ($query) use ($tags) {
			$query->whereIn('idTag', $tags);
		})->where('ativado', 1)->limit(10)->orderBy('created_at', 'desc')->get();

		$docrel = Documento::whereHas('tags', function ($query) use ($tags) {
			$query->whereIn('idTag', $tags);
		})->where('ativado', 1)->limit(10)->orderBy('created_at', 'desc')->get();

		$docs = $this->documentoService->getFeaturedDocuments();
		$docl = $this->documentoService->getLatestDocuments();

		return view('Pages.artigomenu', [
			'crumbs' => $breadcrumbs2,
			'artrel' => $artrel,
			'art' => $art,
			'docrel' => $docrel,
			'docs' => $docs,
			'items' => $itemrel
		]);
	}

	/**
	 * Get article by ID and redirect to slug
	 */
	public function getArtigo($id) {
		$art = $this->artigoService->getArticle($id);

		if (!$art) {
			abort(404, 'Article not found');
		}

		$slug = json_decode($art->slug);
		$sl = Session::get('lan') == 0 ? $slug->pt : $slug->en;

		return redirect()->route('slug', $sl);
	}

	/**
	 * Get article by slug
	 */
	public function getArtigoSlug($slug) {
		$art = $this->artigoService->getArticleBySlug($slug);

		if (!$art) {
			abort(404, 'Article not found');
		}

		// Increment view counter
		$this->artigoService->incrementViews($art->id);

		$breadcrumbs2 = $this->breadcrumbs('art', $art->idCategoria, 0);

		$artrel = [];
		$docrel = [];

		$docs = $this->documentoService->getFeaturedDocuments();
		$docl = $this->documentoService->getLatestDocuments();

		$artDocs = Artigo::where('id', $art->id)->with('anexos')->get();
		
		$lan = Session::get('lan');
		$slug = $lan == 0 ? json_decode($art->slug)->pt : json_decode($art->slug)->en;

		return view('Pages.artigo2', [
			'artDocs' => $artDocs,
			'slug' => $slug,
			'crumbs' => $breadcrumbs2,
			'artrel' => $artrel,
			'art' => $art,
			'docrel' => $docrel,
			'docs' => $docs
		]);
	}
	/**
	 * Display contact page
	 */
	public function getContact() {
		$breadcrumbs = Breadcrumbs::addCrumb('Home', URL::to('/'))
			->setDivider(' ')
			->setCssClasses('breadcrumb');

		$breadcrumbs = Breadcrumbs::addCrumb('Contacte-nos', URL::to('/') . '/contact');
		$breadcrumbs2 = $breadcrumbs->render();

		$docs = $this->documentoService->getFeaturedDocuments();
		$docl = $this->documentoService->getLatestDocuments();

		return view('Pages.contact', [
			'crumbs' => $breadcrumbs2,
			'dlast' => $docl,
			'docs' => $docs
		]);
	}

	/**
	 * Handle contact form submission
	 */
	public function postContact(Request $request) {
		$this->validate($request, [
			'name'    => 'required|max:150',
			'message' => 'required|min:10',
			'subject' => 'required|min:3',
		]);

		$data = [
			'email'   => $request->email,
			'name'    => $request->name,
			'msg'     => $request->message,
			'subject' => $request->subject,
		];

		Mail::send('Pages.email', [
			'subject' => $data['subject'],
			'msg' => $data['msg'],
			'email' => $data['email']
		], function ($message) use ($data) {
			$message->from('noreply@ipiaam.cv');
			$message->replyTo('infor@ipiaam.gov.cv');
			$message->to('infor@ipiaam.gov.cv', 'IPIAAM');
			$message->subject($data['subject']);
		});

		Session::flash('success', 'O seu email foi enviado com sucesso!');

		return redirect()->route('Pages.contacto');
	}

	/**
	 * Get slides for home page
	 */
	public function getSlides() {
		return $this->homeService->getSlides();
	}

	/**
	 * Get video configuration
	 */
	public function getVideo() {
		$path = storage_path() . "/json/video.json";
		return json_decode(file_get_contents($path), true);
	}

	/**
	 * Get left menu
	 */
	public function getMenuLeft() {
		$mp = $this->getconfig('menu_left');
		return Item::tree($mp);
	}

	/**
	 * Get top menu
	 */
	public function getMenuTopo() {
		return Item::tree(6);
	}

	/**
	 * Get footer menu
	 */
	public function getMenuRodape() {
		$mp = $this->getconfig('menu_rodape');
		return Item::tree($mp);
	}

	/**
	 * Get main menu
	 */
	public function getMenuPrincipal() {
		$mp = $this->getconfig('menu_principal');
		return Item::tree($mp);
	}
	/**
	 * Get documents by type
	 */
	public function getDocContent($type) {
		switch ($type) {
			case 'destaque':
				return $this->documentoService->getFeaturedDocuments();
			
			case 'last':
				return $this->documentoService->getLatestDocuments(5);
			
			case 'random':
				return $this->documentoService->getRandomDocuments();
			
			default:
				return collect();
		}
	}

	/**
	 * Get featured documents
	 */
	public function getDocDestaque() {
		return $this->getDocContent('destaque');
	}

	/**
	 * Get latest documents
	 */
	public function getDocLast() {
		return $this->getDocContent('last');
	}

	/**
	 * Get random documents
	 */
	public function getDocRandom() {
		return $this->getDocContent('random');
	}

	/**
	 * Get featured news articles
	 */
	public function getNews() {
		return $this->artigoService->getRecentNews(7);
	}

	/**
	 * Get all news articles
	 */
	public function getAllNews() {
		return $this->artigoService->getRecentNews(5);
	}

	/**
	 * Get featured FAQs
	 */
	public function getFaqs() {
		return $this->faqService->getFeaturedFaqs();
	}

	/**
	 * Get partners
	 */
	public function getParceiros() {
		return $this->homeService->getPartners();
	}

	/**
	 * Get back office info from JSON
	 */
	public function getBOinfo() {
		$path = storage_path() . "/json/backoffice_info.json";
		return json_decode(file_get_contents($path), true);
	}

	/*****************
	Get Folders by TagNAv
	 ******************/

	public function getFolderTagNav($id, $type) {	

		return self::getNavTag($id, new Categoria,$type);


	}
	/*****************
		Nav with tags
	 ******************/
	public function getNavTag($id, $obj,$type) {
		
		$tags = [$id];
		

		if($type != '0'){
			
			$content = $obj::take(7)->get();
			//DD($content);
			// $content =  $obj::whereHas('tags', function ($query) use ($tags) {
			// 	// $query->where('idTag', $tags);
			// 	$query->whereIn('idTag', $tags);
			// })->where('ativado', 1)->where($type, 1)->limit(6)->orderBy('created_at', 'desc')->get();

		}else{
				
		    $content = $obj::take(7)->get();
			// $content = $obj::whereHas('tags', function ($query) use ($tags) {
			// 	$query->whereIn('idTag', $tags);
			// })->limit(7)->where('ativado', 1)->orderBy('created_at', 'desc')->get();
		}
		

		//, '=', count($tags)
	
		//getDocRecenteTag
		// $docs = Documento::whereHas('tags', function ($query) use ($tags) {
		// 		$query->whereIn('idTag', $tags);
		// 	}, '=', count($tags))->limit(4)->where('ativado', 1)->orderBy('created_at', 'desc')->get();


			

		// $list = array();

		// if(isset($content) && $content->count() != 0) {
		// 	foreach ($content as $v) {

		// 		$item = $obj::find($v->id);

		// 		$tags = $item->tags;

		// 		if ($item['ativado'] == 1) {
		// 			$list[] = $item;
		// 		}
		// 	}
		// }

		//$list2 = json_encode($list);

		return $content;
	
	
	}

	/*****************
		Nav NEWs
	 ******************/

	public function getInfoTag($id) {
		return self::getNavTag($id, New Artigo,0);

		
	}

	/*****************
	Nav NEWs
	 ******************/

	public function getNewsTag($id) {

		return self::getNavTag($id, New Artigo,0);

		
	}
	/*****************
	getDocTag
	- This function select all documentes according to given tags
	- $idBase can be (formulario, circular e legislacao)
	 ******************/

	public function getDocTag($id) {

		$tags   = [$id];
		$idBase = 0;
		$i      = 0;

		while ($idBase == 0) {

			$cat = Categoria::whereHas('tags', function ($query) use ($tags) {
					$query->whereIn('idTag', $tags);
				}, '=', count($tags))->where('documento', 1)->where('ativado', 1)->inRandomOrder()->first();

			if (!is_null($cat)) {
				$doc_c = Documento::where('idCategoria', $cat->id)->where('ativado', 1)->get();

				if ($doc_c->count() != 0) {
					$idBase = $cat->$id;
					break;

				} elseif ($i < 2) {
					$i++;

				} else {

					break;
				}
			} else {

				return [];
				break;
			}
		}

		//DD($idBase->id);
		//$tags = [$idBase->id,$id];

		$docs = Documento::whereHas('tags', function ($query) use ($tags) {
				$query->whereIn('idTag', $tags);
			}, '=', count($tags))->where('idCategoria', $cat->id)->where('ativado', 1)->limit(6)->orderBy('created_at', 'desc')->get();

		
		$list = array();

		if (isset($docs) && $docs->count() != 0) {
			foreach ($docs as $doc) {

				$item = Documento::find($doc->id);

				$conteudos = $item->conteudos;
				$tags      = $item->tags;

				$list[] = $item;
			}
		}

		$list2 = json_encode($list);


		return $list;
	}
	/*****************
	getDocRecenteTag
	- This function select latest documents(random) according to given tags,

	 ******************/

	public function getDocRecenteTag($id) {

		return self::getNavTag($id, New Documento,0);

	}
	/*****************
	Nav
	 ******************/

	public function getFaqsTag($id) {
		
		return self::getNavTag($id, New Faq, 0);

		
	}

	/*****************
	Nav
	 ******************/

	public function getLinksTag($id, $type = 0) {
		
		

		return self::getNavTag($id, New Link, 0);


	}
	/*****************
	Nav
	 ******************/

	public function getFolderTag($id) {
		
		return self::getNavTag($id, New Categoria, 0);

	}

	

	/*****************
	Get menu items by Tag NAV
	 ******************/
	public function getItemTag($id, $idTag1 = 0) {

		$tags1 = Tag::find($id);

		$tags = [$id];

		if (count($tags1->tags) != 0) {
			foreach ($tags1->tags as $t) {
				$tags[] = $t->id;
			}
		}
		//$tags = [$id, 2];
		//DD($tags);

		$items = Item::whereHas('tags', function ($query) use ($tags) {

				$query->whereIn('idTag', $tags);
			})->where('ativado', 1)->limit(6)->orderBy('created_at', 'desc')->get();

		$list = array();

		if (isset($items) && $items->count() != 0) {
			foreach ($items as $i) {

				$item = Item::find($i->id);

				$tags = $item->tags;

				$list[] = $item;
			}
		}

		$list2 = json_encode($list);

		return $list;
	}

	/**
	 * 
	 */
	
	public function getDash(){
		return view('Pages.dash');
	}
	/**
	***
	****
	****
	*****/
	public function getArtigoTeste() {
	
		$id = 118;

		$art = Artigo::find($id);

		$parent = Categoria::find($art['idCategoria']);
		$p2     = $parent['parent'];

		$breadcrumbs = Breadcrumbs::addCrumb('Home', URL::to('/'))->setDivider(' ')->setCssClasses('breadcrumb');

		if ($p2 != 0) {
			$p2          = Categoria::find($p2);
			$breadcrumbs = Breadcrumbs::addCrumb($p2->titulo, URL::to('/').'/navart/'.$p2->id)->setDivider(' ');
		}

		$breadcrumbs  = Breadcrumbs::addCrumb($art['categorias']['titulo'], URL::to('/').'/navart/'.$art['categorias']['id']);
		$breadcrumbs2 = $breadcrumbs->render();

		$tags = $art->tags->toArray();

		$taglist = array();

		for ($i = 0; $i < sizeof($tags); $i++) {
			$taglist[] = $tags[$i]['pivot']['idTag'];

		}

		
		$docrel = Documento::whereHas('tags', function ($query) use ($taglist) {

				$query->whereIn('idTag', $taglist);
			})->where('ativado', 1)->get();

		$artrel = Artigo::whereHas('tags', function ($query) use ($taglist) {

				$query->whereIn('idTag', $taglist);
			})->where('ativado', 1)->limit(7)->orderBy('created_at', 'desc')->get();

		$conteudo   = $art->conteudos;
		$categorias = $art->categorias;


		$slides     = $this->getSlides();
		$menuTopo   = $this->getMenuTopo();
		$menuLeft   = $this->getMenuLeft();
		$menuRodape = $this->getMenuRodape();

		$menuPrincipal = $this->getMenuPrincipal();
		$docs          = $this->getDocDestaque();
		$docl          = $this->getDocLast();
		$parceiros     = $this->getParceiros();

		$links_footer = $this->getLinksTag(36, 1);
		$lang         = Language::all();

		return view('home')->withEmail($default['email'])->withAddress($default['address'])->withPhone($default['phone'])->withCrumbs($breadcrumbs2)->withArtrel($artrel)->withArt($art)->withRodape($menuRodape['childreen'])->withParceiros($parceiros)->withDocrel($docrel)->withDocs($docs)->withTopo($menuTopo['childreen'])->withPrincipal($menuPrincipal['childreen'])->withLeft($menuLeft['childreen'])->withLinksf($links_footer)->withLanguage($lang);
	}
	/*****************/



	public function save_email_newsletter(Request $request)
	{
		
		if (!Newsletter::isSubscribed($request->email)) {
			Newsletter::subscribe($request->email);
			return response()->json(['msg' => 'Success']);
		}else{
			return response()->json(['msg' => 'Exist']);
		}

		return response()->json(['msg' => 'Error']);
	}

	public function files($path, $file)
	{

		$vpath = storage_path($path . '/') . $file;
		return response()->file($vpath, ['Content-type' => File::mimeType($vpath)]);
	}

	/**
	 * Track link click and redirect to external URL
	 */
	public function clickLink($id)
	{
		$link = Link::find($id);
		
		if (!$link) {
			abort(404, 'Link not found');
		}

		// Track link click
		$this->linkService->incrementClicks($id);

		// Redirect to external URL
		return redirect($link->url);
	}

}