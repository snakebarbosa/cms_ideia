@extends('Pages.nav')

@section('bcrumbs')
	  {!! $crumbs !!}

@endsection

@section('content2')

			<div class="col-sm-12 col-md-12 col-lg-12" style="padding:0;">
				<div class="aac-list-menu">
	              <h4 class="aac-block-title" style="margin-left:0;text-align:left;">Categorias</h4>
	              <ul>
	              @foreach($pastas as $item)
	                     <li>
	                      <a href="{{ URL::to('/') }}/navart/{{ $item['id'] }}" title="{{ $item['titulo'] }}">
	                        <i class="glyphicon glyphicon-folder-open fontBlue folder_position"></i>
	                        <span class="aac-lm-content ellipsed fontBlue folder_text">{{ $item['titulo'] }}</span>

	                      </a>
	                    </li>
	                @endforeach
	              </ul>
	            </div>


	            <div class="aac-list-menu">
	              <h4 class="aac-block-title" style="margin-left:0;text-align:left;">Artigos</h4>
	              <ul>
	              @php
					$lan = Session::get('lan') ?? 0;
				  @endphp
	              @foreach($artigos as $item)
	                    <li>
	                      <a href="{{ URL::to('/') }}/artigo/{{ $item['id'] }}" title="{{ $item['alias'] ?? '' }}" style="width:87%;">

	                        <p style="margin-left:0;">
	                          <span class="aac-lm-content ellipsed">{{ $item['conteudos'][$lan]['titulo'] ?? $item['alias'] }}</span>
	                          <span class="aac-lm-date">{{substr($item['created_at'],0,10) }}</span>

	                        </p>
	                      </a>

	                      @if(isset($item['tags']) && count($item['tags']) > 0)
	                      <div class="aac-lm-date tag_list" style="text-transform:Capitalize;font-weight:bold;">
								<span class="tag_title">Tags: </span>
								@foreach($item['tags'] as $tag)
									<a href="{{ URL::to('/') }}/navtag/{{ $tag['id']}}" style="float: left;" >
										<span class="tags shadow">{{ $tag['name']}}</span>
									</a>
								@endforeach
						 </div>
						 @endif

	                    </li>
	                @endforeach   {{-- {{ $item['id'] }} {{ $item['descricao'] }}--}}

	              </ul>
	            </div>
	            <div class="text-center">
					{!! $artigos->links(); !!}
	            </div>
			</div>

@endsection