@extends('Administrator.admin')

@section('stylesheet')
    <link href="{{URL('/')}}/plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <!-- Waves Effect Css -->
    <link href="{{URL('/')}}/plugins/node-waves/waves.css" rel="stylesheet" />

    <!-- Animation Css -->
    <link href="{{URL('/')}}/plugins/animate-css/animate.css" rel="stylesheet" />
    <!-- Wait Me Css -->
    <link href="{{URL('/')}}/plugins/waitme/waitMe.css" rel="stylesheet" />

    <!-- GALERIA -->
    <link href="{{URL::to('/')}}/plugins/nestable/jquery-nestable.css" rel="stylesheet" />
    <link href="{{URL('/')}}/plugins/dropzone/dropzone.css" rel="stylesheet">
    <link href="{{URL('/')}}/plugins/light-gallery/css/lightgallery.css" rel="stylesheet">

    <!-- Bootstrap Material Datetime Picker Css -->
    <link href="{{URL('/')}}/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet" />
@endsection

@section('script')

@endsection

@section('menu_content')

@endsection

@section('title_content')
    @if(isset($evento)) Editar Evento @else Novo Evento @endif 
@endsection

@section('content')

    @if(isset($evento->id) && $evento->id)
        {!! Form::model($evento, array('route' => array('Evento.update', $evento->id), 'method' => 'PUT','files'=>true)) !!}
    @else
        {!! Form::open(array('route' => 'Evento.store' ,'files'=>true)) !!}
    @endif


    <div class=" pull-right topBottom">
    {{ Form::submit('Guardar', array('class'=>'btn btn-success btn-lg btn-block ')) }}
    </div>

    <div class="content">
        <div class="col-md-8">
            <fieldset>
                <legend>Dados Evento</legend>
                <div class="form-group">
                    <div class="form-line">
                        {{ Form::label('nome','Nome:') }}
                        {{ Form::text('nome', $evento->nome ?? '', array('class'=>'form-control','required'=>'', 'maxlength'=>'200')) }}
                    </div>
                </div>

                <div class="form-group">
                    <div class="form-line">
                        {{ Form::label('endereco','Endereço:') }}
                        {{ Form::text('endereco', $evento->endereco ?? '', array('class'=>'form-control','required'=>'', 'maxlength'=>'200')) }}
                    </div>
                </div>

                <div class="form-group">
                    <div class="form-line">
                        {{ Form::label('descricao', "Descrição:") }}
                        {{ Form::textarea('descricao', $evento->descricao ?? '', array('class'=>'editor', 'rows'=>'10')) }}
                    </div>
                </div>

                <div class="row form-group ">
                    <div class='col-md-6'>
                        <div class="form-line">
                            <div class='input-group'>
                                <label for="dataInicio">Inicio: </label>
                                <input type="text" class="datetimepicker form-control" placeholder="Escolhe a data e hora..." value="{{$evento->dataInicio ?? null}}" required  name="dataInicio"  id="dataInicio" />
                            </div>
                        </div>
                    </div>

                    <div class='col-md-6'>
                        <div class="form-line">
                            <div class='input-group'>
                                <label for="dataFim">Fim: </label>
                                <input type="text" class="datetimepicker form-control" placeholder="Escolhe a data e hora..."  value="{{$evento->dataFim ?? null}}" name="dataFim"  id="dataFim"  />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row form-group">
                    <div class='col-md-6'>
                        <div class="form-line">
                            <div class='input-group'>
                                <label for="numeroInscricao">Número Inscrição: </label>
                                <input type='number' name="numeroInscricao" class="form-control"  value="{{$evento->numeroInscricao ?? null}}" id="numeroInscricao" min="0" placeholder="Lotação Maxima" />
                            </div>
                        </div>
                    </div>
                    <div class='col-md-6'>
                        <div class="form-line">
                            <div class='input-group'>
                                <label for="precoIndividual">Preço: </label>
                                <input type='number' name="precoIndividual" class="form-control" value="{{$evento->precoIndividual ?? null}}" id="precoIndividual" min="0" placeholder="Preço Individual" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row form-group">
                    <div class="col-md-12">
                        <div class="form-line">
                            <div class='input-group'>
                                <label for="dataPagamento">Limite Pagamento: </label>
                                <input type="text" class="datetimepicker form-control" placeholder="Escolhe a data e hora..." value="{{$evento->dataPagamento ?? null}}" name="dataPagamento"  id="dataPagamento" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="form-line">
                        {{ Form::label('latitude','Latitude:') }}
                        {{ Form::text('latitude', $evento->latitude ?? '', array('class'=>'form-control', 'maxlength'=>'200')) }}
                    </div>
                </div>

                <div class="form-group">
                    <div class="form-line">
                        {{ Form::label('longitude','Longitude:') }}
                        {{ Form::text('longitude', $evento->longitude ?? '', array('class'=>'form-control', 'maxlength'=>'200')) }}
                    </div>
                </div>

                <div class="form-group">
                    <div class="form-line">
                        {{ Form::label('linkGoogleMaps','Google Maps:') }}
                        {{ Form::text('linkGoogleMaps', $evento->linkGoogleMaps ?? '', array('class'=>'form-control', 'maxlength'=>'200')) }}
                    </div>
                </div>
            </fieldset>
        </div>

        <div class="col-md-4">
            <fieldset>
                <legend>Informações</legend>
                <div class="form-group">
                    <div class="form-line">
                        <button type="button" class="btn btn-default waves-effect m-r-20" data-toggle="modal" data-target="#largeModal">Escolher Imagem</button>
                            @if(isset($evento->idImagem))
                                {{ Form::hidden('idimagem',$evento->idimagem, array('id' => 'idimagem', 'name'=>'idimagem')) }}
                                {{ Form::text('Titulo Imagem', $evento->imagems->url, array('id' => 'tituloIMG','class'=>'form-control',  'placeholder'=>'Selecione uma imagem para ao artigo.', 'readonly'=>'')) }}
                            @else
                            {{ Form::hidden('idimagem',null, array('id' => 'idimagem', 'name'=>'idimagem')) }}
                                {{ Form::text('Titulo Imagem', null, array('id' => 'tituloIMG','class'=>'form-control',  'placeholder'=>'Selecione uma imagem para ao artigo.', 'readonly'=>'')) }}
                            @endif
                    </div>
                </div>


                <div class="form-group">
                    <div class="form-line">
                        {{ Form::label('ativado','Estado:') }}
                        <div class="switch">
                            <label>Despublicado<input type="checkbox" name="ativado" @if(isset($evento)) @if($evento->ativado) checked @endif @else checked @endif><span class="lever"></span>Publicado</label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="form-line">
                        {{ Form::label('destaque','Destaque:') }}
                        <div class="switch">
                            <label>Não<input type="checkbox" name="destaque" @if(isset($evento)) @if($evento->destaque) checked @endif @else checked @endif><span class="lever"></span>Sim</label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="form-line">
                        {{ Form::label('idCategoria','Pasta:') }}
                        {{ Form::select('idCategoria',$cat, [$evento->categorias->id ?? null], array('class'=>'form-control show-tick','required'=>'required','data-toggle'=>'tooltip', 'data-placement'=>'top','title'=>'Selecione a pasta.')) }}
                    </div>
                </div>

                <div class="form-group">
                    <div class="form-line">
                        {{ Form::label('tag','Tags:') }}
                        {{ Form::select('tag[]',$tag, $evento->tags ?? null, array('id'=>'tagselect','class'=>'form-control show-tick', 'multiple', 'data-live-search'=>'true','data-toggle'=>'tooltip')) }}
                    </div>
                </div>

                <div class="form-group">
                    <div class="form-line">
                        {{ Form::label('keyword','Palavra-chave:') }}
                        {{ Form::text('keyword', $evento->keyword ?? '', array('class'=>'form-control', 'maxlength'=>'250', 'placeholder'=>'Separe as palavras-chave com vírgula.')) }}
                    </div>
                </div>
            </fieldset>

            <fieldset style="margin-top:15px;color:red;">
                <legend>Dicas</legend>
                <div class="form-group">
                <div class="form-line">

                </div>
                </div>
            </fieldset>
        </div>
    </div>
    {!! Form::close() !!}
    @include("Administrator.partials._galeria")

