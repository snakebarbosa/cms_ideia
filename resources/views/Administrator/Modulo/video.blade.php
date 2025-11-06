@extends('Administrator.admin')



@section('title_content')

Video
@endsection

@section('stylesheet')


@endsection

@section('script')

@section('help')
  @include("Administrator.partials._rapido")
@endsection

@endsection

@section('menu_content')

@endsection


@section('content')

{!! Form::open(array('route' => 'Video.storevideo','data-parsley-validate'=>'')) !!}
<div class=" pull-right topBottom">
    {{-- <a id="novo" href="{{ URL::to('/') }}/Administrator/Artigos/Add" class="btn btn-default btn-sm btn-success"><i class="glyphicon glyphicon-plus"></i> Novo Artigo</a>
    <a id="publicar" href="#" class="btn btn-default btn-sm btn-info "><i class="glyphicon glyphicon-check"></i> Publicar</a>
    <a id="despublicar" href="#" class="btn btn-default btn-sm btn-warning"><i class="glyphicon glyphicon-unchecked"></i> Despublicar</a>
    <a id="remover" href="#" class="btn btn-default btn-sm btn-danger"><i class="glyphicon glyphicon-remove"></i> Remover</a> --}}

    {{ Form::submit('Guardar', array('class'=>'btn btn-success btn-lg btn-block')) }}
</div>

<div class="content">

	<div class="col-md-8">
		<fieldset style="margin-top:15px;">
			<legend>Video</legend>
			{{-- {{ Form::hidden('type', 'art', array('id' => 'type', 'name'=>'type')) }} --}}
			<div class="form-group">
				<div class="form-line">
					{{ Form::label('Url','Url Video:') }}
					{{ Form::url('url', null, array('class'=>'form-control','required'=>'', 'maxlength'=>'250','placeholder'=>'Ex:https://www.youtube.com/v=sfgsdknfsdlk')) }}
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

	</div>

</div>
{!! Form::close() !!}

@endsection

@section('script_bottom')
<script>

</script>

@endsection

