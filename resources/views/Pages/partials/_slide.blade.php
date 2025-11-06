<section id="aac-topsection" style="position: relative;">

     <div id="aac-main-carousel" class="carousel slide" data-ride="carousel">
      <ol class="carousel-indicators">

        @php  $i = 0;  @endphp
        @foreach($slides as $slide)
             @if($i == 0)
                <li data-target="#aac-main-carousel" data-slide-to="@php echo $i; @endphp" class="active"></li>

              @else
                <li data-target="#aac-main-carousel" data-slide-to="@php echo $i; @endphp"></li>
              @endif
             @php  $i++;  @endphp
        @endforeach

      </ol>

      <div class="carousel-inner">
         @php
                $i = 0;
            @endphp
        @foreach($slides as $slide)

            @if($i == 0)
               <div class="item active" style="background-image: linear-gradient(to right, rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('{{URL::to('/') }}/files/images/{{$slide['imagems']['url']}}');">
              @php
                   $i = 1;
              @endphp
            @else
               <div class="item" style="background-image: linear-gradient(to right, rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)),  url('{{URL::to('/') }}/files/images/{{$slide['imagems']['url']}}');">
            @endif
               
              <div class="carousel-caption">
                <h1 class="title_slide_home">{{ $slide['conteudos'][Session::get('lan') ?? 0]['titulo'] ?? $slide['alias']}}</h1>
              </div>
                
              <div id="aac-main-notificacao" class="container" style="padding-left: 0px;">
                 <div class="container row_abs">
                    <div class="row">
                       <div class="col-md-9">
                          <p class="white_font">{{ $slide['conteudos'][Session::get('lan') ?? 0]['texto']}}</p>
                        </div>
                          <div class="col-md-3">
                            <p class="p_btn"><a class="btn btn-lg btn-default" href="{{ URL::to('/') }}{{ $slide['url']}}" role="button">Ler Mais</a></p>
                        </div>
                    </div>
                </div>
            </div>
          </div>
        @endforeach
              

      </div>
    </div>

    <!-- <div id="aac-main-notificacao" class="container" style="padding-left: 0px;">
         <div class="container row_abs">
            <div class="row">
               <div class="col-md-9">
                          <h4>Notificação de Acidentes e Incidentes</h4>
                          <p class="white_font">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                        </div>
                         <div class="col-md-3">
                            <p class="p_btn"><a class="btn btn-lg btn-default" href="#" role="button">Ler Mais</a></p>
                        </div>
            </div>
            
        </div>
    </div> -->

  </section>