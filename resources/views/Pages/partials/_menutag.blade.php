<div class="page-header">
  <h3 class="aac-block-title" style="margin:5px;">Documentos</h3>
</div>
<div class="carousel slide" id="myCarousel2">
  <div class="carousel-inner">
    @php $i = 0;
    $j = 0;
    @endphp
    {{-- @if($i == 0) --}}
    <div class="item active">
      <ul class="thumbnails">
        {{--   @php $i = 1; @endphp
        @else
        <div class="item">
          <ul class="thumbnails">
            @endif   --}}
            @foreach($pastas as $item)
            @if($j < 4)
            <li class="col-sm-3 ">
              <div class="fff shadow">
                <div class="thumbnail">
                  <a href="{{ URL::to('/') }}/navdoc/{{ $item['id'] }}"><img src="{{ URL::to('/') }}/files/images/folder_1.png" alt=""></a>
                </div>
                <div class="caption">
                  <h5 title="{{ $item['titulo'] }}">{{ $item['titulo'] }}</h5>
                  <a class="btn btn-mini shadow" href="{{ URL::to('/') }}/navdoc/{{ $item['id'] }}">+</a>
                </div>
              </div>
            </li>
            @endif
            @if($j > 3)
          </ul>
        </div>
        @php $j=0; @endphp
        <div class="item">
          <ul class="thumbnails">
            <li class="col-sm-3 ">
              <div class="fff shadow">
                <div class="thumbnail">
                  <a href="{{ URL::to('/') }}/navdoc/{{ $item['id'] }}"><img src="{{ URL::to('/') }}/files/images/folder_1.png" alt=""></a>
                </div>
                <div class="caption">
                  <h5 title="{{ $item['titulo'] }}">{{ $item['titulo'] }}</h5>
                  <a class="btn btn-mini shadow" href="{{ URL::to('/') }}/navdoc/{{ $item['id'] }}">+</a>
                </div>
              </div>
            </li>
            @endif
            @php $j++; @endphp
            @endforeach

          </ul>
        </div><!-- /Slide1 -->
  </div>

        <nav>
          <ul class="control-box pager">
            <li><a data-slide="prev" href="#myCarousel2" class=""><i class="glyphicon glyphicon-chevron-left"></i></a></li>
            <li><a data-slide="next" href="#myCarousel2" class=""><i class="glyphicon glyphicon-chevron-right"></i></a></li>
          </ul>
        </nav>
        <!-- /.control-box -->
</div><!-- /#myCarousel -->


    <div class="aac-list-menu">
          <div class="page-header">
            <h3 class="aac-block-title" style="margin:5px;">Documentos Recentes</h3>
          </div>
          <ul>
            @foreach($recentestag as $item)
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