@endsection

@section('script_bottom')
    <script src="{{URL('/')}}/plugins/multi-select/js/jquery.multi-select.js"></script>
    <script src="{{URL('/')}}/plugins/tinymce/tinymce.js"></script>
    <script src="{{URL('/')}}/js/material/pages/forms/editors.js"></script>
    <script src="{{URL('/')}}/plugins/dropzone/dropzone.js"></script>
    <script src="{{URL('/')}}/plugins/jquery-slimscroll/jquery.slimscroll.js"></script>
    <script src="{{URL('/')}}/js/material/pages/forms/dropzone.js"></script>
    <script src="{{URL('/')}}/js/material/pages/forms/basic-form-elements.js"></script>
    <!-- Waves Effect Plugin Js -->
    <script src="{{URL('/')}}/plugins/node-waves/waves.js"></script>
    <script src="{{URL('/')}}/plugins/autosize/autosize.js"></script>
    <!-- Moment Plugin Js -->
    <script src="{{URL('/')}}/plugins/momentjs/moment.js"></script>
    <!-- Bootstrap Material Datetime Picker Plugin Js -->
    <script src="{{URL('/')}}/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js"></script>
    <!-- MODAL GALERIA -->
    <script src="{{URL('/')}}/js/galeria.js"></script>
    <!-- ENd MODAL GALERIA -->
    <script src="{{ URL::to('/') }}/plugins/bootstrap-select/js/bootstrap-select.js"></script>
    {{-- <script type="text/javascript">
    //   $("#tagselect").val({{$evento->tags()->allRelatedIds()}});
    </script> --}}
@endsection
