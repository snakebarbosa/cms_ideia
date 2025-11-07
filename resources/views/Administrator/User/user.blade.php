@extends('Administrator.admin')

@section('title_content')

   Users
@endsection

@section('stylesheet')
  <!-- <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
   <link rel="stylesheet" href=" https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css"> -->

@endsection

@section('script')
<!--
  <script type="text/javascript" src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script> -->

@endsection

@section('help')
  @include("Administrator.partials._rapido")
@endsection

@section('menu_content')
  <div class=" pull-right">
  @if($can)
    <a id="novo" href="{{ route('User.create') }}" >
      <button type="button" class="btn btn-success waves-effect" >
        <i class="material-icons">add</i>
        <span class="">Novo User</span>
      </button>
    </a>


    <button  id="ativar" data-target="event" data-target="event" data-obj="#formUser" data-rel='submit' href="{{route('User.ativarcheck')}}" type="button" class="btn btn-info  waves-effect" >
      <i class="material-icons">done</i>
      <span>Ativar</span>
    </button>



    <button id="desactivar" data-target="event" data-target="event" data-obj="#formUser" data-rel='submit' href="{{route('User.desativarcheck')}}" type="button" class="btn bg-deep-orange waves-effect" >
      <i class="material-icons">clear</i>
      <span>Desactivar</span>
    </button>


    <button id="remover" type="button" data-target="event" data-obj="#formUser" data-rel='confirm' data-action="submit" alert-text="Deseja eliminar este Utilizador?"  href="{{route('User.removercheck')}}" class="btn btn-danger waves-effect" >
      <i class="material-icons">remove</i>
      <span>Remover</span>
    </button>
    @endif

  </div>
@endsection


@section('content')

<div class="content">
  <div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
      <div class="card">

        <div class="body table-responsive">
           {!! Form::open(array('route' => 'User.ativarcheck', 'id'=>'formUser')) !!}
          <table class="tableCMP table table-striped">
            <thead>
              <tr>
                <th>Ações</th>
                <th>Nome</th>
                 <th>Email</th>
                 <th>Permissão</th>
                <th>Último Login</th>

                <th>Data Criação</th>
                <th>Data Actualização</th>

                <th>Id</th>
              </tr>
            </thead>
            <tbody>

             @foreach ($data as $item)

             <tr>
              <td>

               <div class="icon-button-demo">


                <input type="checkbox"  name="check[]" value="{{$item->id}}" id="md_checkbox_{{$item->id}}" class="filled-in chk-col-green " unchecked />
                <label style="height: 8px;"  for="md_checkbox_{{$item->id}}"></label>

                 @if($can)
                <a type="button" class="btn botaoListar @if ($item->ativado) btn-info  @else bg-deep-orange @endif  btn-circle  waves-effect waves-circle waves-float" href =' @if ($item->ativado)  {!! URL::to('/') !!}/Administrator/user/estado/{{$item->id}}/0 @else {!! URL::to('/') !!}/Administrator/user/estado/{{$item->id}}/1 @endif'>
                  <i class="material-icons">done</i>
                </a>

                <a type="button" class="btn botaoListar bg-purple btn-circle waves-effect waves-circle waves-float" href="{{ route('User.password.form', $item->id) }}"  data-toggle="tooltip" data-placement="top" title="Alterar Password">
                  <i class="material-icons">lock</i>
                </a>

                <a type="button" class="btn botaoListar bg-lime btn-circle waves-effect waves-circle waves-float" href="{{ route('User.edit', $item->id) }}"  data-toggle="tooltip" data-placement="top" title="Editar">
                  <i class="material-icons">edit</i>
                </a>
              @endif
              </div>
            </td>
            <td><a href="{{ route('User.show',$item->id)}}">{{$item->name}}</a></td>
          <td>{{$item->email}}</td>
          <td>@foreach($item->roles as $role) {{$role->name}} @endforeach</td>
            <td>{{$item->data_last_login}}</td>
           <td>{{$item->created_at}}</td>
           <td>{{$item->updated_at}}</td>
           <td>{{$item->id}}</td>


          </tr>
          @endforeach

        </tbody>
      </table>
       {!! Form::close() !!}
    </div>
  </div>
</div>
</div>
</div>


@endsection

@section('script_bottom')



@endsection

