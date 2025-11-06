<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use App\Helpers\Helpers;
use App\Model\Item;
use App\Model\Language;
use Session;
class CMSViewComposer{

    /**
     * Create a movie composer.
     *
     * @return void
     */
    

    public function __construct()
    {
        
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {

        

        $viewData = [
            'phone' => Helpers::getconfig('phone'),
            'address' => Helpers::getconfig('address'),
            'address2' => Helpers::getconfig('address2'),
            'email' => Helpers::getconfig('email'),
            'emailnot' => Helpers::getconfig('email_not'),
            'slides' => Helpers::getSlides(),
            'topo' => Item::tree(6)['childreen'],
            'left' => Helpers::getMenu('menu_left')['childreen'],
            'rodape' => Helpers::getMenu('menu_rodape')['childreen'],
            'principal' => Helpers::getMenu('menu_principal')['childreen'],
            'parceiros' => Helpers::getParceiros(),
            'linksf' => Helpers::getLinks(),
            'language' => Language::all(),
            'lang' =>  Session::has('lan') ? session()->get('lan') : session()->put('lan', '1'),
            'catnoticia' => Helpers::getconfig('catnoticia'),
            'phone_notification' => Helpers::getconfig('phone_notification'),
        ];

        

        $view->with($viewData);
    }
}
