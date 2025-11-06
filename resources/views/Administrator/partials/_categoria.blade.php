<!-- <div class="row clearfix"> -->
    <!-- Default Example -->
    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
        <div class="card">
             <div class="header">
                <h2>
                    {{$titulo}}
                       <div class=" pull-right">
                          <a id="novo" href="{{ URL::to('/') }}/{{$create}}">
                            <button type="button" class="btn btn-success waves-effect" >
                                  <i class="material-icons">add</i>
                                  <span>{{$button}}</span>
                            </button>
                          </a>
                    </div>
                </h2>
             </div>
            <div class="body">
                <div class="clearfix m-b-20">

     {!! Form::open(array('route' => "$rota", 'id'=> "$idForm")) !!}
                    <div class="dd">
                        <ol class="dd-list">
                            <li  class="dd-item" data-id="0">
                                <div class="dd-handle">


                                    <div class="col-sm-10 nested_cmsli " class="pull-right">
                                        <span> {{$tree['titulo']}} </span>
                                    </div>

                                </div>
                                <ol class="dd-list">

                                    @if ($tree['childreen'])
                                    @forelse($tree['childreen'] as $item)


                                    <li class="dd-item" data-id="4">
                                        <div class="dd-handle">
                                            <div class="col-sm-2 nested_cmsli" class="pull-left">

                                                <a href="@if($item['ativado']==1){!! URL::to('/') !!}/Administrator/Categoria/despublicar/{{$item['id']}}/{{$type}} @else {!! URL::to('/') !!}/Administrator/Categoria/publicar/{{$item['id']}}/{{$type}} @endif" @if($item['ativado']==1) class="col-teal" @else class="col-red" @endif>

                                                    <i class="material-icons">@if($item['ativado']==1) done @else clear @endif</i>
                                                </a>

                                                <a href="{{ URL::to('/') }}/{{$edit1}}/{{$item['id']}}/{{$edit2}}" class="col-blue-grey">
                                                    <i class="material-icons">mode_edit</i>
                                                </a>

                                                <a id="remover" data-target="event"  data-obj="#{{$idForm}}"
                                                   data-rel='confirm' data-action="submit" alert-text="Deseja eliminar este item?" href="{!! URL::to('/') !!}/Administrator/Categoria/delete/{{$item['id']}}/{{$type}}" class="col-pink">
                                                    <i class="material-icons red">delete</i>
                                                </a>
                                            </div>

                                            <div class="col-sm-10 nested_cmsli " class="pull-right">
                                                <span class="font-10"> {{$item['titulo'] }} ({{$item['id'] }}) </span>

                                            </div>
                                            <div class="col-sm-2 nested_cmsli pull-right">

                                                 <a href="{{ URL::to('/') }}/Administrator/Categoria/{{$item['id']}}/down" class="col-blue-grey">
                                                    <i class="material-icons">keyboard_arrow_down</i>
                                                </a>
                                                 <a href="{{ URL::to('/') }}//Administrator/Categoria/{{$item['id']}}/up" class="col-blue-grey">
                                                    <i class="material-icons">keyboard_arrow_up</i>
                                                </a>

                                            </div>
                                        </div>
                                        <ol class="dd-list">

                                    @if ($item['childreen'])
                                    @forelse($item['childreen'] as $item)
                                    <li class="dd-item" data-id="4">
                                        <div class="dd-handle">
                                            <div class="col-sm-2 nested_cmsli" class="pull-left">

                                                <a href="@if($item['ativado']==1){!! URL::to('/') !!}/Administrator/Categoria/despublicar/{{$item['id']}}/{{$type}} @else {!! URL::to('/') !!}/Administrator/Categoria/publicar/{{$item['id']}}/{{$type}} @endif" @if($item['ativado']==1) class="col-teal" @else class="col-red" @endif>

                                                    <i class="material-icons">@if($item['ativado']==1) done @else clear @endif</i>
                                                </a>

                                                <a href="{{ URL::to('/') }}/{{$edit1}}/{{$item['id']}}/{{$edit2}}" class="col-blue-grey">
                                                    <i class="material-icons">mode_edit</i>
                                                </a>

                                                <a id="remover" data-target="event"  data-obj="#{{$idForm}}"
                                                   data-rel='confirm' data-action="submit" alert-text="Deseja eliminar este item?" href="{!! URL::to('/') !!}/Administrator/Categoria/delete/{{$item['id']}}/{{$type}}" class="col-pink">
                                                    <i class="material-icons red">delete</i>
                                                </a>
                                            </div>

                                            <div class="col-sm-10 nested_cmsli " class="pull-right">
                                                <span class="font-9">{{$item['titulo'] }}({{$item['id'] }})</span>

                                            </div>
                                            <div class="col-sm-2 nested_cmsli pull-right">

                                                 <a href="{{ URL::to('/') }}/Administrator/Categoria/{{$item['id']}}/down" class="col-blue-grey">
                                                    <i class="material-icons">keyboard_arrow_down</i>
                                                </a>
                                                 <a href="{{ URL::to('/') }}//Administrator/Categoria/{{$item['id']}}/up" class="col-blue-grey">
                                                    <i class="material-icons">keyboard_arrow_up</i>
                                                </a>

                                            </div>
                                        </div>


                                    </li>
                                    @empty
                                    @endforelse
                                    @endif

                                </ol>


                                    </li>
                                    @empty
                                    @endforelse
                                    @endif

                                </ol>
                            </li>


                        </ol>
                    </div>
             {!! Form::close() !!}
                </div>

            </div>

        </div>
    </div>
<!-- </div> -->
