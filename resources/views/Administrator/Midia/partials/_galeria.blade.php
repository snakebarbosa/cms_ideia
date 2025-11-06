<legend>Galeria</legend>



	   <div class="card row">

			<div class="body" id="imagemGaleria">
			     <div id="aniimated-thumbnials" class="list-unstyled row clearfix">



			   </div>
		  </div>
     </div>
      <input type="hidden" name="deleteImg" id="deleteImg" value="{{$delete}}" readonly>


					{!! Form::open(array('method'=>'DELETE', 'id'=>'formDelImg')) !!}

					{!! Form::close() !!}

