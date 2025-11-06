@extends('Pages.nav')

@section('keywords')

	<meta name="keywords" content="{{ $faq->keyword }} "/>
@endsection

@section('bcrumbs')
	  {!! $crumbs !!}

@endsection
@section('content2')

			<div class="col-sm-12 col-md-12 col-lg-12" style="padding:0;">
				<h3 class="aac-block-title" style="text-transform:capitalize;margin-left:0;padding-top: 0;margin-top: 0;" >{{ $faq->alias }}
					<span class="aac-lm-date2">{{ substr($faq->created_at,0,10) }}</span>
				</h3>
				<div class="col-sm-12 col-md-12 col-lg-12">
					<h4 class="aac-block-title" style="border:none;"></h4>
					{!! $faq->conteudos[0]->texto !!}
				</div>
				<div class="col-sm-12 col-md-12 col-lg-12" style="margin-top:25px;">
					<span style="color:#23527c;">Tags: </span>
						@foreach($faq->tags as $item)
							<a href="{{ URL::to('/') }}/navtag/{{ $item['id']}}"><span class="tags shadow">{{ $item['name']}}</span></a>
						@endforeach
				</div>
				<div class="col-sm-12 col-md-12 col-lg-12" style="margin-top:25px;">
					<h4 style="text-transform:capitalize;margin-left:0;padding-top: 0;margin-top: 0;" >Partilhar</h3>
					 @include("Pages.partials._share",['url' => "{{ URL::to('/') }}/artigo/".$faq->id ])
		       </div>


		        <div class="aac-list-menu">
	              <h4 class="aac-block-title" style="margin-left:0;text-align:left;">FAQs Relacionados</h4>

	                   <ol class="aac-list-faqs">
				          @foreach($faqrel as $item)
				            <li>
				              <div class="col-sm-1" style="width: 35px;">
				              	<img src="{{ URL::to('/') }}/files/images/faq_icon.png" />
				              </div>
				              <a href="{{ URL::to('/') }}/faq/{{ $item['id'] }}" title="{{ $item['alias'] }}">
				              @if(strlen($item['alias']) > 50)
					              {{substr($item['alias'],0,50)."..." }}
					           @else
					          	 {{ $item['alias'] }}
				              @endif

				              </a>
				            </li>
				          @endforeach
				      </ol>

	            </div>
			</div>
@endsection
