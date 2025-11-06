@extends('Pages.home')

@section('title')
	@yield('keywords')

@endsection

@section('breacrumbs')
  @if(View::hasSection('bcrumbs'))
		 @yield('bcrumbs')
  @else
		{!! $crumbs2 !!}
  @endif

@endsection

@section('slide')
	
@endsection

@section('content')
  <div class="container">
	<div class="row">

		<div class="col-sm-4 col-md-4 col-lg-3" style="padding:0;">
				<div class="col-md-12"  id="leftcol" style="z-index:9998;">
	         		  <ul class="nav nav-pills nav-stacked aac-menu-stack navbar"  data-spy="affix"  data-offset-top="150" data-offset-bottom="750" style="top:15%;" id="menulateral_fixo" >
			                
								 {{--@foreach($left as $item)
			                         <li>
			                           @if('http://' . $_SERVER['HTTP_HOST'] .$_SERVER['REQUEST_URI'] ==  URL::to('/') .$item['url'])


			                            	@if(strpos($item['url'], 'http') !== false)
					                               <a href="{{ $item['url'] }}" target="__blank" class="active">
					                            @else
					                               <a href="{{ URL::to('/') }}{{ $item['url'] }}" class="active">
					                            @endif
			                             @else

			                              @if(strpos($item['url'], 'http') !== false)
					                               <a href="{{ $item['url'] }}" target="__blank">
					                            @else
					                               <a href="{{ URL::to('/') }}{{ $item['url'] }}">
					                            @endif
			                            @endif

			                             
			                              <span>{{ $item['titulo'] }}</span>
			                              <i class="glyphicon glyphicon-chevron-right "></i>
			                            </a>
			                          </li>

			               		@endforeach  --}}
							

			                @include("Pages.partials._menuleftnav")
          			</ul>
		        </div>
				{{--  @include("Pages.partials._navdoc") --}}


	    </div>
	    <div class="col-sm-8 col-md-8 col-lg-9">
			 @if(View::hasSection('content2'))
             	 @yield('content2')
      		 @else

				 @include("Pages.partials._navnews")
				  @include("Pages.partials._menutag")
				  @include("Pages.partials._faqtag")
				   @include("Pages.partials._linktag")
				    @include("Pages.partials._navtagrel")
				
	   		 @endif
	    </div>
	 </div>
 	</div>

@endsection


@section('script_bottom')
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
@endsection