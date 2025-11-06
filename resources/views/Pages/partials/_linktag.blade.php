
 <div class="col-sm-6 col-md-6 col-lg-6" style="padding:0;margin-top:30px;">
  <h3 class="aac-block-title" style="margin:5px;margin-top:10px;">Links</h3>  
  <div class="col-sm-12" >
    <div class=" shadow">
        <ol class="aac-list-faqs">
          {{-- {{ print_r($faqs)}} --}}
          @foreach($links as $link)
            <li>
             
              <a href="{{ route('link.click', $link['id']) }}" target="_blank" title="{{ $link['titulo'] }}">
              @if(strlen($link['titulo']) > 50)
              {{substr($link['titulo'],0,50)."..." }}
              @else
           {{ $link['titulo'] }}
              @endif
                
              </a>
            </li>
          @endforeach
      </ol>
    </div>
 </div>
</div>