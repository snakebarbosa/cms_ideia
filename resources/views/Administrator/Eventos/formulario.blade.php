
<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">

    <head>
        <meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
         {{-- <link rel="stylesheet" type="text/css" href="{{URL::to('/')}}/css/formulario/demo.css">  --}}
        <link rel="stylesheet" type="text/css" media="screen" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        {{-- <link rel="stylesheet" type="text/css" media="screen" href="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.1/jquery.rateyo.min.css"> --}}

        

          <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

  <!-- Bootstrap Core Css -->
  {{-- <link href="{{ URL::to('/') }}/plugins/bootstrap/css/bootstrap.css" rel="stylesheet"> --}}
  {{-- <link href="{{ URL::to('/') }}/css/material/materialize.css" rel="stylesheet" /> --}}
  <link href="{{ URL::to('/') }}/css/material/themes/all-themes.css" rel="stylesheet" />
  <link href="{{ URL::to('/') }}/plugins/sweetalert/sweetalert.css" rel="stylesheet">
  <!-- Waves Effect Css -->
  <link href="{{ URL::to('/') }}/plugins/node-waves/waves.css" rel="stylesheet" />

  <!-- Animation Css -->
  <link href="{{ URL::to('/') }}/plugins/animate-css/animate.css" rel="stylesheet" />

  <!-- Custom Css -->
  {{-- <link href="{{ URL::to('/') }}/css/material/style.css" rel="stylesheet"> --}}

  {{-- <link href="{{ URL::to('/') }}/css/cms.css" rel="stylesheet" /> --}}

  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
 {{-- <link rel="stylesheet" href=" https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css"> --}}

  <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->



        <style type="text/css" media="screen">

            body{
                /* background: lightgray; */
                background: #f2f2f2;
                font-family: sans-serif;
            }

            #fb-rendered-form {
                clear:both;
                display:none;
                button{
                    float:right;
                }
            }

        </style>

        <script>
            window.Laravel = {!! json_encode(['csrfToken' => csrf_token(),]) !!};    </script>

    </head>

    <body>       


        <!-- Overlay For Sidebars -->
        <div class="overlay"></div>
        
        
        {{-- @include("Administrator/partials._menuleft") --}}

        <div class="content">
          <div class="meuClasse">
            <div id="fazer_formulario" class="formulario1" style="padding: 35px;
                 margin-bottom: 51px; ">

                <h1>Crie/Edite o seu formulário</h1> 

   

                <div  id="fb-editor"></div>

                <div id="fb-rendered-form">

                    <form method="POST" action="#" >   </form>

                    <button class="btn btn-default edit-form">Editar</button>          
                    <button id="salvar_dados" class="btn btn-success ">Salvar</button>

                  
                   
                </div>
    


            </div>

           </div>

        </div>


    </body>





<script src="{{ URL::to('/') }}/plugins/jquery/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>


     {{-- <script src="{{URL::to('/')}}/js/formulario/control_plugins/starRating.min.js"></script>  --}}
<script src="{{URL::to('/')}}/js/formulario/control_plugins/textarea.trumbowyg.min.js"></script>


<script src="{{URL::to('/')}}/js/formulario/vendor.js"></script>
<script src="{{URL::to('/')}}/js/formulario/form-builder.min.js"></script>
<script src="{{URL::to('/')}}/js/formulario/form-render.min.js"></script>

 {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.1/jquery.rateyo.min.js"></script>  --}}
 {{-- <script src="{{URL::to('/')}}/js/formulario/demo.js"></script>  --}}




    <!-- Bootstrap Core Js -->
    {{-- <script src="{{ URL::to('/') }}/plugins/bootstrap/js/bootstrap.js"></script> --}}



    <!-- Slimscroll Plugin Js -->
    <script src="{{ URL::to('/') }}/plugins/jquery-slimscroll/jquery.slimscroll.js"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="{{ URL::to('/') }}/plugins/node-waves/waves.js"></script>

    <!-- Custom Js -->
    {{-- <script src="{{ URL::to('/') }}/js/material/admin.js"></script> --}}

    <!-- Demo Js -->
    {{-- <script src="{{ URL::to('/') }}/js/material/demo.js"></script> --}}

    <script src="{{URL('/')}}/plugins/sweetalert/sweetalert.min.js"></script>
   

  
<script type="text/javascript">
     

            $(document).ready(function(){


            var dados = {!!$formulario!!};    
          
             if (dados != "") {
                    window.onload = function(){
                    formBuilder.actions.setData(dados);
                      } 
                  }  

            var $fbEditor = $(document.getElementById('fb-editor')),
                    $formContainer = $(document.getElementById('fb-rendered-form')),
                    fbOptions = {
                        onSave: function() {
                            $fbEditor.toggle();
                            $formContainer.toggle();
                            $('form', $formContainer).formRender({
                                 formData: formBuilder.formData

                            });
                            window.sessionStorage.setItem('formData', (formBuilder.formData));
                          },
                         i18n: {
                         locale: 'pt-BR'
                         }
                    },
                    formBuilder = $fbEditor.formBuilder(fbOptions);
                    $('.edit-form', $formContainer).click(function() {
                       $fbEditor.toggle();
                       $formContainer.toggle();
                    });
              
                       
            

              $('#salvar_dados').click(function(e){
              e.preventDefault();
                
                var pegaJson = window.sessionStorage.getItem('formData'); 
                  
                var idEvent = {!!$eventoId[0]!!};               

                url = '{{route("Evento.setFormulario")}}';                 
               
                  $.ajax({
                    
                      type: 'post',
                      url: url,
                      dataType: "text",
                      data: {pegaJson:pegaJson, idEvent:idEvent, "_token":"{{ csrf_token()}}"},    
                      success: function()  
                      {  
                        alert("Dados salvo com sucesso");
                      },
                      error: function() {
                       alert("Erro ao salvar");
                      }
                  });
                });

            });
</script>

</html>
