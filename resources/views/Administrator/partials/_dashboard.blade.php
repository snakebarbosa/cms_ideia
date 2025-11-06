
 <div class="container-fluid" >
              <!-- CPU Usage -->
            <div class="row clearfix">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="card">
                        <img src="{{ URL::to('/') }}/files/images/cmsideia_final_1024.png" width="40%">
                    </div>
                </div>
            </div>
            <!-- #END# CPU Usage -->


            @include("Administrator.partials._rapido")

            <div class="row clearfix">
                <!-- Visitors -->
                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <div class="card">
                             <div class="header">
                                <h2>ÚLTIMOS ARTIGOS</h2>
                                <ul class="header-dropdown m-r--5">
                                    <li class="dropdown">
                                        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                            <i class="material-icons">more_vert</i>
                                        </a>
                                        <ul class="dropdown-menu pull-right">
                                            <li><a href="{{ URL::to('/') }}/Administrator/Artigo">Ver Todos</a></li>
                                            <li><a href="{{ URL::to('/') }}/Administrator/Categoria">Ver Categorias</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                          <div class="body">
                            <div class="table-responsive">
                                <table class="table table-hover dashboard-task-infos responsive">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Título</th>
                                            <th>Categoria</th>
                                            <th>Acessos</th>
                                             <th>Criado Por</th>
                                            <th>Data Criação</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($artRec as $item)
                                            <tr>
                                                 <td>{{ $item->id}}</td>
                                                <td><small><a href="{{ URL::to('/')}}/Administrator/Artigo/{{$item->id}}/edit">{{ substr($item->alias, 0, 25)."..." }}</a></small></td>
                                                <td><small>{{ $item->categorias->titulo}}</small></td>
                                                <td>132</td>
                                                <td><small>{{ $item->user->name}}</small></td>
                                                <td><small>{{ date_format($item->created_at, 'Y-m-d')}}</small></td>
                                           </tr>

                                        @endforeach


                                    </tbody>
                                </table>
                            </div>
                            <ul class="dashboard-stat-list">

                            </ul>
                        </div>
                    </div>
                </div>
                <!-- #END# Visitors -->
                <!-- Latest Social Trends -->
                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <div class="card">

                           <div class="header">
                                <h2>ÚLTIMOS DOCUMENTOS</h2>
                                <ul class="header-dropdown m-r--5">
                                    <li class="dropdown">
                                        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                            <i class="material-icons">more_vert</i>
                                        </a>
                                        <ul class="dropdown-menu pull-right">
                                           <li><a href="{{ URL::to('/') }}/Administrator/Documento">Ver Todos</a></li>
                                            <li><a href="{{ URL::to('/') }}/Administrator/Documentacao/categoria">Ver Pastas</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        <div class="body">
                             <div class="table-responsive">
                                <table class="table table-hover dashboard-task-infos responsive">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Título</th>
                                            <th>Categoria</th>
                                            <th>Acessos</th>
                                             <th>Ficheiro</th>
                                             <th>Criado Por</th>
                                            <th>Data Criação</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($docRec as $item)
                                            @php
                                                $url2 = json_decode($item['url']);
                                            @endphp
                                            <tr>
                                                 <td>{{$item->id}}</td>
                                                <td><small><a href="{{URL::to('/')}}/Administrator/Documento/{{$item->id}}/edit">{{ substr($item->alias, 0, 25)."..." }}</a></small></td>
                                                <td><small>{{ $item->categorias->titulo}}</small></td>
                                                <td>132</td>
                                                <td><a class="btn btn-default fontBlue icon_download" target="_blank" href="{{ URL::to('/') }}/documento/opendoc/{{ $url2->pt }}" role="button"> <span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span></a></td>
                                                <td><small>{{ $item->user->name}}</small></td>
                                                <td><small>{{ $item->created_at}}</small></td>
                                           </tr>

                                        @endforeach


                                    </tbody>
                                </table>
                             </div>
                        </div>
                    </div>
                </div>
                <!-- #END# Latest Social Trends -->
                <!-- Answered Tickets -->
                <div class="col-xs-12 col-sm-12 col-md-3 col-lg-4">
                    <div class="card">

                           <div class="header">
                                <h2>ÚLTIMAS TAGS</h2>
                                <ul class="header-dropdown m-r--5">
                                    <li class="dropdown">
                                        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                            <i class="material-icons">more_vert</i>
                                        </a>
                                        <ul class="dropdown-menu pull-right">
                                           <li><a href="{{ URL::to('/') }}/Administrator/Tag">Ver Todos</a></li>

                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        <div class="body">
                            <div class="table-responsive">
                                 <table class="table table-hover dashboard-task-infos responsive">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Título</th>


                                            <th>Criado Por</th>
                                            <th>Data Criação</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($tagRec as $item)
                                            <tr>
                                                 <td>{{ $item->id}}</td>
                                                <td><small>{{ substr($item->name, 0, 25)."..."}}</small></td>
                                                <td><small>{{ $item->user->name}}</small></td>
                                                <td><small>{{ $item->created_at}}</small></td>
                                           </tr>

                                        @endforeach


                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- #END# Answered Tickets -->
            </div>

            <div class="row clearfix">
                <!-- Task Info -->
                <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                    <div class="card">
                        <div class="header">
                            <h2>Últimos Logins</h2>
                            <ul class="header-dropdown m-r--5">
                                <li class="dropdown">
                                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                        <i class="material-icons">more_vert</i>
                                    </a>
                                    <ul class="dropdown-menu pull-right">
                                        <li><a href="{{ URL::to('/') }}/Administrator/User">Ver Todos</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-hover dashboard-task-infos">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>User</th>
                                            <th>Email</th>
                                             <th>Permissão</th>
                                            <th>Último Login</th>
                                            <th>Criado em</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                         @foreach($users as $item)
                                            <tr>
                                                 <td>{{ $item->id}}</td>
                                                <td>{{ $item->name}}</td>
                                                <td>{{ $item->email}}</td>
                                                <td>@foreach($item->roles as $role) {{$role->name}} @endforeach</td>
                                                <td><small>{{ $item->data_last_login}}</small></td>
                                                <td><small>{{ $item->created_at}}</small></td>
                                                  <td> <i class="material-icons col-green">person</i></td>
                                                  <!-- col-red -->
                                           </tr>

                                        @endforeach
                                     <!--    <tr>
                                            <td>1</td>
                                            <td>Task A</td>
                                            <td><span class="label bg-green">Doing</span></td>
                                            <td>John Doe</td>
                                            <td>
                                                <div class="progress">
                                                    <div class="progress-bar bg-green" role="progressbar" aria-valuenow="62" aria-valuemin="0" aria-valuemax="100" style="width: 62%"></div>
                                                </div>
                                            </td>
                                        </tr> -->

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- #END# Task Info -->
                <!-- Browser Usage -->
                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <div class="card">
                        <div class="header">
                            <h2>BROWSER USAGE</h2>
                            <ul class="header-dropdown m-r--5">
                                <li class="dropdown">
                                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                        <i class="material-icons">more_vert</i>
                                    </a>
                                    <ul class="dropdown-menu pull-right">
                                        <li><a href="{{ URL::to('/') }}/Administrator/Log">Ver Todos</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <div class="body">
                            {{-- <div id="donut_chart" class="dashboard-donut-chart"></div> --}}
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Utilizador</th>
                                        <th>Ação</th>
                                        <th>Entidade</th>
                                        <th>Data Criação</th>
                                        <th>Data Actualização</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($logs as $log)
                                        <tr>
                                            <td>{{$log->causer->name}}</td>
                                            <td>{{$log->description}}</td>
                                            <td>{{$log->log_name}}</td>
                                            <td>{{$log->created_at}}</td>
                                            <td>{{$log->updated_at}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                                
                            
                        </div>
                    </div>
                </div>
                <!-- #END# Browser Usage -->
            </div>
        </div>