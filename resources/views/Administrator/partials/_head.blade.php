  <!-- <meta http-equiv="X-UA-Compatible" content="IE=edge"> -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <meta name="title" content="Instituto de Prevenção e Investigação de Acidentes Aereos e Maritimos  - IPIAAM">
  <title>Instituto de Prevenção e Investigação de Acidentes Aereos e Maritimos  - IPIAAM</title>

   {{-- Favicon --}}
  <link rel="icon" href="{{ URL::to('/') }}/files/images/ideia_icon32.png">
   {{-- <link rel="icon" href="{{ URL::to('/') }}/images/ideia_icon32.png" type="image/png">  --}}
  
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

  <!-- Bootstrap Core Css -->
  <link href="{{ URL::to('/') }}/plugins/bootstrap/css/bootstrap.css" rel="stylesheet">
  <link href="{{ URL::to('/') }}/css/material/materialize.css" rel="stylesheet" />
  <link href="{{ URL::to('/') }}/css/material/themes/all-themes.css" rel="stylesheet" />
  <link href="{{ URL::to('/') }}/plugins/sweetalert/sweetalert.css" rel="stylesheet">
  <!-- Waves Effect Css -->
  <link href="{{ URL::to('/') }}/plugins/node-waves/waves.css" rel="stylesheet" />

  <!-- Animation Css -->
  <link href="{{ URL::to('/') }}/plugins/animate-css/animate.css" rel="stylesheet" />

  <!-- Custom Css -->
  <link href="{{ URL::to('/') }}/css/material/style.css" rel="stylesheet">

  <link href="{{ URL::to('/') }}/css/cms.css" rel="stylesheet" />

 <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
 <link rel="stylesheet" href=" https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css">

  <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->


  @yield('stylesheet')