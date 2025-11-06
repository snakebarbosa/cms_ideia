<div class="col-sm-6 col-md-6 col-lg-6" style="padding:0;margin-top:30px;">
  <h3 class="aac-block-title" style="margin:5px;margin-top:10px;">FAQ's</h3>
  <div class="col-sm-12" >
    <div class=" shadow">
        <ol class="aac-list-faqs">
          @foreach($faqs as $faq)
            <li>
              <img src="{{URL::to('/')}}/files/images/faq_icon.png"/>
              <a href="{{ URL::to('/') }}/faq/{{ $faq['id'] }}" title="{{ $faq['alias'] }}">
              @if(strlen($faq['alias']) > 50)
                 {{substr($faq['alias'],0,50)."..." }}
              @else
                 {{ $faq['alias'] }}
              @endif

              </a>
               <span class="aac-lm-category"> <a style="padding: 0;margin: 0;padding-left: 39px;" href="{{ URL::to('/') }}/navfaq/{{$faq['categorias']['id']}}">{{ $faq['categorias']['titulo'] }}</a></span>
            </li>
          @endforeach
      </ol>
    </div>
 </div>
</div>
