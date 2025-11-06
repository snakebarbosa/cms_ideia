
  <section>

    <div class="container top50 border_">

      <div class="row">

        {{-- <div class="col-sm-6 col-md-6 col-lg-6 " style="height: 418px;background-size: cover;background-image: url( {{ asset('/images/plane.jpg') }} )">	
		</div> --}}
		<div class="col-sm-6 col-md-6 col-lg-6 " style="height: 418px;">
			
          <div id="aac-articles-slide" class="carousel shadow" data-ride="carousel">
            <ol class="carousel-indicators col-sm-4">

                @php  $i = 0;  @endphp
				@foreach($news as $item) 
					@if($i == 0)
						<li data-target="#aac-articles-slide" class="active" data-slide-to="@php echo $i; @endphp"> </li>
					@else
						<li data-target="#aac-articles-slide" data-slide-to="@php echo $i; @endphp" class=""></li>
					@endif
					@php  $i++;  @endphp
				@endforeach
            </ol>
            <div class="carousel-inner">

             	@php $i = 0; @endphp
				@foreach($news as $item)
					@if($i == 0)
						<div class="item active" style="background-image:url('{{URL::to('/')}}/files/images/{{$item['imagems']['url']}}')">
						@php $i = 1; @endphp
					@else
						<div class="item" style="background-image:url('{{URL::to('/')}}/files/images/{{$item['imagems']['url']}}')">
					@endif

						<div class="aac-art-slide-content col-sm-5">
							<h4 style="margin-top: 20px;"><a href="{{ URL::to('/') }}/artigo/{{ $item['id'] }}">{{ $item['conteudos'][Session::get('lan')]['titulo']  }}</a></h4>
							<div class="aac-art-date">{{substr($item['data_criacao'],0,10) }}</div>
						</div>
					</div>
				@endforeach
            </div>
          </div>
		</div>



		
		{{-- <div class="col-sm-6 col-md-6 col-lg-6 tab_container" style="height: 400px; padding-left: 36px;">
			<ul class="nav nav-tabs tab_intro">
			  <li role="presentation" class="active"><a data-toggle="tab" href="#tab11">{{ $tab1pos1->conteudos[Session::get('lan')]->titulo }}</a></li>
			  <li role="presentation"><a data-toggle="tab" href="#tab12">{{ $tab1pos2->conteudos[Session::get('lan')]->titulo }}</a></li>
			  <li role="presentation"><a data-toggle="tab" href="#tab13">{{ $tab1pos3->conteudos[Session::get('lan')]->titulo }}</a></li>
			</ul>
			  <div class="tab-content">
				  <div id="tab11" class="tab-pane fade in active">
		               <!-- <h3 class="fontBold">{{ $tab1pos1->titulo }}</h3> -->
		              <p>{!!  substr($tab1pos1->conteudos[Session::get('lan')]->texto,0,500) !!}...</p>
					<span><a href="{{route('artigo',$tab1pos1->id)}}" class="link_more">@if(Session::get('lan')==0) Ler Mais @else Read More  @endif</a></span>
		           </div>
		            <div id="tab12" class="tab-pane fade">
		              <!-- <h3 class="fontBold">{{ $tab1pos2->titulo }}</h3> -->
		              <p>{!!  substr($tab1pos2->conteudos[Session::get('lan')]->texto,0,500)  !!}...</p>
					  <span><a href="{{route('artigo',$tab1pos2->id)}}" class="link_more">@if(Session::get('lan')==0) Ler Mais @else Read More  @endif</a></span>
		            </div>
		             <div id="tab13" class="tab-pane fade">
		              <!-- <h3 class="fontBold">{{ $tab1pos3->titulo }}</h3> -->
		              <p>{!!  substr($tab1pos3->conteudos[Session::get('lan')]->texto,0,500)  !!}...</p>
						<span><a href="{{route('artigo',$tab1pos3->id)}}" class="link_more">@if(Session::get('lan')==0) Ler Mais @else Read More  @endif</a></span>
		            </div>
			 </div>
		</div> --}}

		<div class="col-sm-6 col-md-6 col-lg-6 tab_container" style="height: 400px; padding-left: 36px;">
			@forelse($allNews as $item)
				<div class="media">
					<div class="media-body">
						<small class="data_doc">{{substr($item['data_criacao'],0,10) }}</small>
						<a href="{{ URL::to('/') }}/artigo/{{ $item['id'] }}"><h4 class="media-heading" style="color:#303030!important;">{{ $item['conteudos'][Session::get('lan')]['titulo'] }}</h4></a>
						
					</div>
				</div>
			@empty
			@endforelse  
			<span><a href="{{ URL::to('/') }}/navart/{{$catnoticia}}" class="link_more pull-right" style="margin-top: 20px;">@if(Session::get('lan')==0) Ver Mais @else See More  @endif</a></span>
		</div>
		<!-- ##### end tab -->

      </div>

    </div>
  </section>
