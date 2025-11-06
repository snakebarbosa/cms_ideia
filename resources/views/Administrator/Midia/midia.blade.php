
@extends('Administrator.admin')


@section('stylesheet')

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<!-- JQuery Nestable Css -->
<link href="{{URL::to('/')}}/plugins/nestable/jquery-nestable.css" rel="stylesheet" />

<!-- Dropzone Css -->
<link href="{{URL('/')}}/plugins/dropzone/dropzone.css" rel="stylesheet">

<!-- Light Gallery Plugin Css -->
<link href="{{URL('/')}}/plugins/light-gallery/css/lightgallery.css" rel="stylesheet">


@endsection

@section('title_content')

	Galeria
@endsection

@section('menu_content')
	<div class=" pull-right">
				<a href= "{{ route('Midia.create') }}">
					<button type="button" class="btn btn-info waves-effect">
						<i class="material-icons">add</i>
						<span>Adicionar Album</span>
					</button>
				</a>
			</div>
@endsection

@section('help')
  @include("Administrator.partials._rapido")
@endsection

@section('content')

<div class="content">
	 <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12"  style="right: 10px;" >
			<!-- categoriaImabgem -->
			@include("Administrator/Midia/partials._categoriaImag",['delete'=> true])
			<!-- FimcategoriaImabgem -->
		</div>

		<div class="col-md-8" >
			<!-- inicio upload -->
			@include("Administrator/Midia/partials._upload")
			<!-- fim upload -->

			<!-- Inicio Galeria -->
			@include("Administrator/Midia/partials._galeria",['delete'=> true])
			<!-- Fim Galeria -->

		</div>
	</div>
</div>



@endsection

@section('script_bottom')
<script >

</script>


<!-- Dropzone Plugin Js -->
<script src="{{URL('/')}}/plugins/dropzone/dropzone.js"></script>
<!-- Slimscroll Plugin Js -->
<script src="{{URL('/')}}/plugins/jquery-slimscroll/jquery.slimscroll.js"></script>
<!-- Light Gallery Plugin Js -->
<script src="{{URL('/')}}/plugins/light-gallery/js/lightgallery-all.js"></script>
<!-- Custom Js -->
<script src="{{URL('/')}}/js/material/pages/medias/image-gallery.js"></script>

<script src="{{URL('/')}}/js/material/pages/forms/dropzone.js"></script>


<script src="{{URL('/')}}/js/galeria.js"></script>



@endsection