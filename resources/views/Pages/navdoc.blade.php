@extends('Pages.nav')

@section('bcrumbs')
	  {!! $crumbs !!}

@endsection

@section('content2')

			<div class="col-sm-12 col-md-12 col-lg-12" style="padding:0;">
				<div class="aac-list-menu">
	              <h4 class="aac-block-title" style="margin-left:0;text-align:left;">Pastas</h4>
	              <ul>
	              @foreach($pastas as $item)
	                     <li>
	                      <a href="{{ URL::to('/') }}/navdoc/{{ $item['id'] }}" title="{{ $item['titulo'] }}">
	                        <i class="glyphicon glyphicon-folder-open fontBlue folder_position"></i>
	                        <span class="aac-lm-content ellipsed fontBlue folder_text">{{ $item['titulo'] }}</span>

	                      </a>
	                    </li>
	                @endforeach
	              </ul>
	            </div>

				@if(isset($documentos) && count($documentos)>0)
				@php
					$lan = Session::get('lan') ?? 0;
				@endphp
	            <div class="aac-list-menu">
	              <h4 class="aac-block-title" style="margin-left:0;text-align:left;">Documentos</h4>
	              <ul>
	              @foreach($documentos as $item)

	                    <li>
	                      <a href="{{ URL::to('/') }}/documento/{{ $item['id'] }}" title="{{ $item['nome'] ?? '' }}" style="width:87%;">
	                        @if(isset($item['tipos']['titulo']))
	                        	<img src="{{ URL::to('/') }}/files/images/{{ $item['tipos']['titulo'] }}.png"/>
	                        @endif
	                        <p>
	                          <span class="aac-lm-content ellipsed">{{ $item['conteudos'][$lan]['titulo'] ?? $item['nome'] }}</span>
	                          <span class="aac-lm-date">{{substr($item['created_at'],0,10) }}</span>
	                          <span class="aac-lm-content ellipsed" style="font-size: 10px;color: #000;">{!! strip_tags($item['descricao'] ?? '') !!}</span>
	                        </p>
	                      </a>

	                    @if(isset($item['tags']) && count($item['tags']) > 0)
	                    <div class="aac-lm-date tag_list" style="padding-left: 40px; text-transform:Capitalize;font-weight:bold;">
								<span class="tag_title">Tags: </span>
								@foreach($item['tags'] as $tag)
									<a href="{{ URL::to('/') }}/navtag/{{$tag['id']}}" style="float: left;" >
										<span class="tags shadow">{{$tag['name']}}</span>
									</a>
								@endforeach
						 </div>
						 @endif

						  <a class="btn btn-default fontBlue icon_download" target="__blank" href="{{ URL::to('/') }}/documento/opendoc/{{$item['url']}}" role="button"> <span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span></a>

	                    </li>
	                @endforeach

	              </ul>
	            </div>
				@endif
				<div class="text-center">
					{!! $documentos->links(); !!}
	            </div>


			</div>

@endsection
