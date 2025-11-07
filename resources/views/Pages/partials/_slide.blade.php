<section id="aac-topsection" style="position: relative;">

     @if(isset($slides) && count($slides) > 0)
     <div id="aac-main-carousel" class="carousel slide" data-ride="carousel">
      <ol class="carousel-indicators">
        @foreach($slides as $index => $slide)
          <li data-target="#aac-main-carousel" data-slide-to="{{ $index }}" class="{{ $index == 0 ? 'active' : '' }}"></li>
        @endforeach
      </ol>

      <div class="carousel-inner">
        @foreach($slides as $index => $slide)
          @php
            $lan = Session::get('lan') ?? 0;
            // Get image URL
            $imageUrl = '';
            if (isset($slide->imagems) && $slide->imagems) {
              $imageUrl = $slide->imagems->url ?? '';
            } elseif (isset($slide['imagems']) && isset($slide['imagems']['url'])) {
              $imageUrl = $slide['imagems']['url'];
            }
            
            // Get slide title
            $slideTitle = $slide->alias ?? 'Slide';
            if (isset($slide->conteudos) && $slide->conteudos->count() > 0) {
              $slideTitle = $slide->conteudos[$lan]->titulo ?? $slide->conteudos->first()->titulo ?? $slide->alias;
            } elseif (isset($slide['conteudos'][$lan]['titulo'])) {
              $slideTitle = $slide['conteudos'][$lan]['titulo'];
            }
            
            // Get slide text
            $slideText = '';
            if (isset($slide->conteudos) && $slide->conteudos->count() > 0) {
              $slideText = $slide->conteudos[$lan]->texto ?? $slide->conteudos->first()->texto ?? '';
            } elseif (isset($slide['conteudos'][$lan]['texto'])) {
              $slideText = $slide['conteudos'][$lan]['texto'];
            }
            
            // Get slide URL
            $slideUrl = $slide->url ?? $slide['url'] ?? '#';
          @endphp

          <div class="item {{ $index == 0 ? 'active' : '' }}" 
               @if($imageUrl)
               style="background-image: linear-gradient(to right, rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('{{ URL::to('/') }}/files/images/{{ $imageUrl }}');"
               @else
               style="background-color: #333; min-height: 400px;"
               @endif
          >
            <div class="carousel-caption">
              <h1 class="title_slide_home">{{ $slideTitle }}</h1>
            </div>
              
            <div id="aac-main-notificacao" class="container" style="padding-left: 0px;">
              <div class="container row_abs">
                <div class="row">
                  <div class="col-md-9">
                    <p class="white_font">{{ $slideText }}</p>
                  </div>
                  <div class="col-md-3">
                    @if($slideUrl && $slideUrl != '#')
                    <p class="p_btn">
                      <a class="btn btn-lg btn-default" href="{{ URL::to('/') }}{{ $slideUrl }}" role="button">Ler Mais</a>
                    </p>
                    @endif
                  </div>
                </div>
              </div>
            </div>
          </div>
        @endforeach
              

      </div>
    </div>
    @else
    <div class="alert alert-info text-center" style="margin: 20px;">
        <p>Não há slides disponíveis no momento.</p>
    </div>
    @endif

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