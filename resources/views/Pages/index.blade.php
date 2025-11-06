<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="UTF-8"/>
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport"/>
        <meta http-equiv="Access-Control-Allow-Origin" content="*" />
         <meta http-equiv="Access-Control-Allow-Headers" content="X-Requested-With" />
        <title>{{$title or 'Instituto de Preven&ccedil;&atilde;o e Investiga&ccedil;&atilde;o de Acidentes Aereos e Maritimos'}}</title>
        <!-- Favicon-->
        <link rel="icon" href="{{ URL::to('/') }}/favicon.ico" type="image/x-icon"/>

        <!-- Google Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css"/>
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css"/>
        
        <!-- Bootstrap Core Css -->
        <link href="{{ URL::to('/') }}/plugins/bootstrap/css/bootstrap.css" rel="stylesheet"/>

        <!-- Waves Effect Css -->
        <link href="{{ URL::to('/') }}/plugins/node-waves/waves.css" rel="stylesheet"/>

        <!-- Animation Css -->
        <link href="{{ URL::to('/') }}/plugins/animate-css/animate.css" rel="stylesheet"/>

        <!-- Custom Css -->
        <link href="{{ URL::to('/') }}/css/material/style.min.css" rel="stylesheet"/>
        <link href="{{ URL::to('/') }}/css/font-awesome.min.css" rel="stylesheet"/>

        @stack('stylesheet')

        <link href="{{ URL::to('/') }}/css/style.site.css" rel="stylesheet"/>
        <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
        <link href="{{ URL::to('/') }}/css/material/themes/theme-white.css" rel="stylesheet"/>
    </head>

    <body class="theme-white cmp {{isset($nav) && $nav ? 'cpm-nav' : ''}}">
        <!-- Page Loader -->
        <div class="page-loader-wrapper">
            <div class="loader">
                <div class="preloader">
                    <div class="spinner-layer pl-red">
                        <div class="circle-clipper left">
                            <div class="circle"></div>
                        </div>
                        <div class="circle-clipper right">
                            <div class="circle"></div>
                        </div>
                    </div>
                </div>
                <p>Please wait...</p>
            </div>
        </div>
        <!-- #END# Page Loader -->
        <!-- Overlay For Sidebars -->
        <div class="overlay"></div>
        <!-- #END# Overlay For Sidebars -->

        <!-- Search Bar -->
        <div class="search-bar cmp-search">
            <div class="container">
                <div class="search-icon">
                    <i class="material-icons">search</i>
                </div>
                {!! Form::open(['route' => 'Pages.getSearch']) !!}
                    <input type="text" name="search" minlength="3" required="required" placeholder="Pesquisar por ..."/>
                {!! Form::close() !!}
                <div class="close-search">
                    <i class="material-icons">close</i>
                </div>
            </div>
        </div>

        <!-- Top Bar -->
        <nav class="navbar">
            <div class="container">
                <div class="navbar-header">
                    <a class="navbar-brand cmplogo" href="{{ URL::to('/') }}">
                        <img class="city" src="{{ URL::to('/') }}/files/images/cmp160anos.svg">
                    </a>
                </div>
                <div class="iconmenu">

                    <div class="cpm-social-icon">
                        <a href="{{ $social[0]->facebook }}" target="_blank">
                            <img src="{{ URL::to('/') }}/files/images/social/facebook.svg">
                        </a>
                        <a href="{{ $social[0]->instagram }}" target="_blank">
                            <img src="{{ URL::to('/') }}/files/images/social/instagram.svg">
                        </a>
                        <a href="{{ $social[0]->youtube }}" target="_blank">
                            <img src="{{ URL::to('/') }}/files/images/social/youtube.svg">
                        </a>
                    </div>

                    <a href="javascript:void(0);" class="js-search cmp-icon-search" data-close="true">
                        <i class="material-icons grt-white">search</i>
                        <span>Pesquisar</span>
                    </a>

                    <a data-toggle="collapse" href="#topmenu" class="menu-bar">
                        <i class="material-icons mopen">menu</i>
                        <i class="material-icons mclose">close</i>
                        <span>MENU</span>
                    </a>
                </div>
            </div>

            <!-- #Top Bar -->
            <section class="collapse" id="topmenu">
                <!-- Menu -->
                <div class="container">
                    <div class="row">
                        <div id="cmp-menu" class="cmp_sep_top_padding cmp_sep_bottom_padding">
                            <div class="col-md-9">
                                <div class="row">
                                    @if(isset($menu))
                                        @forelse($menu['parent'] as $key => $parent)
                                            <div class="col-md-{{$menu['col']}}" id="menu-{{$parent->id}}">
                                                <div class="cmp-vertical-menu">
                                                    <div class="cmp-vertical-menu-top">{{$parent->titulo}}</div>
                                                    <ul class="nav nav-pills nav-stacked">
                                                        @forelse($menu['menu'] as $key => $item)
                                                            <li id="item-{{$item->id}}">
                                                                <a href="{{$item->url}}">{{$item->titulo}}</a>
                                                            </li>
                                                        @empty
                                                        @endforelse
                                                    </ul>
                                                </div>
                                            </div>
                                        @empty
                                        @endforelse
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="cmp-vertical-menu noborder">
                                    <a class="cmp-vertical-menu-img" href="" style="background-image: url({{ URL::to('/') }}/images/cmpicons/cmplogo.svg);"></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- #Menu -->
                <div class="cmp-overlay-menu"></div>
            </section>
        </nav>

        <div class="content-home">

            @if (View::hasSection('content'))
                @yield('content')
            @else

            @endif
        </div>

        <div class="content-footer">
            <section id="cmp-footer-contact" class="cmp_sep_bottom_padding cmp_sep_top_padding">
                <div class="container">
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="contact-title">Contacte-nos</div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="contact">
                            <div class="title">Linha Geral</div>
                            <div class="number">534 70 00</div>
                            <div class="timetable">(2ª a 6ª feira, das 8h00 ás 17h30)</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="contact">
                            <div class="title">Linha Saneamento</div>
                            <div class="number">534 70 00</div>
                            <div class="timetable">(2ª a 6ª feira, das 8h00 ás 17h30)</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="contact">
                            <div class="title">Guarda Municipal</div>
                            <div class="number">800 20 20</div>
                            <div class="timetable">(atendimento 24horas)</div>
                        </div>
                    </div>
                </div>
            </section>

            <section id="cmp-footer">
                <div class="panel-footer footer">
                    <div class="container">
                        <div class="col-sm-12 col-md-8 col-xs-12">
                            <i class="material-icons">copyright</i>2018 CMPraia - Gabinete de Informação e Comunicação. Todos os direitos reservados
                        </div>
                        <div class="col-sm-12 col-md-4 col-xs-12">Designed and Developed by <a href="http://www.ideiacv.com" target="_blank">iDE!A</a></div>
                    </div>
                </div>
            </section>
        </div>

        <!-- Jquery Core Js -->
        <script src="{{ URL::to('/') }}/plugins/jquery/jquery.min.js"></script>

        <!-- Bootstrap Core Js -->
        <script src="{{ URL::to('/') }}/plugins/bootstrap/js/bootstrap.js"></script>

        <!-- Waves Effect Plugin Js -->
        <script src="{{ URL::to('/') }}/plugins/node-waves/waves.js"></script>

        <!-- Custom Js -->
        <script src="{{ URL::to('/') }}/js/admin.js"></script>

        @stack('script')

        <script src="{{ URL::to('/') }}/js/script.site.js"></script>
    </body>
</html>