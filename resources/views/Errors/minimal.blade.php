
<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="UTF-8"/>
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport"/>
        <title>{{config('app.title')}}</title>
        <!-- Favicon-->
        <link rel="icon" href="{{ URL::to('/') }}/files/images/favicon.ico" type="image/x-icon"/>

        <!-- Google Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css"/>
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css"/>

        <!-- Bootstrap Core Css -->
        <link href="{{ URL::to('/') }}/plugins/bootstrap/css/bootstrap.css" rel="stylesheet"/>

        <!-- Waves Effect Css -->
        <link href="{{ URL::to('/') }}/plugins/node-waves/waves.css" rel="stylesheet"/>

        <!-- Animation Css -->
        <link href="{{ URL::to('/') }}/plugins/animate-css/animate.css" rel="stylesheet"/>

        <!-- Custom Css -->
        <link href="{{ URL::to('/') }}/css/material/style.min.css" rel="stylesheet"/>

        <link href="{{ URL::to('/') }}/css/style.site.css" rel="stylesheet"/>
        <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
        <link href="{{ URL::to('/') }}/css/material/themes/theme-white.css" rel="stylesheet"/>
    </head>
    <body class="four-zero-four">
        <div class="container container-table">
            <div class="four-zero-four-container">
                <div class="error-code" id="app-error">
                    <div class="">
                        <img class="city" src="{{ URL::to('/') }}/files/images/error.png" alt="" height="80" width="80">
                    </div>
                </div>
                <div class="error-message cmp_sep_top_margin">
                    <p>@yield('texto')</p>
                </div>
                <div class="button-place">
                    <a href="{{ URL::to('/') }}" class="btn btn-default btn-lg waves-effect">Voltar ao Inicio</a>
                    {{-- <a href="@if(isset($clerkId) && !is_null($clerkId)) {{ URL::to('/') }}/ccidadao/{{$key or ''}}/{{$clerkEmail or ''}} @else {{ URL::to('/') }} @endif" class="btn btn-default btn-lg waves-effect">Voltar ao Inicio</a> --}}
                </div>
            </div>
        </div>
    </body>
    <!-- Jquery Core Js -->
    <script src="{{ URL::to('/') }}/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap Core Js -->
    <script src="{{ URL::to('/') }}/plugins/bootstrap/js/bootstrap.js"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="{{ URL::to('/') }}/plugins/node-waves/waves.js"></script>
</html>
