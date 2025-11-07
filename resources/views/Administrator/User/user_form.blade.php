@extends('Administrator.admin')

@section('title_content')
    @if(isset($user)) Editar Users @else Novo Users @endif 
@endsection

@section('content')
    @if(isset($user->id) && $user->id)
        {!! Form::model($user, array('route' => array('User.update', $user->id), 'method' => 'PUT')) !!}
    @else
        {!! Form::open(array('route' => 'User.store')) !!}
    @endif

    @if( !isset($user))
        <div class=" pull-right topBottom">
            {{ Form::submit('Guardar', array('class'=>'btn btn-success btn-lg btn-block ')) }}
        </div> 
    @endif 

    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

           @if( isset($user))
             <input type="hidden" name="id" id="id" value="{{$user->id}}" >
          @endif

            <div class="card">
                <div class="header">
                    <h2>Preencha os campos</h2>
                </div>

                <div class="body">                                    
                    
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input id="name" type="text" class="form-control" name="name" value="{{ $user->name  ?? old('name') }}" required autofocus>
                                    @if ($errors->has('name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif
                                    <label class="form-label required">Nome</label>
                                </div>
                            </div>
                        </div>


                        <div class="col-sm-6">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input id="email" type="email" class="form-control" name="email" value="{{ $user->email ?? old('email') }}" required>
                                        @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                                    <label class="form-label required">E-Mail</label>
                                </div>
                            </div>
                        </div>

                        @if(!isset($user))
                            <div class="col-sm-6">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input id="password" type="password" class="form-control" name="password" required>
                                            @if ($errors->has('password'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('password') }}</strong>
                                            </span>
                                        @endif
                                        <label class="form-label required">Password</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                                        <label class="form-label required">Confirmar Password</label>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($can)
                            <div class="col-sm-6">
                                <div class="form-group form-float">
                                    <label class="form-label required">Nível de Acesso</label>
                                    <div class="form-line" >
                                        @foreach ($roles as $role)
                                            <input type="checkbox" id="checkbox_{{$role->id}}" name="roles[]" value="{{$role->id}}" @if(isset($roleUser)) @foreach($roleUser as $r) @if($role->id == $r->id) checked @endif @endforeach @endif/>
                                            <label for="checkbox_{{$role->id}}">{{$role->display_name}}</label>
                                        @endforeach


                                        <span class="help-block">
                                            <strong>{{ $errors->first('roles') }}</strong>
                                        </span>


                                    </div>
                                </div>
                            </div>
                        @endif

                        @if(isset($user))
                            <div class="form-group">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary">
                                        Actualizar
                                    </button>
                                    <a href="{{ route('User.password.form', $user->id) }}" class="btn btn-warning">
                                        <i class="material-icons">lock</i> Alterar Password
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
    <!-- #END# Exportable Table -->
  {{-- </div> --}}
</section>
@endsection


@push('script')
<!-- <script src="https://cdn.jsdelivr.net/npm/vue"></script>
<script src="https://unpkg.com/babel-polyfill@6.26.0/dist/polyfill.min.js"></script>
<script src="https://unpkg.com/bootstrap-vue@latest/dist/bootstrap-vue.js"></script> -->

<script>

  // var app = new Vue({
  //       el: '#app',
  //       data:{
  //         rolesSelected:[]
  //       }

  //   });
</script>



 <!-- <script src="{{ URL::to('/') }}/js/administrator/params.js"></script> -->

@endpush

