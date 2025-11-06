@if($docs->count() != 0)

    <div class="col-md-12 aac-list-menu " >
      <h4 >Doc. Destaques</h4>
      <ul class="shadow">
        @foreach($docs as $item)
            <li>
               <a href="{{ URL::to('/') }}/documento/{{ $item['id'] }}" title="{{ $item['descricao'] }}">
                        <img src="{{ URL::to('/') }}/files/images/{{$item['tipos']['titulo'] }}.png"/>
                      </a>
                        <p>
                          <span class="aac-lm-content ellipsed" >
                              <a style="padding: 0;margin: 0;" href="{{ URL::to('/') }}/documento/{{ $item['id'] }}" title="{!! $item['alias'] !!}">{{ $item['nome'] }}</a>
                          </span>
                          <span class="aac-lm-date">{{substr($item['created_at'],0,10) }}</span>
                          <span class="aac-lm-category"> <a style="padding: 0;margin: 0;" href="{{ URL::to('/') }}/navdoc/{{$item['categorias']['id']}}">{{ $item['categorias']['titulo'] }}</a></span>
                        </p>
                      </a>
                    </li>
        @endforeach

      </ul>
    </div>

    <div class="col-md-12 aac-list-menu" >
      <h4>Doc. +Recentes</h4>
      <ul class="shadow">
        @foreach($dlast as $item)
            <li>
              <a href="{{ URL::to('/') }}/documento/{{ $item['id'] }}" title="{{ $item['descricao'] }}">
                        <img src="{{ URL::to('/') }}/files/images/{{$item['tipos']['titulo'] }}.png"/>
                      </a>
                        <p>
                          <span class="aac-lm-content ellipsed" >
                              <a style="padding: 0;margin: 0;" href="{{ URL::to('/') }}/documento/{{ $item['id'] }}" title="{!! $item['alias'] !!}">{{ $item['nome'] }}</a>
                          </span>
                          <span class="aac-lm-date">{{substr($item['created_at'],0,10) }}</span>
                          <span class="aac-lm-category"> <a style="padding: 0;margin: 0;" href="{{ URL::to('/') }}/navdoc/{{$item['categorias']['id']}}">{{ $item['categorias']['titulo'] }}</a></span>
                        </p>
                      </a>
                    </li>
        @endforeach
      </ul>
  </div>
 @endif