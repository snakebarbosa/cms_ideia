@extends('Administrator.admin')

@section('title_content')
    Detalhe Log
@endsection

@section('stylesheet')

@endsection

@section('script')


@endsection

@section('help')
  @include("Administrator.partials._rapido")
@endsection

@section('menu_content')

<div class=" pull-right">
  <a href="{{ route('Log.index') }}">
    <button type="button" class="btn btn-success waves-effect" >
      <i class="material-icons">keyboard_backspace</i>
      <span>Voltar</span>
    </button>
  </a>

</div>
@endsection


@section('content')

    <div class="content">
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
              <div>
                <h4>Utilizador:</h4>
                <p>{{$log->causer->name}}</p>
              </div>

              <div>
                <h4>Ação:</h4>
                <p>{{$log->description}}</p>
              </div>

              <div>
                <h4>Entidade:</h4>
                <p>{{$log->log_name}}</p>
              </div>

              <div>
                <h4>Data Criação:</h4>
                <p>{{$log->created_at}}</p>
              </div>

              <div>
                <h4>Data Atualização:</h4>
                <p>{{$log->updated_at}}</p>
              </div>

              <div>
                <h4>Mudanças</h4>
                <p>{{$log->changes}}</p>
              </div>
            </div>
        </div>
    </div>

@endsection

@section('script_bottom')

<!-- Custom Js -->




@endsection

