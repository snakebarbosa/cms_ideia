<div class="row clearfix">
		<div class="card">

			{!!  Form::open(array('route'=>'Imagem.store','files'=>true ,'name' => "frm_select_client", 'id'=>'frmFileUpload', 'class'=>'dropzone dz-clickable upImage', 'enctype'=>'multipart/form-data' ,'method'=>'POST')) !!}
               {{ Form::hidden('titulo', 'titulo', array('id' => 'titulo', 'name'=>'titulo')) }}
               {{ Form::hidden('categoria', $tree['id'], array('id' => 'categoria', 'name'=>'categoria'))  }}




					<div class="dz-message">
						<div class="drag-icon-cph">
							<i class="material-icons">touch_app</i>
						</div>
						<h3>Solte os arquivos aqui ou clique para carregar.</h3>
					</div>
					<div class="fallback">
						<input name="file" accept="image/*" type="file" multiple />
					</div>

					{!! Form::close() !!}
				</div>
		</div>

