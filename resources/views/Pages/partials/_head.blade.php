  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
 <meta name="csrf-token" content="{{ csrf_token() }}">
 <meta http-equiv="Access-Control-Allow-Origin" content="*"/ >
  <meta name="title" content="Instituto de Prevenção e Investigação de Acidentes Aereos e Maritimos  - IPIAAM">
  <title>Instituto de Prevenção e Investigação de Acidentes Aereos e Maritimos  - IPIAAM</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600" rel="stylesheet">
  <meta name="keywords" content="Instituto de Preven&ccedil;&atilde;o e Investiga&ccedil;&atilde;o de Acidentes Aereos e Maritimos, Cabo Verde, IPIAAM"/>

  <!-- Latest compiled and minified CSS -->
  <!-- Styles -->

  <link rel="icon" href="{{ URL::to('/') }}/files/images/favicon.png">

  <link rel="stylesheet" href="{{ URL::to('/') }}/css/bootstrap/bootstrap-theme.min.css">
  <link rel="stylesheet" href="{{ URL::to('/') }}/css/bootstrap/bootstrap.min.css">
  <link rel="stylesheet" href="{{ URL::to('/') }}/css/aac.css">
  <link rel="stylesheet" href="{{ URL::to('/') }}/css/font-awesome.min.css">

  <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

<script src="https://apps.elfsight.com/p/platform.js" defer></script>


  <script type="text/javascript">

    $(function() { var logo = $(".logo"); $(window).scroll(function() {
        var scroll = $(window).scrollTop();

            if (scroll >= 200) {
                logo.show();

            } else {
                logo.hide();

            }

        });
    });
  </script>

  @yield('script')

  @yield('stylesheet')