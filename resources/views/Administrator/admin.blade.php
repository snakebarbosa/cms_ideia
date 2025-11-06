<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">

<head>

  @include("Administrator/partials._head")

  @yield('title')

  <script>
    window.Laravel = {!! json_encode([
      'csrfToken' => csrf_token(),
      ]) !!};
    </script>

  </head>
  <body class="theme-indigo ls-closed">
    <!-- Page Loader -->
    <div class="page-loader-wrapper">
      <div class="loader">
          <img src="{{URL::to('/')}}/files/images/load_icon_c2.gif" width="220" alt="">
           <!--<div class="preloader">
            <div class="spinner-layer pl-red">
              <div class="circle-clipper left">
                <div class="circle"></div>
              </div>
              <div class="circle-clipper right">
                <div class="circle"></div>
              </div>
            </div> 
          </div>-->
        <p>Carregando...</p>
      </div>
    </div>
    <!-- #END# Page Loader -->
    <!-- Overlay For Sidebars -->
    <div class="overlay"></div>

    @include("Administrator/partials._navmenu")
    @include("Administrator/partials._menuleft")

    <section class="content">
     
        <div class="block-header">

          <!-- <h2>BLANK PAGE</h2> -->

        </div>
   
        @include("Administrator/partials._contentright")

    </section>


<input type="hidden" name="path" id="path" value="{{URL::to('/')}}" placeholder="">
<input type="hidden" name="path" id="path2" value="{{URL::to('/')}}/files/images" placeholder="">

  </body>


  @include("Administrator/partials._footer")
  @yield('script_bottom')

  </html>
