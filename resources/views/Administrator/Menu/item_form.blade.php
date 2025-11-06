@extends('Administrator.admin')

@section('menu_content')

@endsection

@section('title_content')
    @if(isset($id)) Editar Item @else Novo Item @endif 
@endsection


@section('stylesheet')
<link href="{{URL('/')}}/plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />
<!-- GALERIA -->
<link href="{{URL::to('/')}}/plugins/nestable/jquery-nestable.css" rel="stylesheet" />
<link href="{{URL('/')}}/plugins/dropzone/dropzone.css" rel="stylesheet">
<link href="{{URL('/')}}/plugins/light-gallery/css/lightgallery.css" rel="stylesheet">
<!-- GALERIA -->

@endsection


@section('content')

    @if(isset($id->id) && $id->id)
        {!! Form::model($id, array('route' => array('Item.update', $id->id), 'method' => 'PUT')) !!}
    @else
        {!! Form::open(array('route' => 'Item.store')) !!}
    @endif



<div class=" pull-right topBottom ">

 {{ Form::submit('Guardar', array('class'=>'btn btn-success btn-lg btn-block')) }}
</div>

<div class="content">

  <div class="col-md-8">
   <fieldset>
    <div class="form-group">
      <div class="form-line">
        {{ Form::label('tituloEN','Titulo (EN):') }}
        {{ Form::text('tituloEN', $content['tituloEN'] ?? '',  array('class'=>'form-control','required'=>'', 'maxlength'=>'200')) }}
      </div>
    </div>
  </fieldset>

  <fieldset>
    <div class="form-group">
      <div class="form-line">
       {{ Form::label('tituloPT','Titulo (PT):') }}
       {{ Form::text('tituloPT', $content['tituloPT'] ?? '',  array('class'=>'form-control','required'=>'', 'maxlength'=>'200')) }}
     </div>
   </div>
 </fieldset>

 

<div class="form-group">
  <div class="form-line">
   {{ Form::label('tag','Tags:', ['class' => 'form-spacing-top']) }}
   {{ Form::select('tag[]',$tag, $id->tags ?? null, array('class'=>'select2-multi form-control','data-live-search'=>'true','multiple'=>'multiple','data-toggle'=>'tooltip','data-placement'=>'top','title'=>'tags para o item.')) }}
 </div>
</div>


<fieldset style="margin-top:15px;">
  <div class="form-group">
    <div class="form-line">
     {{ Form::label('url','Link:') }}
     {{ Form::text('url', null, array('class'=>'form-control','required'=>'', 'maxlength'=>'200', 'id' => 'idLink')) }}
   </div>
 </div>
</fieldset>
{{-- {{ Form::hidden('url', null, array('class'=>'form-control','required'=>'', 'maxlength'=>'200', 'id' => 'idLink')) }} --}}

      <div class="ArtHide" style="margin-top:15px;">
        <fieldset style="margin-top:15px;">
          <div class="form-group">
            <div class="form-line">
              {{ Form::label('artlink','Link Artigo:', ['class' => 'form-spacing-top']) }}
              {{ Form::select('artlink', $art, null, ['class'=>'form-control','data-live-search'=>'true','data-toggle'=>'tooltip','data-placement'=>'top','title'=>'Artigo Link.', 'id' => 'idArtLink']) }}
            </div>
          </div>
        </fieldset>
      </div>

    <div class="pastaArtHide" style="margin-top:15px;">
      <fieldset style="margin-top:15px;">
        <div class="form-group">
          <div class="form-line">
            {{ Form::label('artpasta','Link Pasta Artigo:', ['class' => 'form-spacing-top']) }}
            {{ Form::select('artpasta', $artPasta, null, ['class'=>'form-control','data-live-search'=>'true','data-toggle'=>'tooltip','data-placement'=>'top','title'=>'Link Pasta Artigo.', 'id' => 'idArtPasta']) }}
          </div>
        </div>
      </fieldset>
    </div>

    <div class="docHide" style="margin-top:15px;">
     <fieldset style="margin-top:15px;">
        <div class="form-group">
          <div class="form-line">
            {{ Form::label('doclink','Link Documento:', ['class' => 'form-spacing-top']) }}
          {{ Form::select('doclink', $doc, null, ['class'=>'form-control','data-live-search'=>'true','data-toggle'=>'tooltip','data-placement'=>'top','title'=>'Documento Link.', 'id' => 'idDocLink']) }}
          </div>
        </div>
     </fieldset>
    </div>

    <div class="pastDocHide" style="margin-top:15px;">
     <fieldset style="margin-top:15px;">
        <div class="form-group">
          <div class="form-line">
            {{ Form::label('docpasta','Link Pasta Documento:', ['class' => 'form-spacing-top']) }}
            {{ Form::select('docpasta', $docPasta, null, ['class'=>'form-control','data-live-search'=>'true','data-toggle'=>'tooltip','data-placement'=>'top','title'=>'Link Pasta Documento.', 'id' => 'idPastaDoc']) }}
          </div>
        </div>
      </fieldset>
    </div>

    <fieldset style="margin-top:15px;">
      <div class="form-group">

        <div class="dropdown col-md-3">
          <button class="btn btn-primary" type="button" id="ArtLink" aria-haspopup="true" aria-expanded="true">Artigo Link:</button> 
        </div>

        <div class="dropdown col-md-3">
          <button class="btn btn-primary" type="button" id="ArtPastaLink"  aria-haspopup="true" aria-expanded="true">Link Pasta Artigo:</button>
        </div>

        <div class="dropdown col-md-3">
          <button class="btn btn-primary" type="button" id="DocLink" aria-haspopup="true" aria-expanded="true">Documento Link:</button>
        </div>

        <div class="dropdown col-md-3">
          <button class="btn btn-primary dropdown-toggle" type="button" id="DocPastaLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Link Pasta Documento:</button>
        </div>

      </div>
    </fieldset>




