@extends('Pages.nav')

@section('keywords')
<meta name="keywords" content="{{ $art->keyword ?? '' }} "/>

		@php
			$lan = Session::get('lan') ?? 0;
		@endphp
		@if(isset($art->conteudos[$lan]))
			<meta property="og:title"  content="{!! $art->conteudos[$lan]->titulo !!}">
			<meta property="og:description" content="{!! strip_tags($art->conteudos[$lan]->titulo) !!}" />
		@endif
		@if(isset($art->imagems->url))
	        <meta property="og:image:url" content="{{ URL::to('/')}}/files/images/{{$art->imagems->url}}">
	        <meta property="og:image:width" content="200">
	        <meta property="og:image:height" content="200">
	      <meta property="og:image:type" content="image/jpeg" />
		@endif
		<meta property="og:type"               content="article" />
@endsection

@section('bcrumbs')
	{!! $crumbs !!}
@endsection

@section('content2')
<div class="col-sm-12 col-md-12 col-lg-12" style="padding:0;">
	@php
		$lan = Session::get('lan') ?? 0;
	@endphp
	<h3 class="aac-block-title" style="text-transform:capitalize;margin-left:0;padding-top: 0;margin-top: 0;" >{{ $art->conteudos[$lan]->titulo ?? $art->alias }}
	<span class="aac-lm-date2">{{ substr($art->created_at,0,10) }}</span>
	</h3>
	@if($art->imagems)
	<div class="col-sm-8 col-md-8 col-lg-8 text-center articles-img" style="margin:auto;margin-left: 18%;">
		<img src="{{ URL::to('/')}}/files/images/{{$art->imagems->url}}" width="100%"/>
	</div>
	@endif
	<div class="col-sm-12 col-md-12 col-lg-12 artigos">
		<h4 class="aac-block-title" style="border:none;"></h4>
		{!! $art->conteudos[$lan]->texto ?? '' !!}
	</div>
	@if($art->tags && $art->tags->count() > 0)
	<div class="col-sm-12 col-md-12 col-lg-12" style="margin-top:25px;">
		<span style="color:#23527c;">Tags: </span>@foreach($art->tags as $item)
		<a href="{{ URL::to('/') }}/navtag/{{ $item['id']}}"><span class="tags shadow">{{ $item['name']}}</span></a>
		@endforeach
	</div>
	@endif
	<div class="col-sm-12 col-md-12 col-lg-12" style="margin-top:25px;">
		<h4 style="text-transform:capitalize;margin-left:0;padding-top: 0;margin-top: 0;" >Partilhar</h3>
		@include("Pages.partials._share",['url' => "/artigo/".$art->id ])
	</div> 



	<!-- Documentos Anexados -->
	@if(isset($artDocs) && $artDocs->count() > 0)
		@foreach($artDocs as $items)
			@if($items->anexos && $items->anexos->count() > 0)
				<div class="col-sm-12 col-md-12 col-lg-12" style="margin-top:25px;">
					<div class="">
						<h4  style="margin-left:0;text-align:left;">Documentos Anexados</h4>
						<ul>
							@foreach ($items->anexos as $item)
								@php
									$url2 = json_decode($item['url']);
									$urlPt = $url2->pt ?? ($url2->en ?? '');
								@endphp
								@if($urlPt)
								<li>
									<a href="{{ URL::to('/') }}/documento/opendoc/{{ $urlPt }}" title="{{ $item['nome'] }}" target="_blank" style="width:87%;">
										<p style="margin-left:0;">
											<img class="" src="{{URL::to('/')}}/files/images/pdf_icon.png" alt="...">
											<span class="aac-lm-content ellipsed">{{ $item['nome']}}</span>
										</p>
									</a>
									<span class="aac-lm-date">{{substr($item['data_criacao'],0,10) }}</span>
									
								</li>
								@endif
							@endforeach
						</ul>
					</div>
				</div>
			@endif
		@endforeach
	@endif 
	<!-- FIM Documentos Anexados -->



	@if(isset($docrel) && count($docrel)>0)
		<div class="col-sm-12 col-md-12 col-lg-12" style="margin-top:25px;">
			<div class="aac-list-menu">
				<h4 class="aac-block-title" style="margin-left:0;text-align:left;">Documentos Relacionados</h4>
				<ul>
					@foreach($docrel as $item)
					@php
						$lan = Session::get('lan') ?? 0;
					@endphp
					<li>
						<a href="{{ URL::to('/') }}/documento/{{ $item['id'] }}" title="{{ $item['nome'] }}" style="width:87%;">
							<p style="margin-left:0;">
								<span class="aac-lm-content ellipsed">{{ $item['conteudos'][$lan]['titulo'] ?? $item['nome'] }}</span>
								<span class="aac-lm-date">{{substr($item['created_at'],0,10) }}</span>

							</p>
						</a>
						@if(isset($item['tags']) && count($item['tags']) > 0)
						<span class="aac-lm-date tag_list" style="text-transform:Capitalize;font-weight:bold;">
									<span class="tag_title">Tags: </span>
									@foreach($item['tags'] as $tag)
									<a href="{{ URL::to('/') }}/navtag/{{ $tag['id']}}" style="float: left;" ><span class="tags shadow">{{ $tag['name']}}</span></a>
									@endforeach
								</span>
						@endif
					</li>
					@endforeach
				</ul>
			</div>
		</div>
	@endif
	@if(isset($artrel) && count($artrel)>1)
		<div class="col-sm-12 col-md-12 col-lg-12" style="margin-top:25px;">
			<div class="aac-list-menu">
				<h4 class="aac-block-title" style="margin-left:0;text-align:left;">Artigos Relacionados</h4>
				<ul>
					@foreach($artrel as $item)
						@if($item['id']!= $art->id)
							@php
								$lan = Session::get('lan') ?? 0;
							@endphp
							<li>
								<a href="{{ URL::to('/') }}/artigo/{{ $item['id'] }}" title="{{ $item['alias'] ?? '' }}" style="width:87%;">
									<p style="margin-left:0;">
										<span class="aac-lm-content ellipsed">{{ $item['conteudos'][$lan]['titulo'] ?? $item['alias'] }}</span>
										<span class="aac-lm-date">{{substr($item['created_at'],0,10) }}</span>

									</p>
								</a>
								@if(isset($item['tags']) && count($item['tags']) > 0)
								<span class="aac-lm-date tag_list" style="text-transform:Capitalize;font-weight:bold;">
											<span class="tag_title">Tags: </span>
											@foreach($item['tags'] as $tag)
											<a href="{{ URL::to('/') }}/navtag/{{ $tag['id']}}" style="float: left;" ><span class="tags shadow">{{ $tag['name']}}</span></a>
											@endforeach
										</span>
								@endif
							</li>
						 @endif
					@endforeach
				</ul>
			</div>
		</div>
	@endif


</div>
@endsection