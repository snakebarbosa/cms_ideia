<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h3>
                  {{$data->name}}

                </h3>

                    <p class="header-info-item">
                            @if($data->ativado == 1)
                               <span class='label bg-green'>Activado</span>
                            @else
                               <span class='label bg-red'>Desativado</span>
                            @endif
                        </p>
                <p class="no-margin">
                      <b>Email:</b>
                      <span>{{$data->email}}</span>
                    </p>
               
                <p class="no-margin">
                      <b>Níveis de Acesso:</b><span>
                       @foreach ($data->roles as $role)
                                        {{ $role->name}},
                                          @endforeach
                     </span>
                    </p>
                 <p class="no-margin">
                      <b>Data do Registo:</b>
                      <span>{{$data->created_at}}</span>
                    </p>
                     <p class="no-margin">
                      <b>Data do Último Login:</b>
                      <span>{{$data->data_last_login}}</span>
                    </p>

             <!-- *************** -->
                <ul class="header-dropdown m-r--5">
                    <li class="dropdown">
                        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <i class="material-icons">more_vert</i>
                        </a>
                         <ul class="dropdown-menu pull-right">
                            <li><a href="{{ URL::to('/') }}/user/estado/{{$data->id}}/1" data-value="{{$data->id}}" data-estado="1" class="estadop">Activar</a></li>
                            <li><a href="{{ URL::to('/') }}/user/estado/{{$data->id}}/0" data-value="{{$data->id}}" data-estado="0" class="estadop">Desactivar</a></li>

                        </ul>

                    </li>
                </ul>
            </div>
          <!--   <div class="body body-tab" >

                        <ul class="nav nav-tabs tab-col-orange" role="tablist">
                            <li role="presentation" class="active" >
                                <a href="{{ URL::to('/') }}/Administrator/Admin/Nav/info/2" >Informação</a>
                            </li>
                            <li role="presentation" >
                                <a href="{{ URL::to('/') }}/Administrator/Admin/Nav/historico/2" >Histórico</a>
                            </li>

                        </ul>

            </div> -->
        </div>
    </div>
</div>
