@extends('Administrator.admin')

@section('stylesheet')
  <link href="{{URL('/')}}/plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />
@endsection

@section('script')

@endsection

@section('menu_content')

@endsection

@section('title_content')
  @if(isset($tag)) Editar Tag @else Novo Tag @endif 
@endsection

@section('content')

  @if(isset($tag->id) && $tag->id)
    {!! Form::model($tag, array('route' => array('Tag.update', $tag->id), 'method' => 'PUT')) !!}
  @else
    {!! Form::open(array('route' => 'Tag.store')) !!} 
  @endif

  <div class=" pull-right topBottom">
    {{ Form::submit('Guardar', array('class'=>'btn btn-success btn-lg btn-block')) }}
  </div>

 {{--  ***********ADD TIPO*************** --}}
 <div class="content">
  {{-- {!! Form::open(array('route' => 'Categoria.store','data-parsley-validate'=>'')) !!} --}}
  <div class="col-md-8">

   <fieldset style="margin-top:15px;">

    <div class="form-group">
      <div class="form-line">
        {{ Form::label('name','Nome:') }}
        {{ Form::text('name', $tag->name ?? '', array('class'=>'form-control', 'required'=>'required',)) }}
      </div>
    </div>


    <div class="form-group">
      <div class="form-line">
        {{ Form::label('tag','Tags:') }}
        {{ Form::select('tag[]',$tags, null, array('class'=>'form-control show-tick', 'multiple', 'data-live-search'=>'true','data-toggle'=>'tooltip')) }}
      </div>
    </div>
    
  </fieldset>
</div>


<div class="col-md-4">
 <fieldset style="margin-top:15px;color:red;">
  <legend>Dicas</legend>
  {{-- Utilize apenas uma palavra para criar o tag.
  <br>O "Tag" serve para organizar a informação no website, portanto defina um tag para um conceito geral. Ex: Legislação --}}
</fieldset>
</div>

</div>
{!! Form::close() !!}

{{--  ***********ADD TIPO*************** --}}
@endsection


@section('script_bottom')
<script src="{{URL('/')}}/plugins/multi-select/js/jquery.multi-select.js"></script>
<script src="{{ URL::to('/') }}/plugins/bootstrap-select/js/bootstrap-select.js"></script>

<script>
 $(document).ready(function(){
  
 });


</script>
@endsection
