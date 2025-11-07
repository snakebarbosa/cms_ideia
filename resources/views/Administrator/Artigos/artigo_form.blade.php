@extends('Administrator.admin')

@section('stylesheet')
    <link href="{{URL('/')}}/plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />
    <!-- GALERIA -->
    <link href="{{URL::to('/')}}/plugins/nestable/jquery-nestable.css" rel="stylesheet" />
    <link href="{{URL('/')}}/plugins/dropzone/dropzone.css" rel="stylesheet">
    <link href="{{URL('/')}}/plugins/light-gallery/css/lightgallery.css" rel="stylesheet">
    <!-- GALERIA -->
    <style>
        #documentosList .list-group-item {
            padding: 10px 15px;
            margin-bottom: 5px;
            border: 1px solid #ddd;
            border-radius: 3px;
            background-color: #f9f9f9;
            transition: background-color 0.2s;
        }
        #documentosList .list-group-item:hover {
            background-color: #f0f0f0;
        }
        #documentosList .doc-title {
            display: inline-block;
            margin-left: 8px;
            vertical-align: middle;
            max-width: calc(100% - 100px);
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        #documentosList .remove-doc {
            margin-top: -3px;
            padding: 2px 8px;
        }
        #documentosList .alert {
            margin-bottom: 0;
            padding: 12px 15px;
        }

        /* Language Tabs Styling */
        .nav-tabs {
            border-bottom: 2px solid #00bcd4;
            margin-bottom: 0;
        }

        .nav-tabs > li > a {
            color: #666;
            font-weight: 500;
            border-radius: 4px 4px 0 0;
            transition: all 0.3s ease;
        }

        .nav-tabs > li > a:hover {
            background-color: #f5f5f5;
            border-color: #ddd #ddd transparent;
            color: #00bcd4;
        }

        .nav-tabs > li.active > a,
        .nav-tabs > li.active > a:hover,
        .nav-tabs > li.active > a:focus {
            background-color: #00bcd4;
            color: white;
            border-color: #00bcd4 #00bcd4 transparent;
            font-weight: 600;
        }

        .nav-tabs > li > a .material-icons {
            font-size: 18px;
            vertical-align: middle;
            margin-right: 5px;
        }

        .tab-content {
            background-color: #fff;
            border: none;
            padding: 20px;
            border-radius: 0 0 4px 4px;
            min-height: 400px;
        }

        .tab-pane {
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endsection

@section('title_content')
    @if(isset($artigo)) Editar Artigo @else Novo Artigo @endif 
@endsection


@section('content')

  @if(isset($artigo->id) && $artigo->id)
    {!! Form::model($artigo, array('route' => array('Artigo.update', $artigo->id), 'method' => 'PUT','files'=>true)) !!}
  @else
    {!! Form::open(array('route' => 'Artigo.store','files'=>true)) !!} 
  @endif

  <div class=" pull-right topBottom">
    {{ Form::submit('Guardar', array('class'=>'btn btn-success btn-lg btn-block ')) }}
  </div>  

  <div class="content">
    <div class="col-md-8">
      <!-- Language Tabs -->
      <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active">
          <a href="#tab-pt" aria-controls="tab-pt" role="tab" data-toggle="tab">
            <i class="material-icons" style="vertical-align: middle; font-size: 18px;">flag</i>
            Português
          </a>
        </li>
        <li role="presentation">
          <a href="#tab-en" aria-controls="tab-en" role="tab" data-toggle="tab">
            <i class="material-icons" style="vertical-align: middle; font-size: 18px;">flag</i>
            English
          </a>
        </li>
      </ul>

      <!-- Tab Content -->
      <div class="tab-content">
        <!-- Portuguese Tab -->
        <div role="tabpanel" class="tab-pane active" id="tab-pt">
          <fieldset>
            <div class="form-group">
              <div class="form-line">
                {{ Form::label('tituloPT','Titulo (PT):') }}
                {{ Form::text('tituloPT', $content['tituloPT'] ?? '', array('class'=>'form-control','required'=>'', 'maxlength'=>'200')) }}
              </div>
            </div>

            <div class="form-group">
              <div class="form-line">
                {{ Form::label('textopt', "Artigo (Português):") }}
                {{ Form::textarea('textopt', $content['conteudoPT'] ?? '', array('class'=>'form-control editor','required'=>'', 'rows'=>'10' ,'placeholder'=>'Escreva a notícia aqui (PT)...', 'id'=>'conteudo2')) }}
              </div>
            </div>
          </fieldset>
        </div>

        <!-- English Tab -->
        <div role="tabpanel" class="tab-pane" id="tab-en">
          <fieldset>
            <div class="form-group">
              <div class="form-line">
                {{ Form::label('tituloEN','Titulo (EN):') }}
                {{ Form::text('tituloEN', $content['tituloEN'] ?? '', array('class'=>'form-control','required'=>'', 'maxlength'=>'200')) }}
              </div>
            </div>

            <div class="form-group">
              <div class="form-line">
                {{ Form::label('textoen', "Artigo (Inglês):") }}
                {{ Form::textarea('textoen', $content['conteudoEN'] ?? '', array('class'=>'form-control editor','required'=>'', 'rows'=>'10', 'placeholder'=>'Escreva a notícia aqui (EN)...', 'id'=>'conteudo1')) }}
              </div>
            </div>
          </fieldset>
        </div>
      </div>
    </div> 

    <div class="col-md-4">
      <fieldset>
        <legend>Informações</legend>

        <div class="form-group">
          <div class="form-line">
            {{ Form::label('imagem','Imagem do Artigo:') }}
            <div class="row">
              <div class="col-md-8">
                @if(isset($artigo->idImagem)) 
                    {{ Form::hidden('idimagem', $artigo->imagems->id, array('id' => 'idimagem', 'name'=>'idimagem')) }} 
                    {{ Form::text('Titulo Imagem', $artigo->imagems->titulo, array('id' => 'tituloIMG','class'=>'form-control',  'placeholder'=>'Selecione uma imagem para ao artigo.', 'readonly'=>'')) }}
                @else
                  {{ Form::hidden('idimagem', null, array('id' => 'idimagem', 'name'=>'idimagem')) }}
                  {{ Form::text('Titulo Imagem', null, array('id' => 'tituloIMG','class'=>'form-control',  'placeholder'=>'Selecione uma imagem para ao artigo.', 'readonly'=>'')) }}
                @endif
              </div>
              <div class="col-md-4">
                <button type="button" class="btn btn-primary waves-effect" data-toggle="modal" data-target="#largeModal">
                  @if(isset($artigo->idImagem)) 
                    Alterar Imagem
                  @else
                    Escolher Imagem
                  @endif
                </button>
                @if(isset($artigo->idImagem))
                  <button type="button" class="btn btn-warning waves-effect m-l-5" onclick="removeImage()">Remover</button>
                @endif
              </div>
            </div>
            @if(isset($artigo->idImagem))
              <div class="image-preview m-t-10">
                <img src="{{ URL::to('/') }}/images/{{ $artigo->imagems->url }}" alt="{{ $artigo->imagems->titulo }}" style="max-width: 200px; max-height: 150px;" class="img-thumbnail">
                <p class="text-muted"><small>Imagem atual do artigo</small></p>
              </div>
            @endif
          </div>
        </div>

        <div class="form-group">
          <div class="form-line">
              {{ Form::label('documentos','Documentos Anexados:') }}
              <button type="button" class="btn btn-default waves-effect m-t-10 m-b-10" data-toggle="modal" data-target="#addDocs">
                <i class="material-icons">attach_file</i> Anexar Documentos
              </button>
              {{ Form::hidden('iddocumentoartigo', $documentoIds ?? null, array('id' => 'iddocumentoartigo', 'name'=>'iddocumentoartigo[]')) }}
              {{ Form::hidden('tituloDocumento', $documento ?? null, array('id' => 'tituloDocumento')) }}
              
              <div id="documentosList" class="m-t-10">
                @if(isset($artigo) && $artigo->anexos->count() > 0)
                  <ul class="list-group">
                    @foreach($artigo->anexos as $anexo)
                      <li class="list-group-item" data-doc-id="{{ $anexo->id }}">
                        <i class="material-icons" style="vertical-align: middle; font-size: 18px;">description</i>
                        <span class="doc-title">{{ $anexo->alias ?? $anexo->nome ?? 'Documento sem título' }}</span>
                        <button type="button" class="btn btn-xs btn-danger pull-right remove-doc" onclick="removeDocument({{ $anexo->id }})">
                          <i class="material-icons" style="font-size: 14px;">close</i>
                        </button>
                      </li>
                    @endforeach
                  </ul>
                @else
                  <div class="alert alert-info">
                    <i class="material-icons" style="vertical-align: middle;">info</i>
                    Nenhum documento anexado. Clique no botão acima para adicionar.
                  </div>
                @endif
              </div>
          </div>
        </div>

        <div class="form-group">
          <div class="form-line">
            {{ Form::label('ativado','Estado:')}}
            <div class="switch">
              <label>Despublicado<input type="checkbox" name="ativado" @if(isset($artigo)) @if($artigo->ativado) checked @endif @else checked @endif><span class="lever"></span>Publicado</label>
            </div>
          </div>
        </div>

        <div class="form-group">
          <div class="form-line">
            {{ Form::label('destaque','Destaque:') }}
            <div class="switch">
              <label>Não<input type="checkbox" name="destaque" @if(isset($artigo)) @if($artigo->destaque) checked @endif @else checked @endif><span class="lever"></span>Sim</label>
            </div>
          </div>
        </div>

        <div class="form-group">
            <div class="form-line">
              {{ Form::label('data_criacao','Data de Criação:') }}
              {{ Form::date( 'data_criacao', $artigo->data_criacao ?? Carbon\Carbon::now(),  ['id' => 'data_criacao', 'class'=>"form-control "])}}
              <div class="error-data-criacao"></div>
            </div>
        </div>

        <div class="form-group">
          <div class="form-line">
              {{ Form::label('publicar','Data Publicaçāo:') }}
              {{ Form::date( 'publicar', $artigo->publicar ?? Carbon\Carbon::now(),  ['id' => 'idpublicar', 'class'=>'form-control'])}}
              <div class="error-publicar"></div>
          </div>
        </div>

        <div class="form-group">
          <div class="form-line">
              {{ Form::label('despublicar','Data Remoçāo:') }}
              {{ Form::date('despublicar', $artigo->despublicar ?? Carbon\Carbon::parse(config('custom.LAST_DATA_FIELD')),  ['id' => 'iddespublicar', 'class'=>'form-control'])}}
              <div class="error-despublicar"></div>
          </div>
        </div>

        <div class="form-group">
          <div class="form-line"> 
            {{ Form::label('idCategoria','Categoria:') }}
            {{ Form::select('idCategoria',$cat, [$artigo->categorias->id ?? null] ,array('class'=>'form-control show-tick','data-live-search'=>'true','required'=>'required','data-toggle'=>'tooltip')) }}
          </div>
        </div>

        <div class="form-group">
          <div class="form-line">
            {{ Form::label('tag','Tags:') }}
            {{ Form::select('tag[]',$tag, $artigo->tags ?? null, array('id'=>'tagselect','class'=>'form-control show-tick', 'data-live-search'=>'true','data-toggle'=>'tooltip', 'multiple')) }}
          </div>
        </div>

        <div class="form-group">
          <div class="form-line">
            {{ Form::label('keyword','Palavra-chave:') }}
            {{ Form::text('keyword', $artigo->keyword ?? '', array('class'=>'form-control', 'maxlength'=>'250', 'placeholder'=>'Separe as palavras-chave com vírgula.')) }}
          </div>
        </div>

        @if(isset($artigo->id) && $artigo->id)
        <div class="form-group">
          <div class="form-line">
            {{ Form::label('slug_pt','URL Slug (PT):') }}
            @php
              $slugData = json_decode($artigo->slug ?? '{}', true);
              $slugPT = $slugData['pt'] ?? '';
              $slugEN = $slugData['en'] ?? '';
            @endphp
            {{ Form::text('slug_pt', $slugPT, array('class'=>'form-control', 'maxlength'=>'250', 'placeholder'=>'URL amigável em português (deixe em branco para gerar automaticamente)')) }}
            <small class="text-muted">URL amigável para a versão em português. Deve ser único e conter apenas letras, números e hífens.</small>
            @if ($errors->has('slug_pt'))
                <div class="text-danger">{{ $errors->first('slug_pt') }}</div>
            @endif
          </div>
        </div>
        
        <div class="form-group">
          <div class="form-line">
            {{ Form::label('slug_en','URL Slug (EN):') }}
            {{ Form::text('slug_en', $slugEN, array('class'=>'form-control', 'maxlength'=>'250', 'placeholder'=>'URL amigável em inglês (deixe em branco para gerar automaticamente)')) }}
            <small class="text-muted">URL amigável para a versão em inglês. Deve ser único e conter apenas letras, números e hífens.</small>
            @if ($errors->has('slug_en'))
                <div class="text-danger">{{ $errors->first('slug_en') }}</div>
            @endif
          </div>
        </div>
        @endif
      </fieldset>

      <fieldset style="margin-top:15px;">
        <legend>Dicas</legend>
      </fieldset>
    </div>


  </div> 
 
  {!! Form::close() !!}

  {{-- Modal Para Anexar Documendos --}}
    <div class="modal fade" id="addDocs" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content modal-lg">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Anexar Documentos</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
              @include('Administrator.partials._docs_table_view', ['buttons' => false, 'documents' => $doc, 'pageLength' => true])
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <button type="button" id="buttonOK" class="btn btn-primary" >Anexar Documentos</button>
        </div>
      </div>
    </div>
  </div>
  {{-- FIM MODAL --}}
@endsection

@include("Administrator.partials._galeria")


@section('script_bottom')

<script src="{{URL('/')}}/plugins/multi-select/js/jquery.multi-select.js"></script>
<script src="{{URL('/')}}/plugins/tinymce/tinymce.js"></script>
<script src="{{URL('/')}}/js/material/pages/forms/editors.js"></script>
<script src="{{URL('/')}}/js/galeria.js"></script>
<script src="{{URL('/')}}/plugins/dropzone/dropzone.js"></script>
<script src="{{URL('/')}}/plugins/jquery-slimscroll/jquery.slimscroll.js"></script>
<script src="{{URL('/')}}/js/material/pages/forms/dropzone.js"></script>
<script src="{{ URL::to('/') }}/plugins/bootstrap-select/js/bootstrap-select.js"></script>

{{-- <script type="text/javascript">
  // $("#tagselect").val({{$artigo->tags()->allRelatedIds()}});
</script>  --}}

<script type="text/javascript">
function removeImage() {
    if (confirm('Tem certeza que deseja remover a imagem do artigo?')) {
        $('#idimagem').val('');
        $('#tituloIMG').val('');
        $('.image-preview').remove();
        $('.btn[onclick="removeImage()"]').remove();
        $('.btn[data-target="#largeModal"]').text('Escolher Imagem');
        $('#tituloIMG').attr('placeholder', 'Selecione uma imagem para ao artigo.');
    }
}

// Update button text and show preview when image is selected from gallery
$(document).ready(function() {
    // Override the original gallery selection function
    $("#okModalGal").off('click').on('click', function () {
        var element = $("#aniimated-thumbnials .showImage img.hover");

        if (element.attr('data-id')) {
            $('#idimagem').val(element.attr('data-id'));
            $('#tituloIMG').val(element.attr('data-title'));
            
            // Update button text
            $('.btn[data-target="#largeModal"]').text('Alterar Imagem');
            
            // Add remove button if it doesn't exist
            if (!$('.btn[onclick="removeImage()"]').length) {
                $('.btn[data-target="#largeModal"]').after('<button type="button" class="btn btn-warning waves-effect m-l-5" onclick="removeImage()">Remover</button>');
            }
            
            // Add or update image preview
            $('.image-preview').remove();
            var imageUrl = element.attr('src');
            var imageTitle = element.attr('data-title');
            var previewHtml = '<div class="image-preview m-t-10">' +
                            '<img src="' + imageUrl + '" alt="' + imageTitle + '" style="max-width: 200px; max-height: 150px;" class="img-thumbnail">' +
                            '<p class="text-muted"><small>Imagem selecionada para o artigo</small></p>' +
                            '</div>';
            $('#tituloIMG').closest('.form-line').append(previewHtml);

            $('#largeModal').modal('hide');
        } else {
            alert("Nenhuma imagem foi selecionada.");
        }
    });
});
</script>

@endsection
