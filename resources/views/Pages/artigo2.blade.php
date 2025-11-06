
<!DOCTYPE html>
<html lang="pt">

    <head>
      <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
 <meta name="csrf-token" content="MR2EBH2LIHatzONEMVLSMJwKhxIlWLpg4XGvYGav">
 <meta http-equiv="Access-Control-Allow-Origin" content="*"/ >
  <meta name="title" content="Instituto de Prevenção e Investigação de Acidentes Aereos e Maritimos  - IPIAAM">
   <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
   
	<meta property="og:title"  content="{!! $art->conteudos[Session::get('lan')]->titulo !!}">
    <meta property="og:url" content="https://ipiaam.cv/artigos/{{$slug}}">
	<meta property="og:description" content="{!! strip_tags($art->conteudos[Session::get('lan')]->titulo) !!}" />
	@if(isset($art->imagems))
	<meta property="og:image:url" content="{{ URL::to('/')}}/files/images/{{$art->imagems->url}}">
	@endif
    <meta property="og:image:width" content="200">
	<meta property="og:image:height" content="200">
	<meta property="og:image:type" content="image/jpeg" />
	<meta property="og:type" content="article" />
	
  <title>Instituto de Prevenção e Investigação de Acidentes Aereos e Maritimos  - IPIAAM</title>
 
  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600" rel="stylesheet">
  <meta name="keywords" content="Instituto de Preven&ccedil;&atilde;o e Investiga&ccedil;&atilde;o de Acidentes Aereos e Maritimos, Cabo Verde, IPIAAM"/>

  <!-- Latest compiled and minified CSS -->
  <!-- Styles -->

  <link rel="icon" href="https://ipiaam.cv/files/images/favicon.png">

  <link rel="stylesheet" href="https://ipiaam.cv/css/bootstrap/bootstrap-theme.min.css">
  <link rel="stylesheet" href="https://ipiaam.cv/css/bootstrap/bootstrap.min.css">
  <link rel="stylesheet" href="https://ipiaam.cv/css/aac.css">
  <link rel="stylesheet" href="https://ipiaam.cv/css/font-awesome.min.css">

  <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

<script src="https://apps.elfsight.com/p/platform.js" defer></script>


  <script type="text/javascript">

    $(function() { var logo = $(".logo"); $(window).scroll(function() {
        var scroll = $(window).scrollTop();

            if (scroll >= 200) {
                logo.show();

            } else {
                logo.hide();

            }

        });
    });
  </script>

       <script>
        window.Laravel = {"csrfToken":"MR2EBH2LIHatzONEMVLSMJwKhxIlWLpg4XGvYGav"};
    </script>

    <!--Scripts Google Analytics-->

        <script>

          (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){

          (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),

          m=s.getElementsByTagName(o)[0];
a.async=1;
a.src=g;
m.parentNode.insertBefore(a,m)

          })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');



          ga('create', 'UA-77530273-1', 'auto');

          ga('send', 'pageview');



