<!-- <div class="row clearfix"> -->
    <!-- Default Example -->
    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
        <div class="card">
             <div class="header">
                <h2>
                    {{$titulo}}

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
                                        <span class="font-10"> {{$tree['titulo'] }} ({{$tree['id'] }})</span>
                                    </div>

                                </div>
                                <ol class="dd-list">

                                    @if ($tree['childreen'])
                                    @forelse($tree['childreen'] as $item)


                                    <li class="dd-item" data-id="4">
                                        <div class="dd-handle">
                                            <div class="col-sm-2 nested_cmsli" class="pull-left">

                                                <a href="@if($item['ativado']==1){!! URL::to('/') !!}/Administrator/Item/despublicar/{{$item['id']}} @else {!! URL::to('/') !!}/Administrator/Item/publicar/{{$item['id']}} @endif" @if($item['ativado']==1) class="col-teal" @else class="col-red" @endif>

                                                    <i class="material-icons">@if($item['ativado']==1) done @else clear @endif</i>
                                                </a>

                                                <a href="{{ URL::to('/') }}/{{$edit1}}/{{$item['id']}}/{{$edit2}}" class="col-blue-grey">
                                                    <i class="material-icons">mode_edit</i>
                                                </a>

                                                <a id="remover$item['id']" data-target="event"  data-obj="#formItem"
                                                   data-rel='confirm' data-action="submit" alert-text="Deseja eliminar este item?" data-action="submit" href="{{ route('Item.destroy',$item['id']) }}" class="col-pink">
                                                    <i class="material-icons red">delete</i>
                                                </a>





                                            </div>

                                            <div class="col-sm-8 nested_cmsli " class="pull-right">
                                                <span class="font-10"> {{$item['titulo']}} </span>

                                            </div>

                                             <div class="col-sm-2 nested_cmsli pull-right">

                                                 <a href="{{ URL::to('/') }}/Administrator/Item/{{$item['id']}}/down" class="col-blue-grey">
                                                    <i class="material-icons">keyboard_arrow_down</i>
                                                </a>
                                                 <a href="{{ URL::to('/') }}//Administrator/Item/{{$item['id']}}/up" class="col-blue-grey">
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

                                                <a href="@if($item['ativado']==1){!! URL::to('/') !!}/Administrator/Item/despublicar/{{$item['id']}} @else {!! URL::to('/') !!}/Administrator/Item/publicar/{{$item['id']}} @endif" @if($item['ativado']==1) class="col-teal" @else class="col-red" @endif>
                                                    <i class="material-icons">@if($item['ativado']==1) done @else clear @endif</i>
                                                </a>

                                                <a href="{{ URL::to('/') }}/{{$edit1}}/{{$item['id']}}/{{$edit2}}" class="col-blue-grey">
                                                    <i class="material-icons">mode_edit</i>
                                                </a>

                                                <a id="remover$item['id']" data-target="event"  data-obj="#formItem"
                                                   data-rel='confirm' data-action="submit" alert-text="Deseja eliminar este item?"  href="{{route('Item.destroy',$item['id'])}}" class="col-pink">
                                                    <i class="material-icons red">delete</i>
                                                </a>
                                            </div>

                                            <div class="col-sm-8 nested_cmsli " class="pull-right">
                                                <span class="font-10"> {{$item['titulo']}} </span>

                                            </div>
                                               <div class="col-sm-2 nested_cmsli pull-right">

                                                 <a href="{{ URL::to('/') }}/Administrator/Item/{{$item['id']}}/down" class="col-blue-grey">
                                                    <i class="material-icons">keyboard_arrow_down</i>
                                                </a>
                                                 <a href="{{ URL::to('/') }}//Administrator/Item/{{$item['id']}}/up" class="col-blue-grey">
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


