<section>
<aside id="leftsidebar" class="sidebar">
            <!-- User Info -->
            <div class="user-info" style="background: url('{{URL::to('/')}}/files/images/user-img-background.jpg') no-repeat no-repeat;">
               {{-- <!--  <div class="image">
                    <img src="{{ URL::to('/')}}/images/user.png" width="48" height="48" alt="User" />
                </div> --> --}}

                  <!-- Authentication Links -->


                 <div class="info-container">
                    <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> {{ Auth::user()->name }}</div>
                    <div class="email"> {{ Auth::user()->email }}</div>
                    <div class="btn-group user-helper-dropdown">
                        <i class="material-icons" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">keyboard_arrow_down</i>
                        <ul class="dropdown-menu pull-right">
                            <li><a href="{{ route('User.edit', Auth::user()->id) }}"><i class="material-icons">person</i>Meu Perfil</a></li>
                            <li role="seperator" class="divider"></li>

                            <li><a href="{{ route('logout') }}"  onclick="event.preventDefault();
                                       document.getElementById('logout-form').submit();"><i class="material-icons">input</i>Sair</a></li>
                             <form id="logout-form" class="steps" action="{{ route('logout') }}" method="POST" style="display: none;">
                              {{ csrf_field() }}
                          </form>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- #User Info -->
            <!-- Menu -->
            <div class="menu">
                <ul class="list">
                    <li class="header">Menu</li>
                    <li >
                        <a href="{{ URL::to('/') }}/Administrator">
                            <i class="material-icons">home</i>
                            <span>Home</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ URL::to('/') }}/Administrator/User">
                            <i class="material-icons">person</i>
                            <span>Utilizador</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ URL::to('/') }}/Administrator/Tag">
                            <i class="material-icons">person</i>
                            <span>Tags</span>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">perm_media</i>
                            <span>Menus</span>
                        </a>
                        <ul class="ml-menu">
                            <li>
                                <a href="{{ URL::to('/') }}/Administrator/Tipo">Menu</a>
                            </li>
                            <li>
                                <a href="{{ URL::to('/') }}/Administrator/Item">Item</a>
                            </li>
                        </ul>
                    </li>
                     <li>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">perm_media</i>
                            <span>Eventos</span>
                        </a>
                        <ul class="ml-menu">
                            <li>
                                <a href="{{ URL::to('/') }}/Administrator/Evento">Evento</a>
                            </li>
                            <li>
                                <a href="{{ URL::to('/') }}/Administrator/Categoria">Categoria</a>
                            </li>
                        </ul>
                    </li>

                    <li>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">widgets</i>
                            <span>Módulos</span>
                        </a>
                        <ul class="ml-menu">
                            <li>
                                <a href="{{ URL::to('/') }}/Administrator/Slide">Slides</a>
                            </li>
                            <li>
                                <a href="{{ URL::to('/') }}/Administrator/Modulo/Menu">Menu</a>
                            </li>
                            <li>
                                <a href="{{ URL::to('/') }}/Administrator/Modulo/Video">Video</a>
                            </li>
                            <li>
                                <a href="{{ URL::to('/') }}/Administrator/Parceiro">Parceiros</a>
                            </li>
                        </ul>
                    </li>
                    <li class="active">
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">swap_calls</i>
                            <span>Artigo</span>
                        </a>
                        <ul class="ml-menu">
                            <li>
                                <a href="{{ URL::to('/') }}/Administrator/Artigo">Artigos</a>
                            </li>
                            <li>
                                <a href="{{ URL::to('/') }}/Administrator/Categoria">Categoria</a>
                            </li>

                        </ul>
                    </li>
                    <li>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">assignment</i>
                            <span>Documentos</span>
                        </a>
                        <ul class="ml-menu">
                            <li>
                                <a href="{{ URL::to('/') }}/Administrator/Documento">Documentos</a>
                            </li>
                            <li>
                                <a href="{{ URL::to('/') }}/Administrator/Categoria">Pastas</a>
                            </li>

                        </ul>
                    </li>
                    <li>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">perm_media</i>
                            <span>Media</span>
                        </a>
                        <ul class="ml-menu">
                            <li>
                                <a href="{{ URL::to('/') }}/Administrator/Imagem">Galeria</a>
                            </li>
                         <!--    <li>
                                <a href="{{ URL::to('/') }}/Administrator/Categoria">Albúns</a>
                            </li> -->

                        </ul>
                    </li>
                    <li>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">perm_media</i>
                            <span>FAQs</span>
                        </a>
                        <ul class="ml-menu">
                            <li>
                                <a href="{{ URL::to('/') }}/Administrator/Faq">Faq</a>
                            </li>
                            <li>
                                <a href="{{ URL::to('/') }}/Administrator/Categoria">Categoria</a>
                            </li>
                        </ul>
                    </li>
                      <li>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">perm_media</i>
                            <span>Links</span>
                        </a>
                        <ul class="ml-menu">
                            <li>
                                <a href="{{ URL::to('/') }}/Administrator/Link">Link</a>
                            </li>
                            <li>
                                <a href="{{ URL::to('/') }}/Administrator/Categoria">Categoria</a>
                            </li>
                        </ul>
                    </li>

                    <li>
                        <a href="{{ URL::to('/') }}/Administrator/Log">
                            <i class="material-icons">description</i>
                            <span>Logs</span>
                        </a>
                    </li>
                    
                </ul>
            </div>
            <!-- #Menu -->
            <!-- Footer -->
            <div class="legal">
                <div class="copyright">
                    &copy;
 2025 <a href="http://www.ideiacv.com" target="_blank">IDEIA, Lda CMS - </a>.
                </div>
                <div class="version">
                    <b>Version: </b> 2.0
                </div>
            </div>
            <!-- #Footer -->
        </aside>
    </section>