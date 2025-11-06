<section>
  <div class="container top50 border_">
    <div class="row">
      <div class="col-sm-6 col-md-6 col-lg-6 col-xs-12 tab_container_doc">
          <div class="col-12 bottom50">
            <h1 class="fontBold tituloDoc">@if(Session::get('lan')==0) DOCUMENTOS @else DOCUMENTS  @endif</h1>
          </div>

          <ul class="nav nav-tabs tab_intro_doc">
            @if(isset($tab2pos1[0])) <li role="presentation" class="active"><a style="font-size:13px;" data-toggle="tab" href="#home2">{{ $tab2pos1[0]->categorias->titulo }}</a></li>@endif
            @if(isset($tab2pos2[0]))<li role="presentation" ><a style="font-size:13px;" data-toggle="tab" href="#menu12">{{ $tab2pos2[0]->categorias->titulo }}</a></li>@endif
            @if(isset($tab2pos3[0]))<li role="presentation"><a style="font-size:13px;" data-toggle="tab" href="#menu3">{{ $tab2pos3[0]->categorias->titulo }}</a></li>@endif
            @if(isset($tab2pos5[0]))<li role="presentation"><a style="font-size:13px;" data-toggle="tab" href="#menu2_5">{{ $tab2pos5[0]->categorias->titulo ?? 'NEWSLETTER'}}</a></li>@endif
             {{--  <li role="presentation"><a style="font-size:13px;" data-toggle="tab" href="#menu24">@if(isset($tab2pos4[0])){{ $tab2pos4[0]->categorias->titulo }}@endif</a></li> --}}
          </ul>
          <div class="tab-content">
            <div id="home2" class="tab-pane fade in active">
               {{-- <!-- <h3 class="fontBold">{{ $tab2pos1[0]->categorias->titulo }}</h3> --> --}}
              @forelse($tab2pos1 as $item)
                <div class="media">
                  <div class="media-left">
                    <a href="{{ URL::to('/') }}/documento/{{ $item['id'] }}" target="_blank">
                    <img class="media-object" src="{{URL::to('/')}}/files/images/pdf_icon.png" alt="...">
                    </a>
                  </div>
                  <div class="media-body">
                    <small class="data_doc">{{substr($item['data_criacao'],0,10) }}</small>
                    <a href="{{ URL::to('/') }}/documento/{{ $item['id'] }}" target="_blank"><h4 class="media-heading" style="color:#303030!important;">{{ $item['conteudos'][Session::get('lan')]['titulo'] }}</h4></a>
                    <p class="font12">{{substr($item['conteudos'][Session::get('lan')]['descricao'] ,0,50) }}</p>
                  </div>
                </div>
              @empty
              @endforelse  
            </div>
            <div id="menu12" class="tab-pane fade">
              {{-- <!-- <h3 class="fontBold">{{ $tab2pos2[0]->categorias->titulo }}</h3> --> --}}
                @forelse($tab2pos2 as $item)
                  <div class="media">
                    <div class="media-left">
                      <a href="{{ URL::to('/') }}/documento/{{ $item['id'] }}" target="_blank">
                        <img class="media-object" src="{{URL::to('/')}}/files/images/pdf_icon.png" alt="...">
                      </a>
                    </div>
                    <div class="media-body">
                      <small class="data_doc">{{substr($item['data_criacao'],0,10) }}</small>
                      <a href="{{ URL::to('/') }}/documento/{{ $item['id'] }}" target="_blank"><h4 class="media-heading" style="color:#303030!important;">{{ $item['conteudos'][Session::get('lan')]['titulo'] }}</h4></a>
                      <p class="font12">{{substr($item['conteudos'][Session::get('lan')]['descricao'] ,0,50) }}</p>
                    </div>
                  </div>
                @empty
                @endforelse  
            </div>
            <div id="menu3" class="tab-pane fade">
              {{-- <!-- <h3 class="fontBold">{{ $tab2pos3[0]->categorias->titulo }}</h3> --> --}}
              @forelse($tab2pos3 as $item)
                  <div class="media">
                    <div class="media-left">
                      <a href="{{ URL::to('/') }}/documento/{{ $item['id'] }}" target="_blank">
                        <img class="media-object" src="{{URL::to('/')}}/files/images/pdf_icon.png" alt="...">
                      </a>
                    </div>
                    <div class="media-body">
                      <small class="data_doc">{{substr($item['data_criacao'],0,10) }}</small>
                      <a href="{{ URL::to('/') }}/documento/{{ $item['id'] }}" target="_blank"><h4 class="media-heading" style="color:#303030!important;">{{ $item['conteudos'][Session::get('lan')]['titulo'] }}</h4></a>
                      <p class="font12">{{substr($item['conteudos'][Session::get('lan')]['descricao'] ,0,50) }}</p>
                    </div>
                  </div>
              @empty
              @endforelse  
            </div>
            
            <div id="menu2_5" class="tab-pane fade">
              @forelse($tab2pos5 as $item)
                  <div class="media">
                    <div class="media-left">
                      <a href="{{ URL::to('/') }}/documento/{{ $item['id'] }}" target="_blank">
                        <img class="media-object" src="{{URL::to('/')}}/files/images/pdf_icon.png" alt="...">
                      </a>
                    </div>
                    <div class="media-body">
                      <small class="data_doc">{{substr($item['data_criacao'],0,10) }}</small>
                      <a href="{{ URL::to('/') }}/documento/{{ $item['id'] }}" target="_blank"><h4 class="media-heading" style="color:#303030!important;">{{ $item['conteudos'][Session::get('lan')]['titulo'] }}</h4></a>
                      <p class="font12">{{substr($item['conteudos'][Session::get('lan')]['descricao'] ,0,50) }}</p>
                    </div>
                  </div>
              @empty
              @endforelse  
            </div>
            
             {{-- <div id="menu24" class="tab-pane fade">
              <!-- <h3 class="fontBold">{{ $tab2pos3[0]->categorias->titulo }}</h3> -->
                 @forelse($tab2pos4 as $item)
                          <div class="media">
                            <div class="media-body">
                              <small class="data_doc">{{substr($item['data_criacao'],0,10) }}</small>
                              <a href="{{ URL::to('/') }}/artigo/{{ $item['id'] }}" target="_blank"><h4 class="media-heading" style="color:#303030!important;">{{ $item['conteudos'][Session::get('lan')]['titulo'] }}</h4></a>
                              <p class="font12">{{substr($item['conteudos'][Session::get('lan')]['descricao'] ,0,50) }}</p>
                              
                            </div>
                          </div>
                         @empty
                         @endforelse  
            </div> --}}
          </div>

        </div>
    <!-- ##### end tab -->
         <div class="col-sm-6 col-md-6 col-lg-6 col-xs-12 recents_news_tab">
              <div class="col-12 bottom50">
                <h1 class="fontBold tituloDoc">@if(Session::get('lan')==0) RECENTES @else LATEST  @endif</h1> 
              </div>

            
              <div class="col-md-12">

                @forelse($dlast as $item)
                  <div class="media">
                    <div class="media-left">
                      <a href="{{ URL::to('/') }}/documento/{{ $item['id'] }}" target="_blank">
                        <img class="media-object" src="{{URL::to('/')}}/files/images/pdf_icon.png" alt="...">
                      </a>
                    </div>
                    <div class="media-body">
                      <small class="data_doc">{{substr($item['data_criacao'],0,10) }}</small>
                      <a href="{{ URL::to('/') }}/documento/{{ $item['id'] }}" target="_blank"><h4 class="media-heading" style="color:#303030!important;">{{ $item['conteudos'][Session::get('lan')]['titulo'] }}</h4></a>
                      <p class="font12">{{substr($item['conteudos'][Session::get('lan')]['descricao'] ,0,50) }}</p>
                      
                    </div>
                  </div>
                  @empty
                  @endforelse  
              </div>
                   
        </div>
      </div>

    </div>

    <div id="margin_parceiros"></div>
  </section>