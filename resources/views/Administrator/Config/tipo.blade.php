
@extends('Administrator.admin')



@section('title_content')

   Tipo
@endsection 

@section('stylesheet')
 
{{-- {{ $boinfo['logo']  }} --}}
 
@endsection 

@section('script')

  
   
@endsection 
 
@section('menu_content')
  <div class=" pull-right">

    <a id="novo" href="{{ route('Tipo.create') }}">
        <button type="button" class="btn btn-success waves-effect" >
            <i class="material-icons">add</i>
            <span>Novo Tipo</span>
        </button>
    </a>

 <a id="publicar" data-target="event" data-obj="#dataform" data-rel="submit" data-select='id' href= '{!! URL::to('/') !!}/Administrator/Tipo/publicar/'+ids[0]; >
    <button type="button" class="btn btn-info  waves-effect" >
      <i class="material-icons">done</i>
      <span>Publicar</span>
    </button>
  </a>

<a id="despublicar" data-target="event" data-obj="#dataform" data-rel="submit" data-select='id' href='{!! URL::to('/') !!}/Administrator/Tipo/despublicar/'+ids[0];>
    <button type="button" class="btn btn-warning  waves-effect" >
      <i class="material-icons">clear</i>
      <span>Despublicar</span>
    </button>
  </a>


  <a id="remover" data-target="event" data-obj="#dataform" data-rel='confirm' data-action="submit" alert-text="Que queres eliminar Tipo?" data-select='id' href='{!! URL::to('/') !!}/Administrator/Tipo/delete/'+ids[0]; >
    <button type="button" class="btn btn-danger waves-effect" >
      <i class="material-icons">remove</i>
      <span>Remover</span>
    </button>
  </a> 
  
  </div>
@endsection 


@section('content')

    <div class="content">
        
       
     {{--     <table id="tabela" class="display mdl-data-table" width="100%" >
           


         </table> --}}

         {{--  <table class=" display mdl-data-table" id="users-table">
               
         </table> --}}
        
    
    </div>
  
@endsection 
@section('script_bottom')
<script>
   $(document).ready(function(){
       });

  

</script>


@endsection

