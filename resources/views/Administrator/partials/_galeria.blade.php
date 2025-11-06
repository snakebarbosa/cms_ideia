<div class="modal fade" id="largeModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title" id="largeModalLabel">Galeria</h4>
            </div>
            <div class="modal-body">
                <div class="content">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12"  style="right: 10px;" >
                        <!-- categoriaImabgem -->
                        @include("Administrator/Midia/partials._categoriaImag", ['cat'=>$catimg,'delete'=> false])
                        <!-- FimcategoriaImabgem -->
                    </div>

                    <div class="col-md-8" >
                        <!-- inicio upload -->
                        @include("Administrator/Midia/partials._upload")
                        <!-- fim upload -->

                        <!-- Inicio Galeria -->
                        @include("Administrator/Midia/partials._galeria",['delete'=> false])
                        <!-- Fim Galeria -->

                    </div>
                </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="okModalGal" class="btn btn-link waves-effect">INSERIR</button>
                <button type="button" id="cancelModalGal" class="btn btn-link waves-effect" data-dismiss="modal">FECHAR</button>
            </div>


        </div>
    </div>
</div>