</script>
<style type="text/css">

 ::selection {
  background: #4286f4; /* WebKit/Blink Browsers */
}
::-moz-selection {
  background: #4286f4; /* Gecko Browsers */
}
</style>
    </head>
    <body data-spy="scroll" data-target=".navbar" data-offset="50">
        <!-- top menu -->
      <header class="borderBottom bgLogo">
    <div class="container">
       <div class="col-sm-12 col-md-9 col-lg-9">
            <ul class="info_top">   <!-- languages -->
           
             <li  style="padding: 4px;">
               Notifique-nos em caso de acidente:
               <li style="">
              <li style="">
                <a href="mailto:notification@ipiaam.gov.cv"><i class="glyphicon glyphicon-envelope"></i> notification@ipiaam.gov.cv</a>
              </li>
              <li style="">
                
                <a href="#"><i class="glyphicon glyphicon-phone"></i> +238 974 88 85 (AERONÁUTICO)/ 974 88 29 / 8001111(MARÍTIMO)</a>
              </li>
            
            <!-- login -->
           
        </ul>
      </div>
    
   <!--    <div class="col-sm-12 col-md-3 col-lg-3">
        
      </div>

      <div class="col-sm-12 col-md-3 col-lg-3">
         
      </div> -->

      <div class="col-sm-12 col-md-3 col-lg-3" >
         <ul class="language">   <!-- languages -->
           
                           <li style="">
                <a href="https://ipiaam.cv/setLanguage/1">pt</a>
              </li>
                           <li style="">
                <a href="https://ipiaam.cv/setLanguage/2">en</a>
              </li>
                         
            <!-- login -->
           
        </ul>
      </div>

        
    </div>
  </header>
      <!-- Top Bar -->
        




  @include("Pages.partials._menuprincipal") 

                     	
       
       

        <section class="bgBlue">
           <div class="container bgBlue">
              <div class="row">
                 <div class="col-sm-12 col-md-12 col-lg-12" >
                		 	<ul itemscope itemtype="http://schema.org/BreadcrumbList" class="breadcrumb"><li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem" class="" ><a itemprop="item" href="https://ipiaam.cv"><span itemprop="name">Home</span></a><meta itemprop="position" content="1" /> <span class="divider"> </span></li><li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem" class="" ><a itemprop="item" href="https://ipiaam.cv/navart/70"><span itemprop="name">Notícias e Consultas Públicas</span></a><meta itemprop="position" content="2" /> <span class="divider"> </span></li><li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem" class=" active"><span itemprop="name">Noticias</span><meta itemprop="position" content="3" /></li></ul>
  
            </div>
           </div>
          </div>
        </section>

                     <section id="aac-home-wrapper">
                <div class="container">
	<div class="row">

		<div class="col-sm-4 col-md-4 col-lg-3" style="padding:0;">
				<div class="col-md-12"  id="leftcol" style="z-index:9998;">
	         		  <ul class="nav nav-pills nav-stacked aac-menu-stack navbar"  data-spy="affix"  data-offset-top="150" data-offset-bottom="750" style="top:15%;" id="menulateral_fixo" >
			                
								 
							

			                <div id="MainMenu" >
        <div class="list-group panel">

                                                                                <!-- <a href="https://ipiaam.cv#"> -->
                              <a href="#demo0" class="list-group-item text-uppercase bgBlue first" data-toggle="collapse" data-parent="#MainMenu" style="width: 250px;color: #fff;border: none;">
                            
                              

                              <span>Aeronáutico</span>
                              <!-- <i class="glyphicon glyphicon-chevron-right"></i> -->
                              <i class="fa fa-caret-down"></i>
                            </a>
                            <div class="collapse" id="demo0">
                                                                  
                                     <a href="https://ipiaam.cv/artigo/13"  class="list-group-item font12 " style="padding-left:30px;">
                                          Segurança
                                      </a>

                                    <!-- <li role="separator" class="divider"></li> -->

                                                                   
                                     <a href="https://ipiaam.cv#"  class="list-group-item font12 " style="padding-left:30px;">
                                          Investigação
                                      </a>

                                    <!-- <li role="separator" class="divider"></li> -->

                                                                   
                                     <a href="https://ipiaam.cv/navdoc/226"  class="list-group-item font12 " style="padding-left:30px;">
                                          Relatórios
                                      </a>

                                    <!-- <li role="separator" class="divider"></li> -->

                                                                   
                                     <a href="https://ipiaam.cv#"  class="list-group-item font12 " style="padding-left:30px;">
                                          Recomendações
                                      </a>

                                    <!-- <li role="separator" class="divider"></li> -->

                                                                   
                                     <a href="https://ipiaam.cv#"  class="list-group-item font12 " style="padding-left:30px;">
                                          Ocorrências
                                      </a>

                                    <!-- <li role="separator" class="divider"></li> -->

                                                            </div>


                                                                                                <!-- <a href="https://ipiaam.cv#"> -->
                              <a href="#demo1" class="list-group-item text-uppercase bgBlue first" data-toggle="collapse" data-parent="#MainMenu" style="width: 250px;color: #fff;border: none;">
                            
                              

                              <span>Marítimo</span>
                              <!-- <i class="glyphicon glyphicon-chevron-right"></i> -->
                              <i class="fa fa-caret-down"></i>
                            </a>
                            <div class="collapse" id="demo1">
                                                                  
                                     <a href="https://ipiaam.cv/artigo/12"  class="list-group-item font12 " style="padding-left:30px;">
                                          Segurança
                                      </a>

                                    <!-- <li role="separator" class="divider"></li> -->

                                                                   
                                     <a href="https://ipiaam.cv#"  class="list-group-item font12 " style="padding-left:30px;">
                                          Investigação
                                      </a>

                                    <!-- <li role="separator" class="divider"></li> -->

                                                                   
                                     <a href="https://ipiaam.cv/navdoc/225"  class="list-group-item font12 " style="padding-left:30px;">
                                          Relatórios
                                      </a>

                                    <!-- <li role="separator" class="divider"></li> -->

                                                                   
                                     <a href="https://ipiaam.cv/artigo/14"  class="list-group-item font12 " style="padding-left:30px;">
                                          Recomendações
                                      </a>

                                    <!-- <li role="separator" class="divider"></li> -->

                                                                   
                                     <a href="https://ipiaam.cv#"  class="list-group-item font12 " style="padding-left:30px;">
                                          Publicações
                                      </a>

                                    <!-- <li role="separator" class="divider"></li> -->

                                                            </div>


                           

        </div>
      </div>          			</ul>
		        </div>
				


	    </div>
	    <div class="col-sm-8 col-md-8 col-lg-9">
			              	<div class="col-sm-12 col-md-12 col-lg-12" style="padding:0;">
	<h3 class="aac-block-title" style="text-transform:capitalize;margin-left:0;padding-top: 0;margin-top: 0;" >{{ $art->conteudos[Session::get('lan')]->titulo }}
	<span class="aac-lm-date2">{{ substr($art->created_at,0,10) }}</span>
	</h3>
	@if($art->imagems)
	<div class="col-sm-8 col-md-8 col-lg-8 text-center articles-img" style="margin:auto;margin-left: 18%;">
		<img src="{{ URL::to('/')}}/files/images/{{$art->imagems->url}}" width="100%"/>
	</div>
	@endif
	<div class="col-sm-12 col-md-12 col-lg-12 artigos">
		<h4 class="aac-block-title" style="border:none;"></h4>
		{!! $art->conteudos[Session::get('lan')]->texto !!}
	</div>
	<div class="col-sm-12 col-md-12 col-lg-12" style="margin-top:25px;">
		<span style="color:#23527c;">Tags: </span>@foreach($art->tags as $item)
		<a href="{{ URL::to('/') }}/navtag/{{ $item['id']}}"><span class="tags shadow">{{ $item['name']}}</span></a>
		@endforeach
	</div>
	<div class="col-sm-12 col-md-12 col-lg-12" style="margin-top:25px;">
		<h4 style="text-transform:capitalize;margin-left:0;padding-top: 0;margin-top: 0;" >Partilhar</h3>
		@include("Pages.partials._share",['url' => "/artigo/".$art->id ])
	</div> 



	<!-- Documentos Anexados -->
	@foreach($artDocs as $items)
		@if($items->anexos->count() > 0)
			<div class="col-sm-12 col-md-12 col-lg-12" style="margin-top:25px;">
				<div class="">
					<h4  style="margin-left:0;text-align:left;">Documentos Anexados</h4>
					<ul>
						@foreach ($items->anexos as $item)
							@php
								$url2 = json_decode($item['url']);
							@endphp
							<li>
								<a href="{{ URL::to('/') }}/documento/opendoc/{{ $url2->pt }}" title="{{ $item['nome'] }}" target="_blank" style="width:87%;">
									<p style="margin-left:0;">
										<img class="" src="{{URL::to('/')}}/files/images/pdf_icon.png" alt="...">
										<span class="aac-lm-content ellipsed">{{ $item['nome']}}</span>{{-- [Session::get('lan')]['titulo'] --}}
									</p>
								</a>
								<span class="aac-lm-date">{{substr($item['data_criacao'],0,10) }}</span>
								
							</li>
						@endforeach
					</ul>
				</div>
			</div>
		@endif
	@endforeach 
	<!-- FIM Documentos Anexados -->



	@if(count($docrel)>0)
		<div class="col-sm-12 col-md-12 col-lg-12" style="margin-top:25px;">
			<div class="aac-list-menu">
				<h4 class="aac-block-title" style="margin-left:0;text-align:left;">Documentos Relacionados</h4>
				<ul>
					@foreach($docrel as $item)
					<li>
						<a href="{{ URL::to('/') }}/documento/{{ $item['id'] }}" title="{{ $item['nome'] }}" style="width:87%;">
							<p style="margin-left:0;">
								<span class="aac-lm-content ellipsed">{{ $item['conteudos'][Session::get('lan')]['titulo'] }}</span>
								<span class="aac-lm-date">{{substr($item['created_at'],0,10) }}</span>

							</p>
						</a>
						<span class="aac-lm-date tag_list" style="text-transform:Capitalize;font-weight:bold;">
									<span class="tag_title">Tags: </span>
									@foreach($item['tags'] as $item)
									<a href="{{ URL::to('/') }}/navtag/{{ $item['id']}}" style="float: left;" ><span class="tags shadow">{{ $item['name']}}</span></a>
									@endforeach
								</span>
					</li>
					@endforeach   {{-- {{ $item['id'] }} {{ $item['descricao'] }}--}}
				</ul>
			</div>
		</div>
	@endif
	@if(count($artrel)>1)
		<div class="col-sm-12 col-md-12 col-lg-12" style="margin-top:25px;">
			<div class="aac-list-menu">
				<h4 class="aac-block-title" style="margin-left:0;text-align:left;">Artigos Relacionados</h4>
				<ul>
					@foreach($artrel as $item)
						@if($item['id']!= $art->id)
							<li>
								<a href="{{ URL::to('/') }}/artigo/{{ $item['id'] }}" title="{{ $item['nome'] }}" style="width:87%;">
									<p style="margin-left:0;">
										<span class="aac-lm-content ellipsed">{{ $item['conteudos'][Session::get('lan')]['titulo'] }}</span>
										<span class="aac-lm-date">{{substr($item['created_at'],0,10) }}</span>

									</p>
								</a>
								<span class="aac-lm-date tag_list" style="text-transform:Capitalize;font-weight:bold;">
											<span class="tag_title">Tags: </span>
											@foreach($item['tags'] as $item)
											<a href="{{ URL::to('/') }}/navtag/{{ $item['id']}}" style="float: left;" ><span class="tags shadow">{{ $item['name']}}</span></a>
											@endforeach
										</span>
							</li>
						 @endif
					@endforeach   {{-- {{ $item['id'] }} {{ $item['descricao'] }}--}}
				</ul>
			</div>
		</div>
	@endif


