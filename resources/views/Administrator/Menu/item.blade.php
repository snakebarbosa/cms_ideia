@extends('Administrator.admin')


@section('title_content')
  Menus
@endsection

@section('stylesheet')

<style type="text/css">

    .nested_cmsli{
        margin: 0px!important;
padding: 0;
height: 22px;
    }
</style>

<!-- JQuery Nestable Css -->
<link href="{{URL::to('/')}}/plugins/nestable/jquery-nestable.css" rel="stylesheet" />

@endsection

@section('script')


@endsection

@section('help')
  @include("Administrator.partials._rapido")
@endsection

@section('menu_content')

<div class=" pull-right">
   <a id="novo" href="{{ URL::to('/') }}//Administrator/Item/create">
      <button type="button" class="btn btn-success waves-effect" >
        <i class="material-icons">add</i>
        <span>Novo Item</span>
      </button>
    </a>
</div>
@endsection

@section('content')

@if($trees != "")

  @foreach($trees as $tree)
      @include('Administrator.partials._item', ['titulo'=>'Menu','create'=>'/Administrator/Item/create','button'=>'Novo Item','idForm'=>'formI','tree' => $tree, 'rota' => 'showCatArt', 'edit1' => "Administrator/Item", 'edit2' => "edit"])<!-- -->
  @endforeach
@endif

{!! Form::open(array('method'=>'DELETE', 'id'=>'formItem')) !!}
{!! Form::close() !!}



@endsection

@section('script_bottom')

<!-- Jquery Nestable -->
<!-- <script src="{{URL::to('/')}}/plugins/nestable/jquery.nestable.js"></script> -->
<!-- Custom Js -->
<!-- <script src="{{URL::to('/')}}/js/material/pages/ui/sortable-nestable.js"></script> -->

@endsection
