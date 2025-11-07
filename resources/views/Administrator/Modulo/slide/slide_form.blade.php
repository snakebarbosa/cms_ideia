@extends('Administrator.admin')

@section('stylesheet')

<!-- GALERIA -->
<link href="{{URL::to('/')}}/plugins/nestable/jquery-nestable.css" rel="stylesheet" />
<link href="{{URL('/')}}/plugins/dropzone/dropzone.css" rel="stylesheet">
<link href="{{URL('/')}}/plugins/light-gallery/css/lightgallery.css" rel="stylesheet">
<!-- GALERIA -->

<style>
#image-preview-container {
    position: relative;
    transition: all 0.3s ease;
}

#image-preview-container:hover {
    border-color: #00bcd4;
    background-color: #f5f5f5;
}

#image-preview {
    transition: all 0.3s ease;
}

#image-preview:hover {
    transform: scale(1.02);
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}

#image-preview-placeholder {
    transition: all 0.3s ease;
}

.form-group label {
    font-weight: 500;
    color: #555;
    margin-bottom: 8px;
}

.btn-default {
    background-color: #00bcd4 !important;
    color: white !important;
    border: none;
}

.btn-default:hover {
    background-color: #0097a7 !important;
}

#remove-image-btn {
    margin-left: 10px;
    transition: all 0.3s ease;
}

#remove-image-btn:hover {
    background-color: #d32f2f !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

