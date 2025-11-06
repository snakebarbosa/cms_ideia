  <nav class="navbar navbar-default bg_azul">
                    <div class="container-fluid ">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header">
                      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                      </button>
                      <a class="navbar-brand" href="{{ URL::to('/') }}/Administrator"><img style="padding: 10px;width: 63%;" src="{{ URL::to('/') }}/files/images{{ $boinfo['logo']  }}"></a>
                    </div>

                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                      <ul class="nav navbar-nav">

                         @foreach($menu['pages'] as $parent)
                             <li class="dropdown">
                                <a href="#" class="dropdown-toggle a_white" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{ $parent['pagename'] }} <span class="caret"></span></a>


                                  @if ($parent['submenu']!="NULL")
                                    <ul class="dropdown-menu">
                                      @foreach($parent['submenu'] as $item)
                                     {{--  <li class="menu-item-has-children">
                                          <a href="{{ $pages->pagelink }}">{{$pages['pagename']}}</a>
                                      </li> --}}
                                        <li>
                                             <a href="{{ URL::to('/') }}/{{$item['pagelink']}}"> <span class="glyphicon glyphicon-{{ $item['icon'] }} text-primary"></span>{{$item['pagename']}}</a>
                                        </li>
                                     @endforeach
                                    </ul>
                                  @endif

                            </li>
                            @endforeach

                      </ul>

                    {{--   <form class="navbar-form navbar-left">
                        <div class="form-group">
                          <input type="text" class="form-control" placeholder="Search">
                        </div>
                        <button type="submit" class="btn btn-default">Submit</button>
                      </form> --}}

                      <ul class="nav navbar-nav navbar-right">

                            {{-- <li role="separator" class="divider"></li> --}}


                          <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @if (Auth::guest())
                            <li><a href="{{ route('login') }}">Login</a></li>
                        @else
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle a_white" data-toggle="dropdown" role="button" aria-expanded="false">
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    </ul>


                      </ul>
                    </div><!-- /.navbar-collapse -->
                  </div><!-- /.container-fluid -->
                </nav>