<div class="container" id="navigation-bar">
 <nav class="navbar">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" data-target="#navbarCollapse" data-toggle="collapse" class="navbar-toggle">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand cmplogo" href="{{ URL::to('/') }}">
              <img class="city logo_img" src="{{ URL::to('/') }}/files/images/logo_ipiaam_512.png">
            </a>
        </div>

       <div style="margin-right: 10px;">
          <form class="navbar-form navbar-right form-inline" method="get" action="{{route('Pages.getSearch')}}" role="search">
            <div class="form-group">
              <input type="text" class="form-control search_box" placeholder="Pesquisar..." name="s">
            </div>
            <button class="btn" type="submit">
              <i class="glyphicon glyphicon-search"></i>
            </button>
          </form>
       </div>
        <!-- Collection of nav links, forms, and other content for toggling -->
        <div id="navbarCollapse" class="collapse navbar-collapse navbar-right" style="margin-top: 8px;">
            <ul class="nav navbar-nav menu_superior">
                <li class="textUpper itemp"><a href="{{ URL::to('/') }}"><i class="glyphicon glyphicon-home"></i></a></li>
                @foreach($principal as $item)
                  @if(isset($item['childreen'][0]))
                    <li class="dropdown itemp">
                        <a href="{{ URL::to('/') }}/{{ $item['url'] }}" class="dropdown-toggle textUpper" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{ $item['conteudos'][Session::get('lan')]['titulo'] }}<span class="caret"></span></a>

                        <ul  class="dropdown-menu" role="menu">
                          @foreach($item['childreen'] as $childreen)
                            <li><a class="itemp" href="{{ URL::to('/') }}{{ $childreen['url'] }}">{{ $childreen['conteudos'][Session::get('lan')]['titulo'] }}</a></li>
                            <li role="separator" class="divider"></li>
                          @endforeach
                        </ul>
                    </li>
                  @else
                    <li class="itemp "><a href="{{ URL::to('/') }}/{{ $item['url'] }}" class="textUpper">{{ $item['conteudos'][Session::get('lan')]['titulo'] }}</span></a></li>
                  @endif
              @endforeach
            </ul>
        </div>
    </nav>
</div>
