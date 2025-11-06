@extends('Administrator.admin')

@section('stylesheet')
  <link rel="stylesheet" href="{{ URL::asset('css/parsley.css') }}">
   <link rel="stylesheet" href="{{ URL::asset('js/summernote/summernote.css') }}">
    {{ Html::style('css/select2.min.css')}}
@endsection

@section('script')

    <script src="{{ URL::asset('js/parsley.js') }}"></script>
    <script src="{{ URL::asset('js/summernote/summernote.min.js') }}"></script>
    {{ Html::script('js/select2.min.js')}}
@endsection

@section('title_content')

  Add User
@endsection

@section('content')


            <div class="panel panel-default">
                <div class="panel-body">

                       {!! Form::open(array('route' => 'User.store','class' =>'form-horizontal')) !!}
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Name</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">Password</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <!-- <button type="submit" class="btn btn-primary">
                                    Register
                                </button> -->
                                 {{ Form::submit('Guardar', array('class'=>'btn btn-success btn-lg btn-block ')) }}
                            </div>
                        </div>
                 {!! Form::close() !!}
                </div>
            </div>


@endsection


@section('script_bottom')

       <!-- Scripts -->


@endsection
