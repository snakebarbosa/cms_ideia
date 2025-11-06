 
 <div class="search-bar">
        <div class="search-icon">
            <i class="material-icons">search</i>
        </div>
          {!! Form::open(['route' => 'Art.search','class' => 'steps']) !!}
            <input type="text" name="search" minlength="3" required="required" placeholder="Pesquisar por Titulo do Artigo..."/>
        {!! Form::close() !!}
        <div class="close-search">
            <i class="material-icons">close</i>
        </div>
    </div>
    <!-- #END# Search Bar -->
    <!-- Top Bar -->
    <nav class="navbar">
        <div class="container-fluid">
            <div class="navbar-header">
                <a href="javascript:void(0);" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false"></a>
                <a href="javascript:void(0);" class="bars"></a>
            <a class="navbar-brand" href="{{ url('/') }}/Administrator">{{config('app.name', 'IDEIA CMS')}}</a>
            </div>
            <div class="collapse navbar-collapse" id="navbar-collapse">
                <ul class="nav navbar-nav navbar-right">
                    <!-- Call Search -->

                    <li><a href="javascript:void(0);" class="js-search" data-close="true"><i class="material-icons">search</i></a></li>
                     <li><a href="{{ URL::to('/') }}/Administrator/Config"  ><i class="material-icons">settings</i></a></li>
                     <li class="pull-right"><a href="{{ url('/') }}" target="_blank" class="js-right-sidebar" data-close="true"><i class="material-icons">open_in_browser</i></a></li>

                </ul>
            </div>
        </div>
    </nav>