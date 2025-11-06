
@extends('Administrator.admin')

@section('stylesheet')


@endsection 

@section('script')  


@endsection 

@section('menu_content')

@endsection

@section('title_content')
  @if(isset($language)) Editar Idioma @else Novo Idioma @endif 
@endsection

@section('content')

  @if(isset($language->id) && $language->id)
    {!! Form::model($language, array('route' => array('Language.update', $language->id), 'method' => 'PUT')) !!}
  @else
    {!! Form::open(array('route' => 'Language.store')) !!} 
  @endif

  <div class=" pull-right topBottom">
   {{ Form::submit('Guardar', array('class'=>'btn btn-success btn-lg btn-block')) }}
 </div>

 {{--  ***********ADD TIPO*************** --}}
 <div class="content"> 
  <div class="col-md-8">
   <fieldset style="margin-top:15px;">

    <div class="form-group">
      <div class="form-line">
        {{ Form::label('titulo','Nome:') }}
        {{ Form::text('titulo', $language->titulo ?? '', array('class'=>'form-control','required'=>'required')) }}
      </div>
    </div>

    <div class="form-group">
      <div class="form-line">
        {{ Form::label('tag','Codigo:') }}
        {{ Form::text('tag', $language->tag ?? '', array('class'=>'form-control','required'=>'required')) }}
      </div>
    </div>

    <div class="form-group">
      <div class="form-line">
        {{ Form::label('ativado','Estado:')}} 
        <div class="switch">
          <label>Despublicado<input type="checkbox" name="ativado" @if(isset($language)) @if($language->ativado) checked @endif @else checked @endif><span class="lever"></span>Publicado</label>
        </div>
      </div>
    </div>


  </fieldset>
</div>



</div>
{!! Form::close() !!}

{{--  ***********ADD TIPO*************** --}}
@endsection 
