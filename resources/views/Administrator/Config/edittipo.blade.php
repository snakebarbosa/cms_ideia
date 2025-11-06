
@extends('Administrator.admin')
  

@section('title_content')

  Novo Tipo
@endsection 

@section('stylesheet')
  <link rel="stylesheet" href="{{ URL::asset('css/parsley.css') }}">
  
@endsection 

@section('script')  
   
    <script src="{{ URL::asset('js/parsley.js') }}"></script>
@endsection 

@section('menu_content')
  <div class=" pull-right">
   {{--  <a href="{{ URL::to('/') }}/" class="btn btn-default btn-sm btn-success"><i class="glyphicon glyphicon-ok"></i> Guardar</a>
     
    <a href="{{ URL::to('/') }}/" class="btn btn-default btn-sm btn-danger"><i class="glyphicon glyphicon-remove"></i> Cancelar</a> --}}
    
      {!! Form::model($tipo, array('route' => array('Tipo.update', $tipo->id), 'method' => 'PUT')) !!}
      {{ Form::submit('Guardar', array('class'=>'btn btn-success btn-lg btn-block')) }}
      
  </div>
@endsection

@section('content')
  
 {{--  ***********ADD TIPO*************** --}}
    <div class="content"> 
      {{-- {!! Form::open(array('route' => 'Categoria.store','data-parsley-validate'=>'')) !!} --}}
        <div class="col-md-8">
         
             <fieldset style="margin-top:15px;">
             
                {{ Form::label('titulo','Nome:') }}
                {{ Form::text('titulo', null, array('class'=>'form-control',)) }}
               
              </fieldset>
                 <div class="col-md-12" style="margin-top:15px;">

                 {{ Form::label('Estado','Estado:') }}
                 {{ Form::select('ativado',  ['1' => 'Publicado', '0' => 'Despublicado'],null, array('class'=>'form-control')) }} 
                </div>
           </div>
          
           <div class="col-md-8" style="margin-top:15px;">
          
           </div>
      {!! Form::close() !!}
    </div>
  
  {{--  ***********ADD TIPO*************** --}}
@endsection 
  