</div>



<div class="col-md-4">

    <div class="form-group">
      <div class="form-line">
        {{ Form::label('ativado','Estado:')}}
        <div class="switch">
          <label>Despublicado<input type="checkbox" name="ativado"  @if(isset($id)) @if($id->ativado) checked @endif @else checked @endif><span class="lever"></span>Publicado</label>
        </div>
      </div>
    </div>

    <div class="form-group">
        <div class="form-line">
             <button type="button" class="btn btn-default waves-effect m-r-20" data-toggle="modal" data-target="#largeModal">Escolher Imagem</button>
             @if(isset($id->idImagem))
                 {{ Form::hidden('idimagem',$id->idimagem, array('id' => 'idimagem', 'name'=>'idimagem')) }}
                {{ Form::text('Titulo Imagem', $id->imagems->titulo, array('id' => 'tituloIMG','class'=>'form-control',  'placeholder'=>'Selecione uma imagem para ao artigo.', 'readonly'=>'')) }}
            @else
              {{ Form::hidden('idimagem',null, array('id' => 'idimagem', 'name'=>'idimagem')) }}
              {{ Form::text('Titulo Imagem', null, array('id' => 'tituloIMG','class'=>'form-control',  'placeholder'=>'Selecione uma imagem para ao artigo.', 'readonly'=>'')) }}
            @endif
        </div>
    </div>

  <div class="form-group">
    <div class="form-line">
      {{ Form::label('idTipo','Menu:') }}
      {{ Form::select('idTipo', $menus , $id->idTipo ?? null,array('class'=>'form-control','required'=>'','data-toggle'=>'tooltip','data-placement'=>'top','title'=>'Selecione a categoria onde o artigo será inserido.','id'=>'idMenu')) }}
    </div>
  </div>

{{-- {{dd($sub_menu)}} --}}
  <div class="form-group">
    <div class="form-line">
      {{ Form::label('parent','Aninhado em:') }}
      {{ Form::select('parent', [], $id->parent ?? null, array('class'=>'form-control','data-live-search'=>'true','required'=>'','data-toggle'=>'tooltip','data-placement'=>'top','placeholder'=>'Escolha um item de menu.','id'=>'idItems')) }}
    </div>
  </div>


<fieldset style="margin-top:15px;">
 <legend>Dicas</legend>

</fieldset>
</div>
<div class="col-md-8" style="margin-top:15px;">

</div>

</div>

{!! Form::close() !!}


 @include("Administrator.partials._galeria")

@endsection


@section('script_bottom')
<script src="{{URL('/')}}/plugins/multi-select/js/jquery.multi-select.js"></script>
<!-- <script src="{{URL('/')}}/plugins/bootstrap-tagsinput/bootstrap-tagsinput.js"></script> -->
<script src="{{ URL::to('/') }}/plugins/bootstrap-select/js/bootstrap-select.js"></script>
<script src="{{URL('/')}}/js/item.js"></script>


<!-- MODAL GALERIA -->
<script src="{{URL('/')}}/js/galeria.js"></script>
<!-- ENd MODAL GALERIA -->
 @endsection

