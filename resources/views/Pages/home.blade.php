<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">

    <head>
      @include("Pages.partials._head")

      @yield('title')

       <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>

   <!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-0J2E3QQ0TH"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-0J2E3QQ0TH');
</script>
<style type="text/css">

 ::selection {
  background: #4286f4; /* WebKit/Blink Browsers */
}
::-moz-selection {
  background: #4286f4; /* Gecko Browsers */
}
</style>
    </head>
    <body data-spy="scroll" data-target=".navbar" data-offset="50">
        <!-- top menu -->
      @include("Pages.partials._navmenu")

      @include("Pages.partials._menuprincipal")
      

       @if (View::hasSection('slide'))
              @yield('slide')
       @else
             @include("Pages.partials._slide")
       @endif

       

        <section class="bgBlue">
           <div class="container bgBlue">
              <div class="row">
                 <div class="col-sm-12 col-md-12 col-lg-12" >
              @yield('breacrumbs')
            </div>
           </div>
          </div>
        </section>

        @if (View::hasSection('content'))
             <section id="aac-home-wrapper">
              @yield('content')
          </section>

        @else
              <section id="aac-home-wrapper">
                <div class="container">
                  <div class="row">

                      {{-- @include("Pages.partials._menuleft")

                       @include("Pages.partials._contentright") --}}
                  </div>
                </div>
              </section>

            @include("Pages.partials._intro")
          @include("Pages.partials._areas")
            @include("Pages.partials._dochome")
        @endif


       <!-- home menu and articles slides -->

  <!-- /home menu and articles slides -->


    @include("Pages.partials._parceiro")


    </body>


         @include("Pages.partials._footer")

         @yield('script_bottom')
         <script>
   let nav = document.getElementById("navigation-bar");
   let sticky = nav.offsetTop;
   window.onscroll = function() {sticker()};
   function sticker() {
      if (window.pageYOffset >= sticky) {
         nav.classList.add("sticky")
      } else {
         nav.classList.remove("sticky");
      }
   }
</script>
</html>
