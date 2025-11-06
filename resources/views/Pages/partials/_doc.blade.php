<div class="col-sm-9 col-md-9 col-lg-9 p0">
          <h3 class="aac-block-title">Documentos</h3>

          <div class="col-sm-4">
            <div class="aac-list-menu shadow">

              <h4>@if(isset($drandom[0]['categorias']['titulo'])) {{ $drandom[0]['categorias']['titulo'] }} @endif</h4>
              <ul>

                @forelse($drandom as $item)
                    <li>
                      <a href="{{ URL::to('/') }}/documento/{{ $item['id'] }}" title="{{ $item['descricao'] }}">
                        <img src="{{ URL::to('/') }}/files/images/{{$item['tipo']}}.png"/>
                      </a>
                        <p>
                          <span class="aac-lm-content ellipsed" >
                              <a style="padding: 0;margin: 0;" href="{{ URL::to('/') }}/documento/{{ $item['id'] }}" title="{!! $item['alias'] !!}">{{ $item['conteudos'][Session::get('lan')]['titulo']}}</a>
                          </span>
                          <span class="aac-lm-date">{{substr($item['created_at'],0,10) }}</span>
                          <span class="aac-lm-category"> <a style="padding: 0;margin: 0;" href="{{ URL::to('/') }}/navdoc/{{$item['categorias']['id']}}">{{ $item['categorias']['titulo'] }}</a></span>
                        </p>
                      </a>
                    </li>
                 @empty
                @endforelse
              </ul>
            </div>
          </div>
          

          <div class="col-sm-4 ">

            <div class="aac-list-menu shadow">
              <h4>+Recentes</h4>
              <ul>
                @forelse($dlast as $item)
                    <li>
                      <a href="{{ URL::to('/') }}/documento/{{ $item['id'] }}" title="{{ $item['descricao'] }}">
                        <img src="{{ URL::to('/') }}/files/images/{{$item['tipo'] }}.png"/>
                      </a>
                        <p>
                          <span class="aac-lm-content ellipsed" >
                              <a style="padding: 0;margin: 0;" href="{{ URL::to('/') }}/documento/{{ $item['id'] }}" title="{!! $item['alias'] !!}">{{ $item['conteudos'][Session::get('lan')]['titulo'] }}</a>
                          </span>
                          <span class="aac-lm-date">{{substr($item['created_at'],0,10) }}</span>
                          <span class="aac-lm-category"> <a style="padding: 0;margin: 0;" href="{{ URL::to('/') }}/navdoc/{{$item['categorias']['id']}}">{{ $item['categorias']['titulo'] }}</a></span>
                        </p>
                      </a>
                    </li>
                 @empty
                @endforelse
              </ul>
            </div>
          </div>
           

          <div class="col-sm-4 ">
            <div class="aac-list-menu shadow">
              <h4>Destaques</h4>
              <ul>

                 @forelse($docs as $item)
                    <li>
                      <a href="{{ URL::to('/') }}/documento/{{ $item['id'] }}" title="{{ $item['descricao'] }}">
                        <img src="{{ URL::to('/') }}/files/images/{{$item['tipo'] }}.png"/>
                      </a>
                        <p>
                          <span class="aac-lm-content ellipsed" >
                              <a style="padding: 0;margin: 0;" href="{{ URL::to('/') }}/documento/{{ $item['id'] }}" title="{!! $item['alias'] !!}">{{ $item['conteudos'][Session::get('lan')]['titulo'] }}</a>
                          </span>
                          <span class="aac-lm-date">{{substr($item['created_at'],0,10) }}</span>
                          <span class="aac-lm-category"> <a style="padding: 0;margin: 0;" href="{{ URL::to('/') }}/navdoc/{{$item['categorias']['id']}}">{{ $item['categorias']['titulo'] }}</a></span>
                        </p>
                      </a>
                    </li>
                  @empty
                @endforelse

              </ul>
            </div>
          </div>
        </div>