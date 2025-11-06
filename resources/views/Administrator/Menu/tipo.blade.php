@extends('Administrator.admin')



@section('title_content')

Menu
@endsection

@section('stylesheet')


@endsection

@section('help')
  @include("Administrator.partials._rapido")
@endsection

@section('script')


@endsection

@section('menu_content')

<div class=" pull-right">

  <a id="novo" href="{{ route('Tipo.create') }}">
    <button type="button" class="btn btn-success waves-effect" >
      <i class="material-icons">add</i>
      <span>Novo Menu</span>
    </button>
  </a>

   <button id="publicar" type="button" data-target="event" data-obj="#formMenu" data-rel='submit' href="{{route('Menu.publicarcheck')}}" class="btn btn-info  waves-effect" >
      <i class="material-icons">done</i>
      <span>Publicar</span>
    </button>



    <button id="despublicar" type="button" data-target="event" data-obj="#formMenu" data-rel='submit' href="{{route('Menu.despublicarcheck')}}" class="btn bg-deep-orange  waves-effect" >
      <i class="material-icons">clear</i>
      <span>Despublicar</span>
    </button>



    <button id="remover" type="button" data-target="event" data-obj="#formMenu" data-rel='confirm' data-action="submit" alert-text="Deseja eliminar este Menu?"  href="{{route('Menu.removercheck')}}" class="btn btn-danger waves-effect" >
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
           {!! Form::open(array('route' => 'Menu.publicarcheck', 'id'=>'formMenu')) !!}
          <table class="tableCMP table table-striped">
            <thead>
              <tr>
                <th>Ações</th>
                <th>Titulo</th>
                 <th>Posicao</th>
                <th>Data Criação</th>
                <th>Id</th>
                 <th>Ver items</th>
              </tr>
            </thead>
            <tbody>

             @foreach($data as $item)

             <tr>
              <td>

               <div class="icon-button-demo">


                <input type="checkbox" name="check[]" value="{{$item->id}}" id="md_checkbox_{{$item->id}}" class="filled-in chk-col-green checkListar " />
                <label style="height: 8px;"  for="md_checkbox_{{$item->id}}"></label>


                <a type="button" class="btn botaoListar @if ($item->ativado) btn-info  @else bg-deep-orange @endif  btn-circle  waves-effect waves-circle waves-float" href =' @if ($item->ativado)  {!! URL::to('/') !!}/Administrator/Tipo/despublicar/{{$item->id}} @else {!! URL::to('/') !!}/Administrator/Tipo/publicar/{{$item->id}} @endif' >
                  <i class="material-icons">done</i>
                </a>


                <a type="button" class="btn botaoListar bg-lime btn-circle waves-effect waves-circle waves-float" href="{{ URL::to('/') }}//Administrator/Tipo/{{$item->id}}/edit"  data-toggle="tooltip" data-placement="top" title="Editar">
                  <i class="material-icons">edit</i>
                </a>

              </div>
            </td>
            <td>{{$item->titulo}}</td>
             <td>{{$item->posicao}}</td>
           <td>{{$item->created_at}}</td>
           <td>{{$item->id}}</td>
            <td><a href="{{ URL::to('/') }}/Administrator/Item">Ver Items</a></td>


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
<script>
 $(document).ready(function(){
  // var table = $('#tabela').DataTable({
  //   processing: true,
  //             //serverSide: true,
  //             stateSave: true,
  //             'language': {
  //               'url': '//cdn.datatables.net/plug-ins/1.10.9/i18n/Portuguese.json'
  //             },
  //             data: {!! $data !!},
  //             columns: [
  //             {
  //               'mData': null,
  //               'title':'Publicado',
  //               'bSortable': false,
  //               'width':10,
  //               'className': 'stats-control',
  //               'mRender': function (o) {
  //                 if(o['ativado'] == 1){
  //                   return "<i class='glyphicon glyphicon-ok' style='color:green;'></i>";

  //                 }else{
  //                   return "<i class='glyphicon glyphicon-remove' style='color:red;'></i>";

  //                 }


  //               }
  //             },
  //             {
  //               'mData': null,
  //               'title':'Editar',
  //               'bSortable': false,
  //               'width':10,
  //               'className': 'editar-control',
  //               'mRender': function (o) {

  //                 return '<a data-item-id="'+o['id']+'" href="{{ URL::to('/') }}/Administrator/Tipo/'+o['id']+'/edit" class="btn btn-stats stats-button btn-primary" type="button">Editar</a>';

  //               }
  //             },

  //             { data: 'titulo', title: "Titulo" },
  //               // { data: 'doc',title: "Doc" },
  //               // { data: 'menu',title: "menu" },
  //               // { data: 'user', title: "user" },
  //               { data: 'id',title: "ID" }
  //               ]
  //             });


  // //**********************ACTION***************
  // //****************************
  // //Change row color
  // //***************************

  // $('#tabela tbody').on( 'click', 'tr', function () {
  //   if ( $(this).hasClass('selected') ) {
  //     $(this).removeClass('selected');
  //   }else {
  //     table.$('tr.selected').removeClass('selected');
  //     $(this).addClass('selected');
  //   }
  // });
  //   //****************************
  // //Get selected rows id
  // //***************************
  // $('#publicar').click( function () {
  //  var ids = $.map(table.rows('.selected').data(), function (item) {
  //   return item['id'];
  // });

  //       //console.log(ids)


  //       if(ids!=0){

  //        window.location.href = '{!! URL::to('/') !!}/Administrator/Tipo/publicar/'+ids[0]+'/menu';

  //      }
  //    });
  //   //****
  //   $('#despublicar').click( function () {
  //    var ids = $.map(table.rows('.selected').data(), function (item) {
  //     return item['id'];
  //   });



  //    if(ids!=0){

  //      window.location.href = '{!! URL::to('/') !!}/Administrator/Tipo/despublicar/'+ids[0]+'/menu';

  //    }
  //  });
  //    //****
  //    $('#remover').click( function () {
  //     var ids = $.map(table.rows('.selected').data(), function (item) {
  //       return item['id'];
  //     });

  //     if(ids!=0){
  //       if (confirm('Are You Sure?')){

  //        window.location.href = '{!! URL::to('/') !!}/Administrator/Tipo/delete/'+ids[0]+'/menu';
  //      }
  //    }


  //  });

   });
 </script>


 @endsection

