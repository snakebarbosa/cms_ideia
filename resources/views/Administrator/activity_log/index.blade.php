@extends('Administrator.admin')

@section('title_content')

Actividades no Sistema
@endsection

@section('stylesheet')

@endsection

@section('script')


@endsection

@section('help')
  @include("Administrator.partials._rapido")
@endsection

@section('menu_content')


@endsection


@section('content')

    <div class="content">
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="body table-responsive">
                        <table class="tableCMP table table-striped">
                            <thead>
                                <tr>
                                    <th>Açōes</th>
                                    <th>Utilizador</th>
                                    <th>Ação</th>
                                    <th>Entidade</th>
                                    <th>Data Criação</th>
                                    <th>Data Actualização</th>
                                    <th>Id</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($logs as $log)
                                    <tr>
                                        <td><a href="{{route('Log.show', $log->id)}}" ><i class="material-icons">visibility</i></a></td>
                                        <td>{{$log->causer->name}}</td>
                                        <td>{{$log->description}}</td>
                                        <td>{{$log->log_name}}</td>
                                        <td>{{$log->created_at}}</td>
                                        <td>{{$log->updated_at}}</td>
                                        <td>{{$log->id}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script_bottom')

<!-- Custom Js -->




@endsection

