@extends('Pages.nav')

@section('bcrumbs')
	  {!! $crumbs !!}

@endsection

@section('content2')

			<div class="col-sm-12 col-md-12 col-lg-12" style="padding:0;">


	             <div class="aac-list-menu">
	              <h4 class="aac-block-title" style="margin-left:0;text-align:left;">Documentos</h4>
	              <ul>
	              @foreach($docrel as $item)
	                    <li>
	                      <a href="{{ URL::to('/') }}/documento/{{ $item['id'] }}" title="{{ $item['nome'] }}" style="width:87%;">
	                        <img src="{{ URL::to('/') }}/files/images/pdf.png"/>
	                        <p>
	                          <span class="aac-lm-content ellipsed">{{ $item['nome'] }}</span>
	                          <span class="aac-lm-date">{{substr($item['created_at'],0,10) }}</span>
	                          <span class="aac-lm-content ellipsed" style="font-size: 10px;color: #000;">{!! strip_tags($item['descricao']) !!}</span>
	                        </p>
	                      </a>
	                    <div class="aac-lm-date tag_list" style="padding-left: 40px; text-transform:Capitalize;font-weight:bold;">
								<span class="tag_title">Tags: </span>
								@foreach($item['tags'] as $item)
									<a href="{{ URL::to('/') }}/navtag/{{ $item['id']}}" style="float: left;" >
										<span class="tags shadow">{{ $item['name']}}</span>
									</a>
								@endforeach
						 </div>
						  <a class="btn btn-default fontBlue icon_download" target="__blank" href="{{ URL::to('/') }}/documento/opendoc/{{ $item['url'] }}" role="button"> <span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span></a>

	                    </li>
	                @endforeach

	              </ul>
	            </div>



	            <div class="aac-list-menu">
	              <h4 class="aac-block-title" style="margin-left:0;text-align:left;">Artigos</h4>
	              <ul>
	              @foreach($artrel as $item)
	                    <li>
	                      <a href="{{ URL::to('/') }}/artigo/{{ $item['id'] }}" title="{{ $item['alias'] }}" style="width:87%;">

	                        <p style="margin-left:0;">
	                          <span class="aac-lm-content ellipsed">{{ $item['alias'] }}</span>
	                          <span class="aac-lm-date">{{substr($item['created_at'],0,10) }}</span>

	                        </p>
	                      </a>

	                      <div class="aac-lm-date tag_list" style="text-transform:Capitalize;font-weight:bold;">
								<span class="tag_title">Tags: </span>
								@foreach($item['tags'] as $item)
									<a href="{{ URL::to('/') }}/navtag/{{ $item['id']}}" style="float: left;" >
										<span class="tags shadow">{{ $item['name']}}</span>
									</a>
								@endforeach
						 </div>

	                    </li>
	                @endforeach   {{-- {{ $item['id'] }} {{ $item['descricao'] }}--}}

	              </ul>
	            </div>






			</div>

@endsection
