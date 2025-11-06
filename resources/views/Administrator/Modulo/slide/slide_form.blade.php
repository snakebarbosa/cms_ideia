@extends('Administrator.admin')

@section('stylesheet')

<!-- GALERIA -->
<link href="{{URL::to('/')}}/plugins/nestable/jquery-nestable.css" rel="stylesheet" />
<link href="{{URL('/')}}/plugins/dropzone/dropzone.css" rel="stylesheet">
<link href="{{URL('/')}}/plugins/light-gallery/css/lightgallery.css" rel="stylesheet">
<!-- GALERIA -->

@endsection

@section('script')
	 <!-- {{ Html::script('js/bootstrap-select.js')}} -->
@endsection

@section('menu_content')
  
@endsection

@section('title_content')
    @if(isset($slide)) Editar Slide @else Novo Slide @endif 
@endsection

@section('content')

    @if(isset($slide->id) && $slide->id)
        {!! Form::model($slide, array('route' => array('Slide.update', $slide->id), 'method' => 'PUT','data-parsley-validate'=>'')) !!}
    @else
        {!! Form::open(array('route' => 'Slide.store','data-parsley-validate'=>'')) !!}
    @endif

    <div class="topBottom pull-right">
     	{{ Form::submit('Guardar', array('class'=>'btn btn-success btn-lg btn-block')) }}
    </div>

    <div class="content">
		<div class="col-md-12">
			<div class="col-md-8">
                <fieldset style="margin-top:15px;">
                    <fieldset style="margin-top:15px;">
                        <div class="form-group">
                            <div class="form-line">
                                {{ Form::label('tituloPT','Titulo (PT):') }}
                                {{ Form::text('tituloPT', $content['tituloPT'] ?? '', array('class'=>'form-control','required'=>'', 'maxlength'=>'200')) }}
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-line">
                                {{ Form::label('textopt','Descrição (PT):') }}
                                {{ Form::textarea('textopt',$content['conteudoPT'] ?? '', array('class'=>'form-control editor','required'=>'','data-toggle'=>'tooltip','data-placement'=>'top', 'cols'=>'200', 'rows'=>'5')) }}
                            </div>
                        </div>
                    </fieldset>

                    <fieldset style="margin-top:15px;">
                        <div class="form-group">
                            <div class="form-line">
                                {{ Form::label('tituloEN','Titulo (EN):') }}
                                {{ Form::text('tituloEN',$content['tituloEN'] ?? '', array('class'=>'form-control','required'=>'', 'maxlength'=>'200')) }}
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-line">
                                {{ Form::label('textoen','Descrição (EN):') }}
                                {{ Form::textarea('textoen',$content['conteudoEN'] ?? '', array('class'=>'form-control editor','required'=>'','data-toggle'=>'tooltip','data-placement'=>'top', 'cols'=>'200', 'rows'=>'5')) }}
                            </div>
                        </div>
                    </fieldset>
                </fieldset>
            </div>

		    <div class="col-md-4">
                <fieldset style="margin-top:15px;">
                    <div class="form-group">
                        <div class="form-line">
                            {{ Form::label('url','URl (Link):') }}
                            {{ Form::text('url', $slide->url ?? '', array('class'=>'form-control','required'=>'')) }}
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-line">
                            {{ Form::label('posicao','Posição:') }}
                            {{ Form::text('posicao', $slide->posicao ?? '', array('class'=>'form-control','required'=>'')) }}
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-line">
                            {{ Form::label('ativado','Estado:') }}
                            <div class="switch">
                            <label>Despublicado<input type="checkbox" name="ativado"@if(isset($slide)) @if($slide->ativado) checked @endif @else checked @endif>
                                <span class="lever"></span>
                                Publicado
                            </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-line">
                            {{ Form::label('publicar','Data Publicaçāo:') }}
                            {{ Form::date( 'publicar', $slide->publicar ?? Carbon\Carbon::now(),  ['id' => 'idpublicar', 'class'=>'form-control'])}}
                            <div class="error-publicar"></div>
                      </div>
                    </div>
                    

                    <div class="form-group">
                      <div class="form-line">
                        {{ Form::label('despublicar','Data Remoçāo:') }}
                        {{ Form::date('despublicar', $slide->despublicar ?? Carbon\Carbon::parse(config('custom.LAST_DATA_FIELD')),  ['id' => 'iddespublicar', 'class'=>'form-control'])}}
                        <div class="error-despublicar"></div>
                      </div>
                    </div>

                    <div class="form-group">
                        <div class="form-line">
                            <button type="button" class="btn btn-default waves-effect m-r-20" data-toggle="modal" data-target="#largeModal">Escolher Imagem</button>
                            {{ Form::hidden('idimagem', $slide->imagems->id ?? null, array('id' => 'idimagem', 'name'=>'idimagem')) }}
                            {{ Form::text('Titulo Imagem', $slide->imagems->url ?? null, array('id' => 'tituloIMG','class'=>'form-control',  'placeholder'=>'Selecione uma imagem para o slide.', 'readonly'=>'')) }}
                      </div>
                    </div>
                </fieldset>
            </div>
		</div>

		<div class="col-md-8" style="margin-top:15px;">
        </div>
        
		{!! Form::close() !!}
        @include("Administrator.partials._galeria")
	</div>
@endsection

@section('script_bottom')
    <script src="{{URL('/')}}/js/galeria.js"></script>
@endsection

