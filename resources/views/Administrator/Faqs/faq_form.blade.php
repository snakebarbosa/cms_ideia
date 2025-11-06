@extends('Administrator.admin')

@section('stylesheet')
  <style>
  </style>
@endsection

@section('script')
  <link href="{{URL('/')}}/plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />
  <link href="{{URL('/')}}/plugins/multi-select/css/multi-select.css" rel="stylesheet">
@endsection


@section('title_content')
  @if(isset($faq)) Editar FAQ @else Novo FAQ @endif
@endsection

@section('menu_content')
@endsection

@section('content')
    @if(isset($faq->id) && $faq->id)
        {!! Form::model($faq, array('route' => array('Faq.update', $faq->id), 'method' => 'PUT')) !!}
    @else
        {!! Form::open(array('route' => 'Faq.store')) !!} 
    @endif

    <div class=" pull-right topBottom">
    {{ Form::submit('Guardar', array('class'=>'btn btn-success btn-lg btn-block ')) }}
    </div>

    <div class="content">
        <div class="col-md-8">
            <fieldset>
            <legend>Conteúdos EN</legend>
            <div class="form-group">
                <div class="form-line">
                {{ Form::label('tituloEN','Pergunta (EN):') }}
                {{ Form::text('tituloEN', $content['tituloEN'] ?? '', array('class'=>'form-control','required'=>'', 'maxlength'=>'200')) }}
                </div>
            </div>
            <br>
            <div class="form-group">
                <div class="form-line">
                {{ Form::label('texto', "Resposta (Inglês):") }}
                {{ Form::textarea('textoen', $content['conteudoEN'] ?? '', array('class'=>'form-control editor','required'=>'', 'rows'=>'10', 'placeholder'=>'Escreva a notícia aqui (EN)...', 'id'=>'conteudo1')) }}
                </div>
            </div>
            </fieldset>
            <fieldset>
                <legend>Conteúdos PT</legend>
                <div class="form-group">
                    <div class="form-line">
                        {{ Form::label('tituloPT','Pergunta (PT):') }}
                        {{ Form::text('tituloPT', $content['tituloPT'] ?? '', array('class'=>'form-control','required'=>'', 'maxlength'=>'200')) }}
                    </div>
                </div>
                <br>
                <div class="form-group">
                    <div class="form-line">
                        {{ Form::label('texto', "Resposta (Português):") }}
                        {{ Form::textarea('textopt', $content['conteudoPT'] ?? '', array('class'=>'form-control editor','required'=>'', 'rows'=>'10' ,'placeholder'=>'Escreva a notícia aqui (EN)...', 'id'=>'conteudo2')) }}
                    </div>
                </div>
            </fieldset>
        </div>

        <div class="col-md-4">
            <fieldset>
                <legend>Informações</legend>
                <div class="form-group">
                    <div class="form-line">
                    {{ Form::label('ativado','Estado:') }}
                    <div class="switch">
                        <label>Despublicado<input type="checkbox" name="ativado" @if(isset($faq)) @if($faq->ativado) checked @endif @else checked @endif><span class="lever"></span>Publicado</label>
                    </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="form-line">
                    {{ Form::label('destaque','Destaque:') }}
                    <div class="switch">
                        <label>Não<input type="checkbox" name="destaque" @if(isset($faq)) @if($faq->destaque) checked @endif @else checked @endif><span class="lever"></span>Sim</label>
                    </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="form-line">
                    {{ Form::label('idCategoria','Categoria:') }}
                    {{ Form::select('idCategoria', $cat, [$faq->categorias->id ?? null] , array('class'=>'form-control show-tick','data-live-search'=>'true','required'=>'','data-toggle'=>'tooltip')) }}
                    </div>
                </div>
                <div class="form-group">
                    <div class="form-line">
                    {{ Form::label('tag','Tags:') }}
                    {{ Form::select('tag[]',$tag, $faq->tags ?? null, array('class'=>'form-control show-tick', 'data-live-search'=>'true', 'multiple','data-toggle'=>'tooltip')) }}
                    </div>
                </div>
                <div class="form-group">
                    <div class="form-line">
                    {{ Form::label('keyword','Palavra-chave:') }}
                    {{ Form::text('keyword', $faq['keyword'] ?? '', array('class'=>'form-control', 'maxlength'=>'250', 'placeholder'=>'Separe as palavras-chave com vírgula.')) }}
                    </div>
                </div>
            </fieldset>
            <fieldset style="margin-top:15px;">
                <legend>Dicas</legend>
            </fieldset>
        </div>
    </div>

    {!! Form::close() !!}

@endsection

@section('script_bottom')
  <script src="{{URL('/')}}/plugins/multi-select/js/jquery.multi-select.js"></script>
  <script src="{{URL('/')}}/plugins/bootstrap-tagsinput/bootstrap-tagsinput.js"></script>
  <script src="{{URL('/')}}/plugins/tinymce/tinymce.js"></script>
  <script src="{{URL('/')}}/js/material/pages/forms/editors.js"></script>
  {{-- <script>tinymce.init({selector: 'textarea'})</script> --}}
  <!-- Jquery Validation Plugin Css -->
  <script src="{{URL('/')}}/plugins/jquery-validation/jquery.validate.js"></script>
  <script src="{{ URL::to('/') }}/plugins/bootstrap-select/js/bootstrap-select.js"></script>
@endsection
