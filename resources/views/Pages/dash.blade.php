@extends('Pages.home')

@section('title')
	@yield('keywords')

@endsection

@section('breacrumbs')
 

@endsection

@section('slide')
	<section id="aac-topsection_nav">
    	<div id="container"  style="height:200px;background-image: url( {{ URL::to('/') }}/files/images/topo3.jpg)">

    	</div>
  	</section>
@endsection


@section('content')
  <div class="container">
	<div class="row">

		
	    <div class="col-sm-12 col-md-12 col-lg-12">
			<iframe width="100%" height="700" src="https://app.powerbi.com/view?r=eyJrIjoiOWQ2NGY3NDEtNmFjZC00ZmU1LTlkNGYtZjc4NTVmODI2NjQ4IiwidCI6ImY1MDUzZGZkLTM3M2QtNDYzZS1hZGFmLTI1Yzg5ZWZlNTFlYiIsImMiOjl9" frameborder="0" allowFullScreen="true"></iframe>
	    </div>
	 </div>
 	</div>

@endsection


