@extends('Administrator.admin')

@section('stylesheet')
<link href="{{URL('/')}}/plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />
<link href="{{URL('/')}}/plugins/multi-select/css/multi-select.css" rel="stylesheet">

@endsection

@section('script')

@endsection

@section('menu_content')

@endsection

@section('title_content')
    @if(isset($link)) Editar Link @else Novo Link @endif 
@section('menu_content')

@section('content')

    @if(isset($link->id) && $link->id)
        {!! Form::model($link, array('route' => array('Link.update', $link->id), 'method' => 'PUT')) !!}
    @else
        {!! Form::open(array('route' => 'Link.store','data-parsley-validate'=>'')) !!}
    @endif


    <div class=" pull-right topBottom">
    {{ Form::submit('Guardar', array('class'=>'btn btn-success btn-lg btn-block')) }}
    </div>

    <div class="content">
        <div class="col-md-12">
            <div class="col-md-8">
                <fieldset style="margin-top:15px;">
                <div class="form-group">
                    <div class="form-line">
                    {{ Form::label('titulo','Titulo:') }}
                    {{ Form::text('titulo', $link->titulo ?? '', array('class'=>'form-control','required'=>'', 'maxlength'=>'200')) }}
                    </div>
                </div>

                <div class="form-group">
                    <div class="form-line">
                    {{ Form::label('url','Url:') }}
                    {{ Form::text('url', $link->url ?? '', array('class'=>'form-control', 'placeholder'=>'exemplo de URL (http://www.site.com)','required'=>'required', 'maxlength'=>'200')) }}
                    </div>
                </div>
                </fieldset>
            </div>
        

            <div class="col-md-4">
                <fieldset style="margin-top:15px;">
                    <div class="form-group">
                        <div class="form-line">
                            {{ Form::label('ativado','Estado:') }}
                            <div class="switch">
                            <label>Despublicado<input type="checkbox" name="ativado" @if(isset($link)) @if($link->ativado) checked @endif @else checked @endif><span class="lever"></span>Publicado</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-line">
                            {{ Form::label('destaque','Destaque:') }}
                            <div class="switch">
                            <label>Não<input type="checkbox" name="destaque" @if(isset($link)) @if($link->destaque) checked @endif @else checked @endif><span class="lever"></span>Sim</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-line">
                            {{ Form::label('idCategoria','Categoria:') }}
                            {{ Form::select('idCategoria',$cat, [$link->categorias->id ?? null],array('class'=>'form-control show-tick','data-live-search'=>'true','required'=>'required','data-toggle'=>'tooltip')) }}
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-line">
                            {{ Form::label('tag','Tags:') }}
                            {{ Form::select('tag[]',$tag, $link->tags ?? null, array('class'=>'form-control show-tick', 'data-live-search'=>'true','data-toggle'=>'tooltip', 'multiple')) }}
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>
    </div>

{!! Form::close() !!}

@endsection

@section('script_bottom')
<script src="{{URL('/')}}/plugins/multi-select/js/jquery.multi-select.js"></script>
<script src="{{URL('/')}}/plugins/bootstrap-tagsinput/bootstrap-tagsinput.js"></script>
<script src="{{ URL::to('/') }}/plugins/bootstrap-select/js/bootstrap-select.js"></script>

<script>
 $(document).ready(function(){

 });

</script>
@endsection

