<div class="col-sm-3 col-md-3 col-lg-3 p0">
  <h3 class="aac-block-title">FAQ's</h3>
  <div class="col-sm-12">
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