@extends('Administrator.admin')

@section('title_content')

Documentos
@endsection
@section('help')
  @include("Administrator.partials._rapido")
@endsection
@section('stylesheet')

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href=" https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css">

@endsection

@section('script')

{{----}}

@endsection

@section('menu_content')
<div class=" pull-right">
  <a href="{{ route('Documento.create') }}">
    <button type="button" class="btn btn-success waves-effect" >
      <i class="material-icons">add</i>
      <span>Novo Documento</span>
    </button>
  </a>



    <button  id="publicar" data-target="event" data-target="event" data-obj="#formDoc" data-rel='submit' href="{{route('Doc.publicarcheck')}}" type="button" class="btn btn-info  waves-effect" >
      <i class="material-icons">done</i>
      <span>Publicar</span>
    </button>



    <button id="despublicar" data-target="event" data-target="event" data-obj="#formDoc" data-rel='submit' href="{{route('Doc.despublicarcheck')}}" type="button" class="btn bg-deep-orange waves-effect" >
      <i class="material-icons">clear</i>
      <span>Despublicar</span>
    </button>




  <button id="remover" data-target="event"  data-obj="#formDoc" data-rel='confirm' data-action="submit" alert-text="Que queres eliminar Documento?" href="{{route('Doc.removercheck')}}" type="button" class="btn btn-danger waves-effect">
    <i class="material-icons">remove</i>
    <span>Remover</span>
  </button>



  <button id="destacar" data-target="event" data-target="event" data-obj="#formDoc" data-rel='submit' href="{{route('Doc.destaquecheck')}}" type="button" class="btn bg-teal  waves-effect" >
    <i class="material-icons">check_box</i>
    <span>Destaque</span>
  </button>



  <button id="rdestacar" data-target="event" data-target="event" data-obj="#formDoc" data-rel='submit' href="{{route('Doc.rdestaquecheck')}}" type="button" class="btn btn-warning waves-effect" >
    <i class="material-icons">check_box_outline_blank</i>
    <span>Retirar Destaque</span>
  </button>



</div>
@endsection


@section('content')

  @include('Administrator.partials._docs_table_view', ['buttons' => true, 'pageLength' => false])
@endsection

@section('script_bottom')

<!-- <script type="text/javascript" src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script> -->



@endsection

