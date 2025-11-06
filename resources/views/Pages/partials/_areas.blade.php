
  <section>

    <div class="container-fluid top30 borderAll">

      <div class="row">

        <div class="col-sm-6 col-md-6 col-lg-6 icon_area_r" style="">
			<div class="col-sm-10 col-md-10 col-lg-10 " style="">
				<a href="mailto:notification@ipiaam.cv"><h3 class="icon_area_h fontBold">@if(Session::get('lan')==0) Aéreo @else Aviation  @endif </h3></a>
		   		<p>@if(Session::get('lan')==0) Entre em contacto em caso de acidentes aéreos. @else Please Contact Us in case of an accident @endif </p>
			</div>
			<div class="col-sm-2 col-md-2 col-lg-2 " >
			<a href="mailto:notification@ipiaam.cv"><img src="{{URL::to('/') }}/files/images/plane1.png" alt="" class="icon_area"></a>
			</div>
			

		</div>
		
		<div class="col-sm-6 col-md-6 col-lg-6 tab_container" >
			
			<div class="col-sm-2 col-md-2 col-lg-2 " style="">
				<a href="mailto:notification@ipiaam.cv"><img src="{{ URL::to('/') }}/files/images/ship_icon.png" alt="" class="icon_area"></a>
			</div>

			<div class="col-sm-10 col-md-10 col-lg-10 " style="">
		    	<a href="mailto:notification@ipiaam.cv"><h3 class="icon_area_h fontBold">@if(Session::get('lan')==0) Marítimo @else Maritime  @endif </h3></a>
		   		 <p>@if(Session::get('lan')==0) Entre em contacto em caso de acidentes marítimos. @else Please Contact Us in case of an accident @endif</p>
			</div>
			
		</div>
		<!-- ##### end tab -->

      </div>

    </div>
  </section>
