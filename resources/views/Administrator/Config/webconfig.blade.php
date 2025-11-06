@extends('Administrator.admin')



@section('title_content')

   Editar WebConfig
@endsection

@section('stylesheet')

<style>
  .description {
    width: 75%;
  }
</style>


@endsection

@section('script')
   <!-- {{ Html::script('js/bootstrap-select.js')}} -->


@endsection

    
@section('menu_content')
  
@endsection


@section('content')

@php
    if(isset($config) && $config != null){
      $id = $config->id;
  }else{
    $id = 1;
  }
@endphp

{!! Form::model($config, array('route' => array('Config.update', $id), 'method' => 'PUT','data-parsley-validate'=>'', 'class' => 'form-inline')) !!}

  <div class="pull-right topBottom">
    {{ Form::submit('Guardar', array('class'=>'btn btn-success btn-lg ')) }}
  </div>

  <div class="row">
    <div class="col-md-6">
      <div class="form-group">
        @if (isset($val) && $val != null)
          @foreach ($val as $key => $item)
            {{ Form::text($key, $key) }} : 
            {{ Form::text( $item,  $item, ['class' => 'description']) }}
            {{-- <a href="{{route('config.delete', $key)}}" class="btn botaoListar bg-red btn-circle waves-effect waves-circle waves-float"><i class="material-icons">delete</i></a> --}}

            <button id="remover" type="button" data-target="event" data-action="nada" data-obj="#formUser" data-rel='confirm' data-url="{{route('config.delete', $key)}}" alert-text="Tem Certeza que queres eliminar?" class="btn botaoListar bg-red btn-circle waves-effect waves-circle waves-float">
                 <i class="material-icons">delete</i>
            </button>

            <br> <br>
          @endforeach
        @endif
      </div>
    </div>
{!! Form::close() !!}



  {!! Form::open(['route' => 'Config.store']) !!}
    <div class="col-md-6">
      <div class="form-group">
          <h4>Adicionar um novo campo e o seu valor</h4>
          {{ Form::text('chave_campo', null, ['placeholder' => 'Nome do campo']) }} : 
          {{ Form::text( 'valor_campo',  null, ['class' => 'description', 'placeholder' => 'Valor']) }}

        <div style="margin-top: 10px;">
          {{ Form::submit('Adicionar Campo', array('class'=>'btn btn-primary btn-lg')) }}
        </div>
      </div>
    </div>

    

{!! Form::close() !!}
  </div>
  


  <div class="col-md-8" style="margin-top:15px;">
  </div> 

</div>


@endsection

@section('script_bottom')
@endsection




