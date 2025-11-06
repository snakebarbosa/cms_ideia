
@extends('Administrator.admin')


@section('title_content')

Novo Tipo
@endsection 

@section('stylesheet')


@endsection 

@section('script')  


@endsection 

@section('menu_content')

 @endsection

 @section('content')

{!! Form::open(array('route' => 'Tipo.store')) !!}

 <div class=" pull-right topBottom">
   {{--  <a href="{{ URL::to('/') }}/" class="btn btn-default btn-sm btn-success"><i class="glyphicon glyphicon-ok"></i> Guardar</a>
     
   <a href="{{ URL::to('/') }}/" class="btn btn-default btn-sm btn-danger"><i class="glyphicon glyphicon-remove"></i> Cancelar</a> --}}

   {{ Form::submit('Guardar', array('class'=>'btn btn-success btn-lg btn-block')) }}

 </div>

 {{--  ***********ADD TIPO*************** --}}
 <div class="content"> 
  {{-- {!! Form::open(array('route' => 'Categoria.store','data-parsley-validate'=>'')) !!} --}}
  <div class="col-md-8">

   <fieldset style="margin-top:15px;">
     <div class="form-group">
      <div class="form-line">
        {{ Form::label('titulo','Titulo:') }}
        {{ Form::text('titulo', null, array('class'=>'form-control',)) }}
      </div>
    </div>


  </fieldset>
</div>
<div class="col-md-4">

  <fieldset style="margin-top:15px;">

   <legend>Definir Tipo</legend>

   <div class="form-group">
    <div class="form-line">

     {{ Form::label('Documento','Documento:') }}
     {{ Form::radio('tipo', 'doc', true) }} 
    
     {{ Form::label('Menu','Menu:') }}
     {{ Form::radio('tipo', 'menu', true) }} 

     {{ Form::label('User','User:') }}
     {{ Form::radio('tipo', 'user', true) }} 

   </div>
 </div>

 <div style="margin-top:15px;">

  <div class="form-group">
    <div class="form-line">
      {{ Form::label('ativado','Estado:') }} 
      <div class="switch">
        <label>Despublicado<input type="checkbox" name="ativado" checked><span class="lever"></span>Publicado</label>
      </div>
    </div>
  </div>
</div>

</fieldset>
</div>

<div class="col-md-8" style="margin-top:15px;">
  {{-- {{ Form::submit('Create Post', array('class'=>'btn btn-success btn-lg btn-block')) }}  --}}
</div>

</div>

{!! Form::close() !!}

{{--  ***********ADD TIPO*************** --}}
@endsection 