</div>

	</div> 



	<!-- Documentos Anexados -->
				 
	<!-- FIM Documentos Anexados -->



		

</div>
      		 	    </div>
	 </div>
 	</div>

          </section>

        

       <!-- home menu and articles slides -->

  <!-- /home menu and articles slides -->


    <!DOCTYPE html>
<html lang="en">
<head>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.js"></script>
<style>
h2{
  text-align:center;
  padding: 20px;
}
/* Slider */

.slick-slide {
    margin: 0px 20px;
}

.slick-slide img {
    width: 100%;
}

.slick-slider
{
    position: relative;
    display: block;
    box-sizing: border-box;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
            user-select: none;
    -webkit-touch-callout: none;
    -khtml-user-select: none;
    -ms-touch-action: pan-y;
        touch-action: pan-y;
    -webkit-tap-highlight-color: transparent;
}

.slick-list
{
    position: relative;
    display: block;
    overflow: hidden;
    margin: 0;
    padding: 0;
}
.slick-list:focus
{
    outline: none;
}
.slick-list.dragging
{
    cursor: pointer;
    cursor: hand;
}

.slick-slider .slick-track,
.slick-slider .slick-list
{
    -webkit-transform: translate3d(0, 0, 0);
       -moz-transform: translate3d(0, 0, 0);
        -ms-transform: translate3d(0, 0, 0);
         -o-transform: translate3d(0, 0, 0);
            transform: translate3d(0, 0, 0);
}

