@extends('Administrator.admin')

@section('stylesheet')
 <link href="{{URL('/')}}/plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />

 <style>
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

@section('script')

@endsection


@section('title_content')
  @if(isset($documento)) Editar Documento @else Novo Documento @endif 
@endsection

@section('menu_content')

@endsection

@section('content')

@if(isset($documento->id) && $documento->id)
  {!! Form::model($documento, array('route' => array('Documento.update', $documento->id), 'method' => 'PUT','files'=>true)) !!}
@else
  {!! Form::open(array('route' => 'Documento.store','files'=>true)) !!} 
@endif

@php
  if(isset($documento)){
    $required_val = false;
  }else{
    $required_val = true;
  }
@endphp

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
                {{ Form::label('textopt', "Descrição (Português):") }}
                {{ Form::textarea('textopt', $content['conteudoPT'] ?? '', array('class'=>'form-control editor','required'=>'', 'rows'=>'10' ,'placeholder'=>'Breve descrição do documento (PT)...', 'id'=>'conteudo2')) }}
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
                {{ Form::label('textoen', "Descrição (Inglês):") }}
                {{ Form::textarea('textoen', $content['conteudoEN'] ?? '', array('class'=>'form-control editor','required'=>'', 'rows'=>'10', 'placeholder'=>'Breve descrição do documento (EN)...', 'id'=>'conteudo1')) }}
              </div>
            </div>
          </fieldset>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-4">
    <fieldset>
      <legend>Informações</legend>
      
      <div class="form-group">
        <div class="form-line">
          {{ Form::label('filePT','FicheiroPT:') }}
          {{ Form::file('filePT',  array('class'=>'form-control ', 'required'=> $required_val, 'title'=>'')) }}
          @if(isset($documento->url))
            @php
              $urls = json_decode($documento->url, true);
              $filePT = $urls['pt'] ?? null;
            @endphp
            @if($filePT)
              <div style="margin-top: 8px; padding: 10px; background-color: #f8f9fa; border-left: 3px solid #17a2b8; border-radius: 4px;">
                <small>
                  <strong class="text-info">Ficheiro atual:</strong> 
                  <span class="text-muted">{{ $filePT }}</span>
                  <br>
                  <a href="{{ URL::to('/') }}/documento/opendoc/{{ $filePT }}" target="_blank" class="btn btn-info btn-xs waves-effect" style="margin-top: 5px; padding: 3px 10px;">
                    <i class="material-icons" style="font-size: 16px; vertical-align: middle; margin-right: 3px;">visibility</i>
                    Ver Documento
                  </a>
                  <span class="text-muted" style="margin-left: 10px;">
                    <i class="material-icons" style="font-size: 14px; vertical-align: middle;">description</i>
                    {{ App\Helpers\Helpers::getDocumentFileSize($documento->url, 'pt') }}
                  </span>
                </small>
              </div>
            @endif
          @endif
        </div>
      </div>
      
      <div class="form-group">
        <div class="form-line">
          {{ Form::label('fileEN','FicheiroEN:') }}
          {{ Form::file('fileEN',  array('class'=>'form-control ', 'required'=> $required_val ,'title'=>'')) }}
          @if(isset($documento->url))
            @php
              $urls = json_decode($documento->url, true);
              $fileEN = $urls['en'] ?? null;
            @endphp
            @if($fileEN)
              <div style="margin-top: 8px; padding: 10px; background-color: #f8f9fa; border-left: 3px solid #17a2b8; border-radius: 4px;">
                <small>
                  <strong class="text-info">Ficheiro atual:</strong> 
                  <span class="text-muted">{{ $fileEN }}</span>
                  <br>
                  <a href="{{ URL::to('/') }}/documento/opendoc/{{ $fileEN }}" target="_blank" class="btn btn-info btn-xs waves-effect" style="margin-top: 5px; padding: 3px 10px;">
                    <i class="material-icons" style="font-size: 16px; vertical-align: middle; margin-right: 3px;">visibility</i>
                    Ver Documento
                  </a>
                  <span class="text-muted" style="margin-left: 10px;">
                    <i class="material-icons" style="font-size: 14px; vertical-align: middle;">description</i>
                    {{ App\Helpers\Helpers::getDocumentFileSize($documento->url, 'en') }}
                  </span>
                </small>
              </div>
            @endif
          @endif
        </div>
      </div>

      <div class="form-group">
        <div class="form-line">
          {{ Form::label('ativado','Estado:') }}
          <div class="switch">
            <label>Despublicado<input type="checkbox" name="ativado" @if(isset($documento)) @if($documento->ativado) checked @endif @else checked @endif><span class="lever"></span>Publicado</label>
          </div>
        </div>
      </div>

      <div class="form-group">
        <div class="form-line">
          {{ Form::label('destaque','Destaque:') }}
          <div class="switch">
            <label>Não<input type="checkbox" name="destaque" @if(isset($documento)) @if($documento->destaque) checked @endif @else checked @endif ><span class="lever"></span>Sim</label>
          </div>
        </div>
      </div>

      <div class="form-group">
          <div class="form-line">
              {{ Form::label('data_criacao','Data de Criação:') }}
              {{ Form::date( 'data_criacao', $documento->data_criacao ?? Carbon\Carbon::now(),  ['id' => 'data_criacao', 'class'=>"form-control "])}}
              <div class="error-data-criacao"></div>
          </div>
      </div>

      <div class="form-group">
        <div class="form-line">
          {{ Form::label('publicar','Data Publicaçāo:') }}
          {{ Form::date( 'publicar', $documento->publicar ?? Carbon\Carbon::now(),  ['id' => 'idpublicar', 'class'=>'form-control'])}}
          <div class="error-publicar"></div>
        </div>
      </div>

      <div class="form-group">
        <div class="form-line">
          {{ Form::label('despublicar','Data Remoçāo:') }}
          {{ Form::date('despublicar', $documento->despublicar ?? Carbon\Carbon::parse(config('custom.LAST_DATA_FIELD')),  ['id' => 'iddespublicar', 'class'=>'form-control'])}}
          <div class="error-despublicar"></div>
        </div>
      </div>

      <div class="form-group">
        <div class="form-line">
          {{ Form::label('idCategoria','Categoria:') }}
          {{ Form::select('idCategoria',$cat, [$documento->categorias->id ?? null], array('class'=>'form-control show-tick','data-live-search'=>'true','required'=>'required','data-toggle'=>'tooltip', 'data-placement'=>'top','title'=>'Selecione a pasta.')) }}
        </div>
      </div>

      <div class="form-group">
        <div class="form-line">
          {{ Form::label('tag','Tags:') }}
          {{ Form::select('tag[]',$tag, $documento->tags ?? null, array('class'=>'form-control show-tick', 'multiple', 'data-live-search'=>'true','data-toggle'=>'tooltip')) }}
        </div>
      </div>

      @if(isset($documento->id) && $documento->id)
      <div class="form-group">
        <div class="form-line">
          {{ Form::label('slug_pt','URL Slug (PT):') }}
          @php
            $slugData = json_decode($documento->slug ?? '{}', true);
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

    <fieldset style="margin-top:15px;color:red;">
      <legend>Dicas</legend>
      <div class="form-group">
        <div class="form-line">
          Certifique-se o campo "Tag" e "Pasta" estão correctos.
          <br>Utilize uma combinação de tags para definir o assunto relacionado ao documento.
        </div>
      </div>
    </fieldset>
  </div>

{!! Form::close() !!}


@endsection

@section('script_bottom')

<script src="{{ URL::to('/') }}/plugins/multi-select/js/jquery.multi-select.js"></script>

<script src="{{URL::to('/')}}/plugins/tinymce/tinymce.js"></script>
<script src="{{URL::to('/')}}/js/material/pages/forms/editors.js"></script>

<!-- Jquery Validation Plugin Css -->

<script src="{{ URL::to('/') }}/plugins/bootstrap-select/js/bootstrap-select.js"></script>


@endsection
