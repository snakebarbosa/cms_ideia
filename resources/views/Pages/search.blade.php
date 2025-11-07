@extends('Pages.nav')

@section('bcrumbs') 
	  {!! $crumbs !!}

@endsection

@section('content2')
  
			<div class="col-sm-12 col-md-12 col-lg-12" style="padding:0;">  
				
				<h3 class="aac-block-title" style="text-transform:capitalize;margin-left:0;padding-top: 0;margin-top: 0;margin-bottom:20px;" >Resultados 
					<span class="aac-lm-date2" style="text-transform:initial;">Para a pesquisa: <b style="font-size:14px;">{!! $s !!}</b></span>
				</h3>

				@if($artigos->count() == 0 && $documentos->count() == 0 && $faqs->count() == 0)
				<div class="aac-list-menu">
					<p style="padding: 20px;">Nenhum resultado encontrado para "<b>{!! $s !!}</b>".</p>
				</div>
				@else
				<!-- Nav tabs -->
				<ul class="nav nav-tabs" role="tablist" style="margin-bottom: 20px;">
					@if($artigos->count() > 0)
					<li role="presentation" class="active">
						<a href="#artigos" aria-controls="artigos" role="tab" data-toggle="tab">
							Artigos <span class="badge">{{ $artigos->count() }}</span>
						</a>
					</li>
					@endif
					@if($documentos->count() > 0)
					<li role="presentation" class="{{ $artigos->count() == 0 ? 'active' : '' }}">
						<a href="#documentos" aria-controls="documentos" role="tab" data-toggle="tab">
							Documentos <span class="badge">{{ $documentos->count() }}</span>
						</a>
					</li>
					@endif
					@if($faqs->count() > 0)
					<li role="presentation" class="{{ ($artigos->count() == 0 && $documentos->count() == 0) ? 'active' : '' }}">
						<a href="#faqs" aria-controls="faqs" role="tab" data-toggle="tab">
							FAQs <span class="badge">{{ $faqs->count() }}</span>
						</a>
					</li>
					@endif
				</ul>

				<!-- Tab panes -->
				<div class="tab-content">
					@if(isset($artigos) && $artigos->count() > 0)
					<div role="tabpanel" class="tab-pane active" id="artigos">
						<div class="aac-list-menu">
							<ul>
							@foreach($artigos as $item) 
								<li>
									<a href="{{ URL::to('/') }}/artigo/{{ $item['id'] }}" title="{{ $item['alias'] ?? '' }}" style="width:100%;">
										<p>
											<span class="aac-lm-content ellipsed">{{ $item['alias'] }}</span>
											<span class="aac-lm-date">{{substr($item['created_at'],0,10) }}</span>
											@if(isset($item['tags']) && count($item['tags']) > 0)
											<span class="aac-lm-date" style="text-transform:Capitalize;font-weight:bold;">
												<span style="color:#23527c;">Tags: </span>
												@foreach($item['tags'] as $tag)
													{{ $tag['name'].', '}}
												@endforeach
											</span>
											@endif
										</p>
									</a>
								</li>
							@endforeach
							</ul>
						</div>
					</div>
					@endif

					@if(isset($documentos) && $documentos->count() > 0)
					<div role="tabpanel" class="tab-pane {{ (!isset($artigos) || $artigos->count() == 0) ? 'active' : '' }}" id="documentos">
						<div class="aac-list-menu">
							<ul>
							@foreach($documentos as $item) 
								<li>
									<a href="{{ URL::to('/') }}/documento/{{ $item['id'] }}" title="{{ $item['nome'] ?? '' }}" style="width:87%;">
										<img src="{{ URL::to('/') }}/files/images/pdf.png"/>
										<p>
											<span class="aac-lm-content ellipsed">{{ $item['nome'] }}</span>
											<span class="aac-lm-date">{{substr($item['created_at'],0,10) }}</span>
											@if(isset($item['tags']) && count($item['tags']) > 0)
											<span class="aac-lm-date" style="text-transform:Capitalize;font-weight:bold;">
												<span style="color:#23527c;">Tags: </span>
												@foreach($item['tags'] as $tag)
													{{ $tag['name'].', '}}
												@endforeach
											</span>
											@endif
										</p>
									</a>
									<a class="btn btn-default fontBlue icon_download" target="__blank" href="{{ URL::to('/') }}/documento/opendoc/{{ $item['url'] }}" role="button"> <span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span></a>
								</li>
							@endforeach
							</ul>
						</div>
					</div>
					@endif

					@if(isset($faqs) && $faqs->count() > 0)
					<div role="tabpanel" class="tab-pane {{ ((!isset($artigos) || $artigos->count() == 0) && (!isset($documentos) || $documentos->count() == 0)) ? 'active' : '' }}" id="faqs">
						<div class="aac-list-menu">
							<ul>
							@foreach($faqs as $item) 
								<li>
									<a href="{{ URL::to('/') }}/faq/{{ $item['id'] }}" title="{{ $item['alias'] ?? '' }}" style="width:100%;">
										<p>
											<span class="aac-lm-content ellipsed">{{ $item['alias'] }}</span>
											<span class="aac-lm-date">{{substr($item['created_at'],0,10) }}</span>
											@if(isset($item['tags']) && count($item['tags']) > 0)
											<span class="aac-lm-date" style="text-transform:Capitalize;font-weight:bold;">
												<span style="color:#23527c;">Tags: </span>
												@foreach($item['tags'] as $tag)
													{{ $tag['name'].', '}}
												@endforeach
											</span>
											@endif
										</p>
									</a>
								</li>
							@endforeach
							</ul>
						</div>
					</div>
					@endif
				</div>
				@endif
	           
			</div>
	  
@endsection 
 