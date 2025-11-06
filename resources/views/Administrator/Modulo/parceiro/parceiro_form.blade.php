
@extends('Administrator.admin')

@section('stylesheet')
    @if(isset($parceiro))
        {{ Html::style('css/select2.min.css')}}
        <style>
            div.bootstrap-select{
                margin: 0;
                padding: 0;
                border: none;
            }

            .bootstrap-select.btn-group:not(.input-group-btn), .bootstrap-select.btn-group[class*="col-"] {
                float: none;
                display: inline-block;
                margin-left: 0;
            }
            .bootstrap-select:not([class*="col-"]):not([class*="form-control"]):not(.input-group-btn) {
                width: 220px;
            }
            .bootstrap-select.btn-group .dropdown-menu.inner {
                position: static;
                float: none;
                border: 0;
                padding: 0;
                margin: 0;
                border-radius: 0;
                -webkit-box-shadow: none;
                box-shadow: none;
            }

            .bootstrap-select.btn-group .dropdown-menu {
                min-width: 100%;
                z-index: 1035;
                -webkit-box-sizing: border-box;
                -moz-box-sizing: border-box;
                box-sizing: border-box;
            }

            .bootstrap-select .media-object {
                display: block;
                height: 40px;
                width: 40px;
            }
        </style>
    @endif 
    <link href="{{URL('/')}}/plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />
    <link href="{{URL::to('/')}}/plugins/nestable/jquery-nestable.css" rel="stylesheet" />
    <link href="{{URL('/')}}/plugins/dropzone/dropzone.css" rel="stylesheet">
    <link href="{{URL('/')}}/plugins/light-gallery/css/lightgallery.css" rel="stylesheet">
@endsection 

@section('script')
	 {{ Html::script('js/bootstrap-select.js')}}
@endsection 
 
@section('menu_content')
  
@endsection 

@section('title_content')
    @if(isset($parceiro)) Editar Parceiro @else Novo Parceiro @endif 
@endsection

@section('content')
   
    @if(isset($parceiro->id) && $parceiro->id)
      {!! Form::model($parceiro, array('route' => array('Parceiro.update', $parceiro->id), 'method' => 'PUT','data-parsley-validate'=>'')) !!}
    @else
      {!! Form::open(array('route' => 'Parceiro.store','data-parsley-validate'=>'')) !!} 
    @endif

    <div class="topBottom pull-right">
        {{ Form::submit('Guardar', array('class'=>'btn btn-success btn-lg btn-block')) }}
    </div>


    <div class="content"> 
		<div class="col-md-12">
            <div class="col-md-8">  
                <fieldset style="margin-top:15px;">
                    <fieldset style="margin-top:15px;">
                        {{ Form::label('titulo','Titulo:') }}
                        {{ Form::text('titulo', $parceiro->titulo ?? '', array('class'=>'form-control','required'=>'', 'maxlength'=>'200')) }}

                        {{ Form::label('url','Url:') }}
                        {{ Form::text('url', $parceiro->url ?? '', array('class'=>'form-control','required'=>'', 'maxlength'=>'200')) }}
                    </fieldset>
		        </fieldset>
		    </div>
            
            <div class="col-md-4">
		        <fieldset style="margin-top:15px;">
                    <div class="form-group">
                        <div class="form-line">
                            {{ Form::label('ativado','Estado:')}}
                            <div class="switch">
                                <label>Despublicado<input type="checkbox" name="ativado" @if(isset($parceiro)) @if($parceiro->ativado) checked @endif @else checked @endif>
                                    <span class="lever"></span>Publicado
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-line">
                            <button type="button" class="btn btn-default waves-effect m-r-20" data-toggle="modal" data-target="#largeModal">Escolher Imagem</button>
                            @if(isset($parceiro->idImagem)) 
                                {{ Form::hidden('idimagem', $parceiro->imagems->id, array('id' => 'idimagem', 'name'=>'idimagem')) }} 
                                {{ Form::text('Titulo Imagem', $parceiro->imagems->titulo, array('id' => 'tituloIMG','class'=>'form-control',  'placeholder'=>'Selecione uma imagem para ao parceiro.', 'readonly'=>'')) }}
                            @else
                                {{ Form::hidden('idimagem', null, array('id' => 'idimagem', 'name'=>'idimagem')) }}
                                {{ Form::text('Titulo Imagem', null, array('id' => 'tituloIMG','class'=>'form-control',  'placeholder'=>'Selecione uma imagem para ao parceiro.', 'readonly'=>'')) }}
                            @endif
                        </div>
                    </div>
             			
		        </fieldset>
		    </div>
		</div>

		<div class="col-md-8" style="margin-top:15px;">
		            
		</div>
		{!! Form::close() !!}
	</div>
         

@endsection 

@include("Administrator.partials._galeria")

@section('script_bottom')
    <script src="{{URL('/')}}/plugins/multi-select/js/jquery.multi-select.js"></script>
    <script src="{{URL('/')}}/plugins/tinymce/tinymce.js"></script>
    <script src="{{URL('/')}}/js/material/pages/forms/editors.js"></script>
    <script src="{{URL('/')}}/js/galeria.js"></script> 
    <script src="{{URL('/')}}/plugins/dropzone/dropzone.js"></script>
    <script src="{{URL('/')}}/plugins/jquery-slimscroll/jquery.slimscroll.js"></script>
    <script src="{{URL('/')}}/js/material/pages/forms/dropzone.js"></script>
    <script src="{{ URL::to('/') }}/plugins/bootstrap-select/js/bootstrap-select.js"></script> 
@endsection    

