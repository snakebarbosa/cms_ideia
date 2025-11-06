@extends('Pages.nav')

@section('keywords')
<meta name="keywords" content="{{ $art->keyword }} "/>
		<meta property="og:title"  content="{!! $art->conteudos[Session::get('lan')]->titulo !!}">
		<meta property="og:description" content="{!! strip_tags($art->conteudos[Session::get('lan')]->texto) !!}" />
		@if(isset($art->imagems->url))
	        <meta property="og:image:url" content="http://aac.cv/images/{{ $art->imagems->url }}">
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

@include("Pages.partials._tagmenu")
<div class="col-sm-12 col-md-12 col-lg-12" style="padding:0;">
	<h3 class="aac-block-title" style="text-transform:capitalize;margin-left:0;padding-top: 0;margin-top: 0;" >{{ $art->conteudos[Session::get('lan')]->titulo }}
	<span class="aac-lm-date2">{{ substr($art->created_at,0,10) }}</span>
	</h3>
	@if($art->imagems)
	<div class="col-sm-8 col-md-8 col-lg-8 text-center articles-img" style="margin:auto;margin-left: 18%;">
		<img src="{{ URL::to('/')}}/files/images/{{$art->imagems->url}}" width="100%"/>
	</div>
	@endif
	<div class="col-sm-12 col-md-12 col-lg-12">
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
		@include("Pages.partials._share",['url' => "{{ URL::to('/') }}/artigo/".$art->id ])
	</div>
	@if(count($docrel)>0)
	<div class="col-sm-12 col-md-12 col-lg-12" style="margin-top:25px;">
		<div class="aac-list-menu">
			<h4 class="aac-block-title" style="margin-left:0;text-align:left;">Documentos Relacionados</h4>
			<ul>
				@foreach($docrel as $item)
					@if($item['ativado']== 1)
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
					@endif
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
					@if($item['id']!= $art->id && $item['ativado']== 1)
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
@endsection