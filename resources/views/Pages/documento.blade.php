@extends('Pages.nav')
@section('bcrumbs')
{!! $crumbs !!}
@endsection
@section('content2')
<div class="col-sm-12 col-md-12 col-lg-12" style="padding:0;">
	@php
		$lan = Session::get('lan') ?? 0;
	@endphp
	<h4 class="aac-block-title" style="margin-left:0;text-transform:capitalize;">{{ $doc['conteudos'][$lan]['titulo'] ?? $doc['nome'] }}</h4>
	<table class="table" style="border-bottom: 1px solid #dcdcdc;">
		<tr>
			<td class="borderRight">Data</td>
			<td class="borderNone">{{ $doc['created_at'] }}</td>
		</tr>
		<tr>
			<td class="borderRight">Descrição</td>
			<td class="borderNone">{!! strip_tags($doc['conteudos'][$lan]['texto'] ?? $doc['descricao'] ?? '') !!}</td>
		</tr>
		<tr>
			<td class="borderRight">Formato</td>
			<td class="borderNone">PDF</td>
		</tr>
		<tr>
			<td class="borderRight">Pasta</td>
			<td class="borderNone"> <a href="{{ URL::to('/') }}/navdoc/{{$doc['categorias']['id']}}" title="{{ $doc['categorias']['titulo'] }} }}">{{ $doc['categorias']['titulo'] }}</a></td>
		</tr>
		<tr>
			<td class="borderRight">Tamanho</td>
			<td class="borderNone">{{ $doc['size']}}</td>
		</tr>
		<tr>
			<td class="borderRight">Acessos</td>
			<td class="borderNone">{{ Count($doc['contador']) + Count($doc['contadores']) }}</td>
		</tr>
		<tr>
			<td class="borderNone"></td>
			<td class="borderNone">
				{{-- <a class="btn btn-default fontBlue" href="#" role="button"> <span class="glyphicon glyphicon-star" aria-hidden="true"></span></a> --}}
				<a class="btn btn-default fontBlue" target="_blank" href="{{ URL::to('/') }}/documento/opendoc/{{ $doc['url'] }}" role="button"> <span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span></a>
				{{-- <a class="btn btn-default fontBlue" href="#" role="button"> <span class="glyphicon glyphicon-print" aria-hidden="true"></span></a> --}}
			</td>
		</tr>
	</table>
	@if(isset($docrel) && count($docrel)>0)
		<div class="aac-list-menu">
			<h4 class="aac-block-title" style="margin-left:0;text-align:left;">@if(Session::get('lan')==0) Documentos Relacionados @else Related Documents  @endif</h4>
			<ul>
				@foreach($docrel as $item)
					@if($item['id']!= $doc['id'] && $item['ativado']== 1)
						@php
							$lan = Session::get('lan') ?? 0;
						@endphp
						<li>
							<a href="{{ URL::to('/') }}/documento/{{ $item['id'] }}" title="{{ $item['nome'] }}" style="width:87%;">
								@if(isset($item['tipos']['titulo']))
									<img src="{{ URL::to('/') }}/files/images/{{ $item['tipos']['titulo'] }}.png"/>
								@endif
								<p>
									<span class="aac-lm-content ellipsed">{{ $item['conteudos'][$lan]['titulo'] ?? $item['nome'] }}</span>
									<span class="aac-lm-date">{{substr($item['created_at'],0,10) }}</span>

								</p>
							</a>
							@if(isset($item['tags']) && count($item['tags']) > 0)
							 <div class="aac-lm-date tag_list" style="padding-left: 40px; text-transform:Capitalize;font-weight:bold;">
											<span class="tag_title">Tags: </span>
											@foreach($item['tags'] as $t)
												<a href="{{ URL::to('/') }}/navtag/{{ $t['id']}}" style="float: left;" >
													<span class="tags shadow">{{ $t['name']}}</span>
												</a>
											@endforeach
									 </div>
							@endif
							<a class="btn btn-default fontBlue icon_download" target="__blank" href="{{ URL::to('/') }}/documento/opendoc/{{ $item['url'] }}" role="button"> <span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span></a>
						</li>
						@endif
				@endforeach   
			</ul>
		</div>
	@endif
</div>
@endsection