@extends('Administrator.admin')

@section('stylesheet')

@endsection

@section('script')

@endsection

@section('menu_content')

@endsection

@section('title_content')
    @if(isset($tipo)) Editar Tipo @else Novo Tipo @endif 
@endsection

@section('content')
    @if(isset($tipo->id) && $tipo->id)
        {!! Form::model($tipo, array('route' => array('Tipo.update', $tipo->id), 'method' => 'PUT')) !!}
    @else
        {!! Form::open(array('route' => 'Tipo.store')) !!} 
    @endif

    <div class="pull-right topBottom ">
        {{ Form::submit('Guardar', array('class'=>'btn btn-success btn-lg btn-block')) }}
    </div>

 {{--  ***********ADD TIPO*************** --}}
    <div class="content">
        {{ Form::hidden('tipo', 'menu', array('id' => 'type', 'name'=>'tipo')) }}
        <div class="col-md-8">
            <fieldset style="margin-top:15px;">
                <div class="form-group">
                    <div class="form-line">
                        {{ Form::label('titulo','Nome:') }}
                        {{ Form::text('titulo', $tipo->titulo ?? '', array('class'=>'form-control',)) }}
                    </div>
                </div>
            </fieldset>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <div class="form-line">
                    {{ Form::label('ativado','Estado:')}}
                    <div class="switch">
                        <label>Despublicado<input type="checkbox" name="ativado" @if(isset($tipo)) @if($tipo->ativado) checked @endif @else checked @endif><span class="lever"></span>Publicado</label>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="form-line">
                    {{ Form::label('posicao','Posição:') }}
                    {{ Form::text('posicao', $tipo->posicao ?? '', array('class'=>'form-control',)) }}
                </div>
            </div>
        </div>

        <div class="col-md-8" style="margin-top:15px;">

        </div>

    </div>
    {!! Form::close() !!}
{{--  ***********ADD TIPO*************** --}}
@endsection
