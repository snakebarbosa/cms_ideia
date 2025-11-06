<legend>Album</legend>


    <!-- Default Example -->

            <div class="body">

                  {!! Form::open(array('route' => 'Midia.categoria', 'id'=>'formMidia')) !!}
                    <div class="dd">
                        <ol class="dd-list">
                            <li  class="dd-item" data-id="0">
                                <div class="dd-handle">
                                     <div class="col-sm-2 nested_cmsli " class="pull-right">
                                                 <input  type="radio" data-obj="{{$tree['id']}}" name="iDcategoria" checked value="{{$tree['id']}}" id="catimg{{$tree['id']}}" class="demo-radio-button raizID" />
                                                <label   for="catimg{{$tree['id']}}"></label>
                                            </div>
                                    <div class="col-sm-8 nested_cmsli " class="pull-right">
                                        <span> {{$tree['titulo']}} </span>
                                    </div>

                                </div>
                                <ol class="dd-list">


                                    @if ($tree['childreen'])
                                    @forelse($tree['childreen'] as $item)


                                    <li class="dd-item" data-id="4">
                                        <div class="dd-handle">
                                             <div class="col-sm-2 nested_cmsli " class="pull-right">
                                                 <input  type="radio" name="iDcategoria" unchecked value="{{$item['id']}}" id="catimg{{$item['id']}}" class="demo-radio-button " />
                                                <label   for="catimg{{$item['id']}}"></label>
                                            </div>
                                            <div class="col-sm-4 nested_cmsli" class="pull-left">
                                                @if($delete == true)
                                                    <a href="@if($item['ativado']==1){!! URL::to('/') !!}/Administrator/Categoria/despublicar/{{$item['id']}}/imagem @else {!! URL::to('/') !!}/Administrator/Categoria/publicar/{{$item['id']}}/imagem @endif" @if($item['ativado']==1) class="col-teal" @else class="col-red" @endif>

                                                        <i class="material-icons">@if($item['ativado']==1) done @else clear @endif</i>
                                                    </a>
                                                 @endif

                                                <a href="{{ URL::to('/') }}/Administrator/Midia/{{$item['id']}}/imagem/editcat" class="col-blue-grey">
                                                    <i class="material-icons">mode_edit</i>
                                                </a>

                                                @if($delete == true)
                                                <a id="remover" data-obj="#formMidia"   data-target="event"
                                                   data-rel='confirm' data-action="submit" alert-text="Deseja eliminar esta Categoria?" href="{!! URL::to('/') !!}/Administrator/Categoria/delete/{{$item['id']}}/imagem" class="col-pink">
                                                    <i class="material-icons red">delete</i>
                                                </a>
                                                @endif
                                            </div>

                                            <div class="col-sm-8 nested_cmsli " class="pull-right">
                                                <span> {{$item['titulo']}} </span>

                                            </div>
                                        </div>
                                        <ol class="dd-list">

                                    @if ($item['childreen'])
                                    @forelse($item['childreen'] as $item)


                                    <li class="dd-item" data-id="4">
                                        <div class="dd-handle">
                                             <div class="col-sm-2 nested_cmsli " class="pull-right">
                                                 <input  type="radio" name="iDcategoria" unchecked value="{{$item['id']}}" id="catimg{{$item['id']}}" class="demo-radio-button " />
                                                <label   for="catimg{{$item['id']}}"></label>
                                            </div>
                                            <div class="col-sm-4 nested_cmsli" class="pull-left">
                                                 @if($delete == true)
                                                    <a href="@if($item['ativado']==1){!! URL::to('/') !!}/Administrator/Categoria/despublicar/{{$item['id']}}/imagem @else {!! URL::to('/') !!}/Administrator/Categoria/publicar/{{$item['id']}}/imagem @endif" @if($item['ativado']==1) class="col-teal" @else class="col-red" @endif>

                                                        <i class="material-icons">@if($item['ativado']==1) done @else clear @endif</i>
                                                    </a>
                                                @endif
                                                <a href="{{ URL::to('/') }}/Administrator/Midia/{{$item['id']}}/imagem/editcat" class="col-blue-grey">
                                                    <i class="material-icons">mode_edit</i>
                                                </a>

                                                 @if($delete == true)
                                                    <a id="remover" data-obj="#formMidia"   data-target="event"
                                                       data-rel='confirm' data-action="submit" alert-text="Deseja eliminar esta Categoria?" href="{!! URL::to('/') !!}/Administrator/Categoria/delete/{{$item['id']}}/imagem" class="col-pink">
                                                        <i class="material-icons red">delete</i>
                                                    </a>
                                                 @endif
                                            </div>

                                            <div class="col-sm-8 nested_cmsli " class="pull-right">
                                                <span> {{$item['titulo']}} </span>

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
