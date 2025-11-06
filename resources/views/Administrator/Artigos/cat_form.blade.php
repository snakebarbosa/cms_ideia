@extends('Administrator.admin')

@section('stylesheet')

<link href="{{URL('/')}}/plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />
@endsection

@section('script')

@endsection

@section('menu_content')

@endsection

@section('title_content')
 @if(isset($categoria)) Editar Categoria Artigo @else Novo Categoria Artigo @endif 
@endsection

@section('content')

  @if(isset($categoria->id) && $categoria->id)
    {!! Form::model($categoria, array('route' => array('Categoria.update', $categoria->id), 'method' => 'PUT')) !!}
  @else
    {!! Form::open(array('route' => 'Categoria.store')) !!} 
  @endif

  <div class="pull-right topBottom">
      {{ Form::submit('Guardar', array('class'=>'btn btn-success btn-lg btn-block')) }}
  </div>

<div class="content">
  <div class="col-md-8">

   <fieldset style="margin-top:15px;">
     <div class="form-group">
         <div class="form-line">
        {{ Form::hidden('type', $type, array('id' => 'type', 'name'=>'type')) }}
        {{ Form::label('tituloPT','Titulo (PT):') }}
        {{ Form::text('tituloPT', $content['tituloPT'] ?? '', array('class'=>'form-control','required'=>'', 'maxlength'=>'200')) }}
       </div>
     </div>
     <div class="form-group">
       <div class="form-line">
      
        {{ Form::label('tituloEN','Titulo (EN):') }}
        {{ Form::text('tituloEN',$content['tituloEN'] ?? '', array('class'=>'form-control','required'=>'', 'maxlength'=>'200')) }}
       </div>
    </div>


      <div class="form-group">
        <div class="form-line switch">
          {{ Form::label('ativado','Estado:') }}
          <div class="switch">
            <label>Despublicado<input type="checkbox" name="ativado" @if(isset($categoria)) @if($categoria->ativado) checked @endif @else checked @endif><span class="lever"></span>Publicado</label>
          </div>
        </div>
      </div>

      <div class="form-group">
        <div class="form-line">
          {{ Form::label('categoria','Aninhado em:') }}
          {{ Form::select('parent', $items , [$categoria->parent ?? null], array('class'=>'form-control','required'=>'','data-live-search'=>'true','data-toggle'=>'tooltip','data-placement'=>'top','title'=>'Selecione a categoria onde o artigo será inserido.')) }}
        </div>
      </div>

     <div class="form-group">
      <div class="form-line">


         {{ Form::label('tag','Tags:') }}
        {{ Form::select('tag[]',$tag, $categoria->tags ?? null, array('id'=>'tagselect','class'=>'form-control show-tick', 'multiple' ,'data-live-search'=>'true','data-toggle'=>'tooltip')) }}
      </div>
    </div>

</fieldset>
</div>
<div class="col-md-4">


  <fieldset style="margin-top:15px;">
   <legend>Dicas</legend>



 </fieldset>
</div>
<div class="col-md-8" style="margin-top:15px;">
  {{-- {{ Form::submit('Create Post', array('class'=>'btn btn-success btn-lg btn-block')) }}  --}}
</div>

</div>
  {!! Form::close() !!}
@endsection


@section('script_bottom')
<script src="{{URL('/')}}/plugins/multi-select/js/jquery.multi-select.js"></script>
<script src="{{URL('/')}}/plugins/bootstrap-tagsinput/bootstrap-tagsinput.js"></script>
<script src="{{ URL::to('/') }}/plugins/bootstrap-select/js/bootstrap-select.js"></script>


{{-- <script>
    $("#tagselect").val({{$categoria->tags()->allRelatedIds()}});
</script> --}}
@endsection

