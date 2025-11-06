@extends('Pages.nav')

@section('keywords') 
   
  <meta name="keywords" content="Organização e Estrutura, AAC, Agência de Aviação Civil, Cabo Verde"/>
@endsection

@section('bcrumbs') 
    {!! $crumbs !!}

@endsection
@section('content2')

      <div class="col-sm-12 col-md-12 col-lg-12" style="padding:0;"> 
        <h3 class="aac-block-title" style="text-transform:capitalize;margin-left:0;padding-top: 0;margin-top: 0;" >Organização e Estrutura
        
        </h3>
            <div class="organigrama">
              <ul>
                  <li>
                    <a href="{{  URL::to('/') }}/artigo/5" class="root">Agência de Aviação Civil</a>
                      <ul>
                          <li><a href="{{  URL::to('/') }}/artigo/5">Conselho de<br> Consultivo</a></li>
                          <li><a href="{{  URL::to('/') }}/artigo/5">Conselho de<br> Administração</a>
                              <ul>
                                  <li><a href="{{  URL::to('/') }}/artigo/5">Secretariado<br>Executivo</a></li>
                                   <li><a href="{{  URL::to('/') }}/artigo/5">Pelouro</a>
                                      <ul>
                                        {{-- pelouro2 --}}
                                        <li ><a href="{{  URL::to('/') }}/artigo/5">Admin<br>Executivo</a>
                                          <ul>
                                              <ul class="verticalUl">
                                                <li class="verticalLi"><a href="{{  URL::to('/') }}/artigo/5">Operações</a></li>
                                                <li class="verticalLi"><a href="{{  URL::to('/') }}/artigo/5">Aeronavegabilidade</a></li>
                                                <li class="verticalLi"><a href="{{  URL::to('/') }}/artigo/5">Licenciamento</a></li>
                                                <li class="verticalLi"><a href="{{  URL::to('/') }}/artigo/5">Medicina<br>Aeronaútica</a></li>
                                              </ul>
                                          </ul>
                                        </li>
                                        {{-- .pelouro2 --}}
                                        {{-- pelouro1 --}}
                                        <li><a href="{{  URL::to('/') }}/artigo/5">Admin.<br>Executivo</a>

                                                <ul>
                                                  <ul class="verticalUl">
                                                    <li class="verticalLi"><a href="{{  URL::to('/') }}/artigo/5">Jurídico</a></li>
                                                    <li class="verticalLi"><a href="{{  URL::to('/') }}/artigo/5">Auditoria,<br>Qual e SSP</a></li>
                                                    <li class="verticalLi"><a href="{{  URL::to('/') }}/artigo/5">RH</a></li>
                                                    <li class="verticalLi"><a href="{{  URL::to('/') }}/artigo/5">Navegação<br>Áerea</a></li>
                                                    <li class="verticalLi"><a href="{{  URL::to('/') }}/artigo/5">Aeródromos</a></li>
                                                    <li class="verticalLi"><a href="{{  URL::to('/') }}/artigo/5">Seg e FAL</a></li>
                                                  </ul>
                                                </ul>
                                        </li>
                                        {{-- .pelouro1 --}}
                                          {{-- pelouro3 --}}
                                        <li><a href="{{  URL::to('/') }}/artigo/5">Admin.<br> Executivo</a>
                                          <ul>
                                              <ul class="verticalUl">
                                                <li class="verticalLi"><a href="{{  URL::to('/') }}/artigo/5">Regulação<br>Económica</a></li>
                                                <li class="verticalLi"><a href="{{  URL::to('/') }}/artigo/5">Admin.<br>Financeira</a></li>
                                                <li class="verticalLi"><a href="{{  URL::to('/') }}/artigo/5">Sis. Inform e<br> Comunicação</a></li>
                                                <li class="verticalLi"><a href="{{  URL::to('/') }}/artigo/5">Planeamento e<br>Monitorização</a></li>
                                              </ul>
                                          </ul>
                                        </li>
                                         
                                      </ul>
                                   </li>
                                   <li><a href="{{  URL::to('/') }}/artigo/5">Gab.<br> Assessoria</a></li>
                              </ul>
                          </li>
                           <li><a href="{{  URL::to('/') }}/artigo/5">Fiscal<br> Único</a></li>
                          
                      </ul>
                  </li>
              </ul>
          </div>
      </div>
      <div class="col-sm-12 col-md-12 col-lg-12" style="padding:0;"> 
       <div class="aac-list-menu">
                <h4 class="aac-block-title" style="margin-left:0;text-align:left;">Documentos Relacionados</h4>
                <ul>
               
                      <li>
                        <a href="{{ URL::to('/') }}/documento/41" title="Manual de Organização" style="width:87%;">
                          <img src="{{ URL::to('/') }}/files/images/pdf.png"/>
                          <p>
                            <span class="aac-lm-content ellipsed">Manual de Organização da AAC, Edição nº 5</span>
                           
                          </p>
                        </a>
                           <a class="btn btn-default fontBlue icon_download" target="__blank" href="{{ URL::to('/') }}/documento/opendoc/1499456674.pdf" role="button"> <span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span></a>

                      </li>
                
                    
                </ul>
              </div>
        </div>
@endsection 
 