@extends('Administrator.admin')



@section('title_content')

   Tags
@endsection

@section('help')
  @include("Administrator.partials._rapido")
@endsection

@section('stylesheet')



@endsection

@section('script')


@endsection

@section('menu_content')
  <div class=" pull-right">

    <a id="novo" href="{{ route('Tag.create') }}">
        <button type="button" class="btn btn-success waves-effect" >
            <i class="material-icons">add</i>
            <span>Novo Tag</span>
        </button>
    </a>


    <button id="remover" data-target="event" data-obj="#formTag" data-rel='confirm' data-action="submit" alert-text="Que queres eliminar Tag?" href="{{route('Tag.removercheck')}}" class="btn btn-danger waves-effect" >
      <i class="material-icons">remove</i>
      <span>Remover</span>
    </button>




  </div>
@endsection


@section('content')

    <div class="content">

  <div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
      <div class="card">

        <div class="body table-responsive">
           {!! Form::open(array('route' => 'Tag.removercheck', 'id'=>'formTag')) !!}
          <table class="tableCMP table table-striped">
            <thead>
              <tr>
                <th>Ações</th>
                <th>Titulo</th>
                <th>Tags</th>
                <th>Data Criação</th>
                <th>Data Actualização</th>
                <th>Id</th>
              </tr>
            </thead>
            <tbody>

             @foreach ($tag as $item)

             <tr>
              <td>

               <div class="icon-button-demo">


                <input type="checkbox" name="check[]" value="{{$item->id}}" id="md_checkbox_{{$item->id}}" class="filled-in chk-col-green " unchecked />
                <label style="height: 8px;"  for="md_checkbox_{{$item->id}}"></label>




                <a type="button" class="btn botaoListar bg-lime btn-circle waves-effect waves-circle waves-float" href="{{ URL::to('/') }}/Administrator/Tag/{{$item->id}}/edit"  data-toggle="tooltip" data-placement="top" title="Editar">
                  <i class="material-icons">edit</i>
                </a>

              </div>
            </td>
            <td>{{$item->name}}</td>


           <td>   </td>

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

