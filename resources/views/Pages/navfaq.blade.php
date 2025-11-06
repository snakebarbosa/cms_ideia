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
	                      <a href="{{ URL::to('/') }}/navfaq/{{ $item['id'] }}" title="{{ $item['titulo'] }}">
	                        <i class="glyphicon glyphicon-folder-open fontBlue folder_position"></i>
	                        <span class="aac-lm-content ellipsed fontBlue folder_text">{{ $item['titulo'] }}</span>
	                         
	                      </a>
	                    </li>
	                @endforeach
	              </ul>
	            </div>


	            <div class="aac-list-menu">
	              <h4 class="aac-block-title" style="margin-left:0;text-align:left;">Faqs</h4>
	              <ul>
	               <ol class="aac-list-faqs">
				          @foreach($faqs as $item)
				            <li>
				              <div class="col-sm-1" style="width:35px;">
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
	                	
	              </ul>
	            </div>
			</div>
	  
@endsection 