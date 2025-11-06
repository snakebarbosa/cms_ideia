{{--     <div id="aac-articles-slide" class="carousel slide shadow" data-ride="carousel">
            <ol class="carousel-indicators col-sm-4">

                 @php  $i = 0;  @endphp
                  @foreach($news as $item)
                       @if($i == 0)
                          <li data-target="#aac-articles-slide" data-slide-to="@php echo $i; @endphp" class="active"></li>

                         @else
                          <li data-target="#aac-articles-slide" data-slide-to="@php echo $i; @endphp" class=""></li>
                      @endif
                       @php  $i++;  @endphp
                  @endforeach
            </ol>
            <div class="carousel-inner">




             @php
                $i = 0;
            @endphp
            @foreach($news as $item)
               @if($i == 0)

                    <div class="item active" style="background-image:url('{{ URL::to('/') }}/{{ 'images/'.$item['imagems']['url'] }}')">

                  @php
                       $i = 1;
                  @endphp
                @else
                     <div class="item" style="background-image:url('{{ URL::to('/') }}/{{ 'images/'.$item['imagems']['url'] }}')">
                @endif

                <div class="aac-art-slide-content col-sm-4">
                  <h3><a href="{{ URL::to('/') }}/artigo/{{ $item['id'] }}">{{ $item['alias'] }}</a></h3>
                  <div class="aac-art-date">{{substr($item['created_at'],0,10) }}</div>

               </div>
              </div>
            @endforeach

            </div>

          </div> --}}

         {{--  <div class="container">
<div class="col-xs-12"> --}}

    <div class="page-header">
        <h3 class="aac-block-title" style="margin:5px;">Artigos Relacionados</h3>
    </div>

    <div class="carousel slide" id="myCarousel">
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


                @foreach($news as $item)

                  @if($j < 4)

                        <li class="col-sm-3">
                          <div class="fff shadow">
                            <div class="thumbnail">
                              <a href="{{ URL::to('/') }}/artigo/{{ $item['id'] }}"><img src="{{ URL::to('/') }}/{{ 'files/images/'.$item['imagems']['url'] }}" alt=""></a>
                            </div>
                            <div class="caption">
                              <h5 title="{{ $item['alias'] }}">{{ $item['alias'] }}</h5>
                              <p>{{substr($item['created_at'],0,10) }}</p>
                              <a class="btn btn-mini shadow" href="{{ URL::to('/') }}/artigo/{{ $item['id'] }}">+</a>
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
                         <li class="col-sm-3">
                            <div class="fff shadow">
                              <div class="thumbnail">
                                <a href="{{ URL::to('/') }}/artigo/{{ $item['id'] }}"><img src="{{ URL::to('/') }}/{{ 'files/images/'.$item['imagems']['url'] }}" alt=""></a>
                              </div>
                              <div class="caption">
                                <h5 title="{{ $item['alias'] }}">{{ $item['alias'] }}</h5>
                                <p>{{substr($item['created_at'],0,10) }}</p>
                                <a class="btn btn-mini shadow" href="{{ URL::to('/') }}/artigo/{{ $item['id'] }}">+</a>
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
        <li><a data-slide="prev" href="#myCarousel" class=""><i class="glyphicon glyphicon-chevron-left"></i></a></li>
        <li><a data-slide="next" href="#myCarousel" class=""><i class="glyphicon glyphicon-chevron-right"></i></a></li>
      </ul>
    </nav>
     <!-- /.control-box -->

    </div><!-- /#myCarousel -->

<!-- </div>/.col-xs-12 -->

<!--</div> /.container -->