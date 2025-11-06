@extends('Pages.nav')

@section('keywords')

	<meta name="keywords" content="Contact, AAC, Agência Aviação Civil, Cabo Verde"/>
@endsection

@section('bcrumbs')
	  {!! $crumbs !!}

@endsection

@section('content2')

		@include("partials._message")

			<div class="col-sm-12 col-md-12 col-lg-12" style="padding:0;">
				<h3 class="aac-block-title" style="margin-left:0;padding-top: 0;margin-top: 0;" >
				    @if(Session::get('lan')==0) Contacte-Nos @else Contact Us @endif
				</h3>

				<div class="col-sm-12 col-md-12 col-lg-12" style="padding:0;">
					{!! Form::open(array('route' => 'Pages.postContact', 'class' => 'form','method'=>'POST')) !!}
						{{ csrf_field()}}
					<div class="form-group">
					    {!! Form::label('Nome') !!}
					    {!! Form::text('name', null,
					        array('required', 'class'=>'form-control', 'placeholder'=>'Your name')) !!}
					</div>

					<div class="form-group">
					    {!! Form::label('E-mail') !!}
					    {!! Form::text('email', null, array('class'=>'form-control','placeholder'=>'Your e-mail address')) !!}
					</div>

					<div class="form-group">
					    {!! Form::label('Assunto') !!}
					    {!! Form::text('subject', null,
					        array('required', 'class'=>'form-control', 'placeholder'=>'Assunto')) !!}
					</div>

					<div class="form-group">
					    {!! Form::label('Mensagem') !!}
					    {!! Form::textarea('message', null,array('required', 'class'=>'form-control','placeholder'=>'Your message')) !!}
					</div>

					<div class="form-group">
					    {!! Form::submit('Enviar!',array('class'=>'btn btn-primary')) !!}
					</div>
					{!! Form::close() !!}
				</div>
			</div>

	<div class="col-sm-12 col-md-12 col-lg-12">
		 <div class="row col1">
	       <div class="col-xs-3">
	           <i class="fa fa-map-marker" style="font-size:16px;"></i>   Address
	       </div>
	       <div class="col-xs-9">
	          Sede do IPIAAM<br>Rua Angola, Cidade do Mindelo São Vicente - República de Cabo Verde<br>
	       </div>
	   </div>

	    <div class="row col1">
	        <div class="col-sm-3">
	            <i class="fa fa-phone"></i>   Phone
	        </div>
	        <div class="col-sm-9">
	             +238 230 09 92
	        </div>
	    </div>
	    <div class="row col1">
	       <br/>
	    </div>
	     <div class="row col1">
	       <div class="col-xs-3">
	           <i class="fa fa-map-marker" style="font-size:16px;"></i>   Address
	       </div>
	       <div class="col-xs-9">
	          Delegação do IPIAAM <br>Achada Grande Frente, Praia<br>CP 7603
	       </div>
	   </div>

	    <div class="row col1">
	       <!-- <div class="col-sm-3">
	            <i class="fa fa-phone"></i>   Phone
	        </div>
	        <div class="col-sm-9">
	             +238 260 34 30
	        </div>-->
	    </div>
	    <div class="row col1">
	        <div class="col-sm-3">
	            <i class="fa fa-envelope"></i>   Email
	        </div>
	        <div class="col-sm-9">
	             <a href="mailto:notification@ipiaam.gov.cv"> {{ $email }}</a> <br>
	        </div>
	    </div><br>
	    <iframe width="100%" height="230" frameborder="0" style="border-radius:0px;" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.co.in/maps?client=firefox-a&ie=UTF8&q=AAC&fb=1&gl=in&t=m&ll=14.9221173,-23.496062&spn=0.052731,0.154495&z=13&iwloc=A&output=embed"  style="border-radius:20px;"></iframe>
	</div>

@endsection