.btn .material-icons {
    vertical-align: middle;
    margin-right: 5px;
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
    min-height: 300px;
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
	 <!-- {{ Html::script('js/bootstrap-select.js')}} -->
@endsection

@section('menu_content')
  
@endsection

@section('title_content')
    @if(isset($slide)) Editar Slide @else Novo Slide @endif 
@endsection

@section('content')

    @if(isset($slide->id) && $slide->id)
        {!! Form::model($slide, array('route' => array('Slide.update', $slide->id), 'method' => 'PUT','data-parsley-validate'=>'')) !!}
    @else
        {!! Form::open(array('route' => 'Slide.store','data-parsley-validate'=>'')) !!}
    @endif

    <div class="topBottom pull-right">
     	{{ Form::submit('Guardar', array('class'=>'btn btn-success btn-lg btn-block')) }}
    </div>

    <div class="content">
		<div class="col-md-12">
			<div class="col-md-8">
                <fieldset style="margin-top:15px;">
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
                    <div class="tab-content" style="margin-top: 20px;">
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
                                        {{ Form::label('textopt','Descrição (PT):') }}
                                        {{ Form::textarea('textopt',$content['conteudoPT'] ?? '', array('class'=>'form-control editor','required'=>'','data-toggle'=>'tooltip','data-placement'=>'top', 'cols'=>'200', 'rows'=>'5')) }}
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
                                        {{ Form::text('tituloEN',$content['tituloEN'] ?? '', array('class'=>'form-control','required'=>'', 'maxlength'=>'200')) }}
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="form-line">
                                        {{ Form::label('textoen','Descrição (EN):') }}
                                        {{ Form::textarea('textoen',$content['conteudoEN'] ?? '', array('class'=>'form-control editor','required'=>'','data-toggle'=>'tooltip','data-placement'=>'top', 'cols'=>'200', 'rows'=>'5')) }}
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </fieldset>
            </div>

		    <div class="col-md-4">
                <fieldset style="margin-top:15px;">
                    <!-- Link Selector Component -->
                    <link-selector
                        type-label="Tipo de Link:"
                        url-field-name="url"
                        initial-url="{{ $slide->url ?? '' }}"
                        @url-changed="onUrlChanged"
                    ></link-selector>

                    <div class="form-group">
                        <div class="form-line">
                            {{ Form::label('posicao','Posição:') }}
                            {{ Form::text('posicao', $slide->posicao ?? '', array('class'=>'form-control','required'=>'')) }}
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-line">
                            {{ Form::label('ativado','Estado:') }}
                            <div class="switch">
                            <label>Despublicado<input type="checkbox" name="ativado"@if(isset($slide)) @if($slide->ativado) checked @endif @else checked @endif>
                                <span class="lever"></span>
                                Publicado
                            </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-line">
                            {{ Form::label('publicar','Data Publicaçāo:') }}
                            {{ Form::date( 'publicar', $slide->publicar ?? Carbon\Carbon::now(),  ['id' => 'idpublicar', 'class'=>'form-control'])}}
                            <div class="error-publicar"></div>
                      </div>
                    </div>
                    

                    <div class="form-group">
                      <div class="form-line">
                        {{ Form::label('despublicar','Data Remoçāo:') }}
                        {{ Form::date('despublicar', $slide->despublicar ?? Carbon\Carbon::parse(config('custom.LAST_DATA_FIELD')),  ['id' => 'iddespublicar', 'class'=>'form-control'])}}
                        <div class="error-despublicar"></div>
                      </div>
                    </div>

                    <div class="form-group">
                        <div class="form-line">
                            <button type="button" class="btn btn-default waves-effect m-r-20" data-toggle="modal" data-target="#largeModal">
                                <i class="material-icons" style="vertical-align: middle; font-size: 18px;">add_photo_alternate</i>
                                Escolher Imagem
                            </button>
                            <button type="button" id="remove-image-btn" class="btn btn-danger waves-effect" style="display: {{ (isset($slide->imagems) && $slide->imagems && $slide->imagems->url) ? 'inline-block' : 'none' }};">
                                <i class="material-icons" style="vertical-align: middle; font-size: 18px;">delete</i>
                                Remover Imagem
                            </button>
                            {{ Form::hidden('idimagem', $slide->imagems->id ?? null, array('id' => 'idimagem', 'name'=>'idimagem')) }}
                            {{ Form::text('Titulo Imagem', $slide->imagems->url ?? null, array('id' => 'tituloIMG','class'=>'form-control',  'placeholder'=>'Selecione uma imagem para o slide.', 'readonly'=>'')) }}
                      </div>
                    </div>

                    <div class="form-group">
                        <label>Pré-visualização da Imagem:</label>
                        <div id="image-preview-container" style="margin-top: 10px; border: 2px dashed #ddd; border-radius: 4px; padding: 10px; min-height: 200px; background-color: #f9f9f9; text-align: center; position: relative;">
                            @if(isset($slide->imagems) && $slide->imagems && $slide->imagems->url)
                                <img id="image-preview" src="{{ URL::to('/') }}/files/images/{{ $slide->imagems->url }}" alt="Slide Image" style="max-width: 100%; max-height: 300px; border-radius: 4px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                            @else
                                <div id="image-preview-placeholder" style="padding: 60px 20px; color: #999;">
                                    <i class="material-icons" style="font-size: 64px; opacity: 0.3;">image</i>
                                    <p style="margin-top: 10px;">Nenhuma imagem selecionada</p>
                                    <small>Clique em "Escolher Imagem" para selecionar</small>
                                </div>
                                <img id="image-preview" src="" alt="Slide Image" style="max-width: 100%; max-height: 300px; border-radius: 4px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); display: none;">
                            @endif
                        </div>
                    </div>
                </fieldset>
            </div>
		</div>

		<div class="col-md-8" style="margin-top:15px;">
        </div>
        
		{!! Form::close() !!}
        @include("Administrator.partials._galeria")
	</div>
@endsection

@section('script_bottom')
    <!-- Vue.js App -->
    <script>
        // Set base URL for Vue components
        window.appBaseUrl = '{{ url('/') }}';
    </script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{URL('/')}}/js/cms.js"></script>
    <script src="{{URL('/')}}/js/galeria.js"></script>
    <script>
    $(document).ready(function() {
        // Listen for changes to the hidden image fields
        $('#idimagem').on('change input', function() {
            var imageId = $(this).val();
            var imageUrl = $('#tituloIMG').val();
            
            if (imageId && imageUrl) {
                // Update image preview
                var fullImageUrl = '{{ URL::to("/") }}/files/images/' + imageUrl;
                $('#image-preview').attr('src', fullImageUrl).show();
                $('#image-preview-placeholder').hide();
                $('#remove-image-btn').show();
            }
        });
        
        // Initialize gallery when modal opens
        $('#largeModal').on('shown.bs.modal', function () {
            var defaultCategory = $('input.raizID').attr('data-obj');
            if (defaultCategory && typeof ajaxImg === 'function') {
                ajaxImg(defaultCategory);
            }
        });

        // Manual category change handler to ensure it works
        $(document).on('change', 'input[name="iDcategoria"]', function() {
            var categoryId = $(this).val();
            if (typeof ajaxImg === 'function') {
                ajaxImg(categoryId);
            }
        });

        // Handle image selection when okModalGal is clicked
        $(document).on('click', '#okModalGal', function(e) {
            var element = $("#aniimated-thumbnials .showImage img.hover");
            
            if (element.length > 0) {
                var imageId = element.attr('data-id');
                var imageUrl = element.attr('data-title');
                
                if (imageId && imageUrl) {
                    $('#idimagem').val(imageId);
                    $('#tituloIMG').val(imageUrl);
                    
                    // Trigger the change event to update preview
                    $('#idimagem').trigger('change');
                    
                    // Close modal
                    $('#largeModal').modal('hide');
                } else {
                    alert("Erro: Imagem sem dados. Por favor, tente novamente.");
                }
            } else {
                alert("Nenhuma imagem foi selecionada.");
            }
        });

        // Remove image button functionality
        $('#remove-image-btn').on('click', function() {
            if (confirm('Tem certeza que deseja remover a imagem selecionada?')) {
                // Clear form fields
                $('#idimagem').val('');
                $('#tituloIMG').val('');
                
                // Hide image preview and show placeholder
                $('#image-preview').hide();
                $('#image-preview-placeholder').show();
                
                // Hide remove button
                $(this).hide();
            }
        });

        // Show placeholder if no image on load
        @if(!isset($slide->imagems) || !$slide->imagems || !$slide->imagems->url)
        $('#image-preview').hide();
        $('#image-preview-placeholder').show();
        $('#remove-image-btn').hide();
        @endif
    });
    </script>
@endsection

