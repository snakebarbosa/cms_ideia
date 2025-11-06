
@extends('Administrator.admin')

@section('stylesheet')
<link rel="stylesheet" href="{{ URL::asset('css/parsley.css') }}">

<style>


</style>

@endsection 

@section('script')  

<script src="{{ URL::asset('js/parsley.js') }}"></script>

@endsection 


@section('title_content')

Módulo Menu
@endsection 

@section('menu_content')

@endsection 

@section('content')

{!! Form::open(array('route' => 'Menu.storemenu','data-parsley-validate'=>'')) !!}
<div class=" pull-right topBottom">   

  {{ Form::submit('Guardar', array('class'=>'btn btn-success btn-lg btn-block ')) }}

</div>

<div class="content"> 
 <div class="col-md-12">
  <fieldset>
   <legend>Informações</legend>
   <div class="form-group">
    <div class="form-line">
     {{ Form::label('topo','Menu Topo:') }}
     {{ Form::select('topo', $menus, null,array('class'=>'form-control','required'=>'','data-toggle'=>'tooltip','data-placement'=>'top','title'=>'Selecione o menu.')) }} 
   </div>
 </div>

 <div class="form-group">
  <div class="form-line">
   {{ Form::label('principal','Menu Princial:') }}
   {{ Form::select('principal', $menus, null,array('class'=>'form-control','required'=>'','data-toggle'=>'tooltip','data-placement'=>'top','title'=>'Selecione o menu.')) }}
 </div>
</div>

<div class="form-group">
  <div class="form-line">
   {{ Form::label('navegacao','Navegação:') }}
   {{ Form::select('navegacao', $menus, null,array('class'=>'form-control','required'=>'','data-toggle'=>'tooltip','data-placement'=>'top','title'=>'Selecione o menu.')) }} 
 </div>
</div>

<div class="form-group">
  <div class="form-line">
    {{ Form::label('rodape','Rodapé:') }}
    {{ Form::select('rodape', $menus, null,array('class'=>'form-control','required'=>'','data-toggle'=>'tooltip','data-placement'=>'top','title'=>'Selecione o menu.')) }} 
  </div>
</div>



</fieldset>

<fieldset style="margin-top:15px;">
  <div class="form-group">
    <div class="form-line">
     <legend>Dicas</legend>



   </div>
 </div>

</fieldset>
</div>


</div>

{!! Form::close() !!}
@endsection 

@section('script_bottom')

<script>
 $(document).ready(function(){


   $(function(){

   });

 </script>
 @endsection    