.slick-track
{
    position: relative;
    top: 0;
    left: 0;
    display: block;
}
.slick-track:before,
.slick-track:after
{
    display: table;
    content: '';
}
.slick-track:after
{
    clear: both;
}
.slick-loading .slick-track
{
    visibility: hidden;
}

.slick-slide
{
    display: none;
    float: left;
    height: 100%;
    min-height: 1px;
}
[dir='rtl'] .slick-slide
{
    float: right;
}
.slick-slide img
{
    display: block;
}
.slick-slide.slick-loading img
{
    display: none;
}
.slick-slide.dragging img
{
    pointer-events: none;
}
.slick-initialized .slick-slide
{
    display: block;
}
.slick-loading .slick-slide
{
    visibility: hidden;
}
.slick-vertical .slick-slide
{
    display: block;
    height: auto;
    border: 1px solid transparent;
}
.slick-arrow.slick-hidden {
    display: none;
}

.slick-prev {
    position: absolute;
    top: 50%;
    left: -4%;
}

.slick-next {
    position: absolute;
    top: 50%;
    right: -4%;
}
</style>

</head>
<body>
   



<!------ Include the above in your HEAD tag ---------->

<section id="aac-parceiro ">
 
    <div class="container top50 bottom50">
      <div class="row top30 bottom30">
          <div class="col-md-12 top30">
            
                <section class="customer-logos slider"> 
                                            <div class="slide">
                            <a href="https://www.asa.cv/" target="_blank"><img src="https://ipiaam.cv/files/images/15625442811650342186.jpg" alt="ASA"></a>
                        </div>
                                            <div class="slide">
                            <a href="http://www.enapor.cv/page/homepage" target="_blank"><img src="https://ipiaam.cv/files/images/1562544282625839913.jpg" alt="ENAPOR"></a>
                        </div>
                                            <div class="slide">
                            <a href="https://www.governo.cv/" target="_blank"><img src="https://ipiaam.cv/files/images/15625442821226782268.jpg" alt="Governo"></a>
                        </div>
                                            <div class="slide">
                            <a href="https://www.icao.int/Pages/default.aspx" target="_blank"><img src="https://ipiaam.cv/files/images/15625442821157443830.jpg" alt="ICAO"></a>
                        </div>
                                            <div class="slide">
                            <a href="http://www.imo.org/en/Pages/Default.aspx" target="_blank"><img src="https://ipiaam.cv/files/images/15625442822066873324.jpg" alt="International Maritime Organization"></a>
                        </div>
                                            <div class="slide">
                            <a href="http://www.imp.cv/" target="_blank"><img src="https://ipiaam.cv/files/images/15625442821841183466.jpg" alt="Instituto Marítimo Portuário"></a>
                        </div>
                                            <div class="slide">
                            <a href="https://www.facebook.com/pages/category/Government-Organization/Guarda-Costeira-de-Cabo-Verde-364043643721844/" target="_blank"><img src="https://ipiaam.cv/files/images/15625442821007776062.jpg" alt="Guarda Costeira Cabo Verde"></a>
                        </div>
                                            <div class="slide">
                            <a href="http://aac.cv/" target="_blank"><img src="https://ipiaam.cv/files/images/1562544281397693072.jpg" alt="Agência de Aviação Civil"></a>
                        </div>
                                    </section>
            
          </div>
      </div><!--.container-->
    </div><!--.container-->
  </section>



  

