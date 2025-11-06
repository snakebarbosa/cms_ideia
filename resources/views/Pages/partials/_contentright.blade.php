<div class="col-sm-9 col-md-9 col-lg-9 ">

          <div id="aac-articles-slide" class="carousel slide shadow" data-ride="carousel">
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

              {{--  --}}

             @php
                $i = 0;
            @endphp
            @foreach($news as $item)
               @if($i == 0)

                    <div class="item active" style="background-image:url('{{URL::to('/') }}/files/images/'{{$item['imagems']['url']}}')">

                  @php
                       $i = 1;
                  @endphp
                @else
                     <div class="item" style="background-image:url('{{URL::to('/') }}/files/images/'{{$item['imagems']['url']}}')">
                @endif

                <div class="aac-art-slide-content col-sm-4">
                  <h3><a href="{{ URL::to('/') }}/artigo/{{ $item['id'] }}">{{ $item['conteudos'][Session::get('lan')]['titulo']  }}</a></h3>
                  <div class="aac-art-date">{{substr($item['created_at'],0,10) }}</div>

               </div>
              </div>
            @endforeach

              {{--  --}}

            </div>

          </div>

        </div>