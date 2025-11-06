<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">

    <head>
      @include("Administrator/partials._head")
       
      @yield('title')
    </head>
    <body>
         
    <!-- <div class="flex-center position-ref full-height"> -->
    {{ phpinfo() }}
           @include("Administrator/partials._navmenu")
                    
                 <div id="container_home" style="">
                            
                      
                          
                                <div class="container">
                                      <div>
                                            <button id="exportar">Exportar</button>
                                            <select id="export_combo">
                                                <option value="markers_export">Marker</option>
                                                <option value="tipo_markers_export">Marker Types</option>
                                                <option value="ilhas_export">Islands</option>
                                                <option value="cidades_export">Cities</option>
                                                <option value="farmacias_export">Farmacia</option>
                                                <option value="produto_export">Produtos</option>
                                                <option value="markers_export">All</option>
                                            </select>
                                        </div>
                                        <div class="row">
                                            
                                               @include("Administrator/partials._menuleft")
                                                
                                                 @include("Administrator/partials._contentright")
                                            
                                        </div>
                                    </div>

                       
                            <!-- <table id="tabela_country" class="display" width="100%"></table> -->
                  </div>
        <!-- </div> -->
    </body>

       @yield('script')
         @include("Administrator/partials._footer")
</html>