<script>
$(document).ready(function(){
    $('.customer-logos').slick({
        slidesToShow: 4,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 2500,
        arrows: true,
        nextArrow: '<div class="slick-prev"><span class="fa fa-angle-left"></span><span class="sr-only">Prev</span></div>',
        prevArrow: '<div class="slick-next"><span class="fa fa-angle-right"></span><span class="sr-only">Next</span></div>',
        dots: false,
        pauseOnHover: false,
        responsive: [{
            breakpoint: 768,
            settings: {
                slidesToShow: 4
            }
        }, {
            breakpoint: 520,
            settings: {
                slidesToShow: 1
            }
        }]
    });
});

</script>

</body>
</html>


    </body>


         <footer >
  <div class="footer" id="footer">
   
    <div class="container">
      <div class="row"> 
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 top30">
             <a class="navbar-brand cmplogo" style="padding-bottom: 30px; margin-top: 0px!important;" href="https://ipiaam.cv">
                            <img class="city" src="https://ipiaam.cv/files/images/logo_white.png" style="height:80px!important;">
                         </a>
          </div>
        
      </div>
      <div class="row " style="border-bottom:1px solid rgba(255,255,255,0.4);"></div>
      
      <div class="row"> 
       
         <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12 top30">
             <div class="col-12 ">
                  <h4 class="fontBold fwhite textUpper tituloFooter bottom20">Mapa do Site</h4> 
                </div>
                <div class="d-flex flex-column">
                 
                                                     <div class="p-2 fwhite"><a href="https://ipiaam.cv/artigo/1">Sobre Nós</a></div>
                                                             <div class="p-2 fwhite"><a href="https://ipiaam.cv/artigo/2">Atribuições</a></div>
                                                             <div class="p-2 fwhite"><a href="https://ipiaam.cv/navdoc/4">Legislação</a></div>
                                                             <div class="p-2 fwhite"><a href="https://ipiaam.cv/navdoc/219">Regulamentos</a></div>
                                            </div>
          </div>

         <!-- END block1 -->

        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12 top30 ">
          <div class="col-12 ">
                  <h4 class="fontBold fwhite textUpper tituloFooter bottom20">Links Úteis</h4> 
                </div>
          <div class="d-flex flex-column">
             
                                               <div class="p-2 fwhite"><a target="_blank" href="http://www.governo.cv/">Governo de Cabo Verde</a></div>
                                                              <div class="p-2 fwhite"><a target="_blank" href="https://www.icao.int/Pages/default.aspx">ICAO</a></div>
                                                              <div class="p-2 fwhite"><a target="_blank" href="https://www.bagasoo.org/beta/">BAGASOO</a></div>
                                                              <div class="p-2 fwhite"><a target="_blank" href="http://www.imo.org">IMO</a></div>
                                      </div>

        </div>

        <!-- END block2 -->

        
           <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12 top30" >
               <div class="col-12 ">
                  <h4 class="fontBold fwhite textUpper tituloFooter bottom20">Contactos</h4> 
                </div>
              <div class="media mtop0" style="height: 40px;">
                <div class="media-left">
                  <a href="#">
                    <img class="media-object" src="https://ipiaam.cv/files/images/phone.png" alt="...">
                  </a>
                </div>
                <div class="media-body">
                  <p class="font11 fwhite">+238 230 09 92</p>
                </div>
              </div>
               <div class="media mtop0" style="height: 40px;">
                  <div class="media-left">
                    <a href="#">
                      <img class="media-object" src="https://ipiaam.cv/files/images/mail.png" alt="...">
                    </a>
                  </div>
                  <div class="media-body">
                  
                    <p class="font11 fwhite">infor@ipiaam.cv </p>
                    
                  </div>
                </div>
                 <div class="media mtop0" style="height: 40px;">
                  <div class="media-left">
                    <a href="#">
                      <img class="media-object" src="https://ipiaam.cv/files/images/location.png" alt="...">
                    </a>
                  </div>
                  <div class="media-body">
                  
                    <p class="font11 fwhite">Sede do IPIAAM: Rua Angola, Mindelo, São Vicente, Cabo Verde</p>
                    
                  </div>
                </div>
                
                <div class="media mtop0" style="height: 40px;">
                  <div class="media-left">
                    <a href="#">
                      <img class="media-object" src="https://ipiaam.cv/files/images/location.png" alt="...">
                    </a>
                  </div>
                  <div class="media-body">
                    <p class="font11 fwhite">Delegação do IPIAAM: Achada Grande Frente, Praia, Santiago, Cabo Verde</p>
                  </div>
                </div>

             
        </div>

         <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12 top30 ">
           <div class="col-12 ">
              <h4 class="fontBold fwhite textUpper tituloFooter bottom20">Newsletter</h4> 
            </div>
            <form id="form_news">
              <div class="form-group">
                <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" required>
              </div>
              <button type="submit" class="btn" style="background-color: #1560B0; color:aliceblue;">Subscribe</button>
            </form>
            <div id="id_response"></div>
        </div>

     </div>
    </div>
  </div>

   <div class="footer footer2" >
    <div class="container">
        <div class="row">
          <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" >
             <div class="copyright-tag">Copyright © 2021 CPIAA - Cabo Verde. All Rights Reserved.
              </div>
              
          </div>
          <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" >
             
          </div>
          <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" >
             
              <div class="copyright-tag">Designed and Developed by <a href="https://www.ideia.cv" target="_blank" style="font-size: 10px;">iDE!A</a>
              </div>
          </div>
        
        </div>
     </div>
   </div>

</footer>


    

         		<script>
		// Carousel Auto-Cycle
		  $(document).ready(function() {
		    $('#myCarousel').carousel({
		      interval: 9000
		    })

		    $('#myCarousel2').carousel({
		      interval: 9000
		    })


		  //   $('#menulateral_fixo').affix({
				//   offset: {
				//     top: 500,
				//     bottom: function () {
				//       return (
				//       	this.bottom = $('#footer').outerHeight(true) + 100
				//       	)
				//     }
				//   }
				// })


		 //  	$('#menulateral_fixo').affix({
			//       offset: {
			//         /* affix after top masthead */
			//         top: function () {
			//             var navOuterHeight = $('#aac-midmenu').height();
			//             return this.top = navOuterHeight;
			//         },
			//         /* un-affix when footer is reached */
			//         bottom: function () {
			//             return (this.bottom = $('footer').outerHeight(true))
			//         }
			//       }
			// });


			$('#menulateral_fixo').affix({
			    offset: {
			    	  top: $('#aac-midmenu').offset().top ,
			       bottom: ($('footer').outerHeight(true)) + 190,


			    }
			});

 	});






		</script>
</html>
