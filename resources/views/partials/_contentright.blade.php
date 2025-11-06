
<div class="panel panel-default">
  <div class="panel-heading clearfix">
    <h3 class="panel-title pull-left" style="padding-top: 7.5px;">@yield('title_content',' Bem vindo ao Administrator')</h3>

  	@yield('menu_content')

  </div>

  <div class="panel-body">

    @include("partials._message")


    @if (View::hasSection('content'))
       @yield('content');
    @else
        <div class="flex-center position-ref full-height">
            <div class="content" style="text-align: center;">
                <div class="title m-b-md">
                <img src="{{URL::to('/')}}/files/images/LOGO_AAC_.png}}">
                </div>

            
            </div>
        </div>
    @endif






  </div>
</div>