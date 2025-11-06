@extends('Administrator.admin')



@section('title_content')

   Idioma
@endsection

@section('stylesheet')

{{-- {{ $boinfo['logo']  }} --}}

@endsection

@section('script')

@section('help')
  @include("Administrator.partials._rapido")
@endsection


@endsection

@section('menu_content')
  <div class=" pull-right">

   <a href="{{ route('Language.create') }}">
    <button type="button" class="btn btn-success waves-effect" >
      <i class="material-icons">add</i>
      <span>Novo Idioma</span>
    </button>
  </a>

    <button id="publicar" type="button" data-target="event" data-obj="#formLang" data-rel='submit' href="{{route('Lang.publicarcheck')}}" class="btn btn-info  waves-effect" >
      <i class="material-icons">done</i>
      <span>Publicar</span>
    </button>

    <button id="despublicar" type="button" data-target="event" data-obj="#formLang" data-rel='submit' href="{{route('Lang.despublicarcheck')}}" class="btn btn-warning  waves-effect" >
      <i class="material-icons">clear</i>
      <span>Despublicar</span>
    </button>


    <button id="remover" type="button" data-target="event" data-obj="#formLang" data-rel='confirm' data-action="submit" alert-text="Que queres eliminar Linguagem?" href="{{route('Lang.removercheck')}}" class="btn btn-danger waves-effect" >
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

          {!! Form::open(array('route' => 'Lang.publicarcheck', 'id'=>'formLang')) !!}
          <table class="table table-striped">
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

             @foreach ($language as $item)

             <tr>
              <td>

               <div class="icon-button-demo">


                <input type="checkbox" name="check[]" value="{{$item->id}}" id="md_checkbox_{{$item->id}}" class="filled-in chk-col-green " unchecked />
                <label style="height: 8px;"  for="md_checkbox_{{$item->id}}"></label>


                <a type="button" class="btn botaoListar @if ($item->ativado) btn-info  @else bg-deep-orange @endif  btn-circle  waves-effect waves-circle waves-float" href =' @if ($item->ativado)  {!! URL::to('/') !!}/Administrator/Language/despublicar/{{$item->id}} @else {!! URL::to('/') !!}/Administrator/Language/publicar/{{$item->id}} @endif' >
                  <i class="material-icons">done</i>
                </a>



                <a type="button" class="btn botaoListar bg-lime btn-circle waves-effect waves-circle waves-float" href="{{ URL::to('/') }}/Administrator/Language/{{$item->id}}/edit"  data-toggle="tooltip" data-placement="top" title="Editar">
                  <i class="material-icons">edit</i>
                </a>

              </div>
            </td>
            <td>{{$item->titulo}}</td>
            <td>{{$item->tag}}</td>
           {{--  <td> @foreach($item->tags as $t)
             {{ $t->name }}
           @endforeach</td>  --}}

           {{-- <td>{{$item->tamanho}}</td> --}}
           <td>{{$item->created_at}}</td>
           <td>{{$item->updated_at}}</td>
           <td>{{$item->id}}</td>

           {{-- <td> --}}
            {{-- <input style="position: relative;left: 0;opacity: 1" type="checkbox" id="md-{{$item->id}}" name="id[]" class="filled-in chk-col-deep-orange" value="{{$item->id}}"></td> --}}

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
  //     var table = $('#tabela').DataTable({
  //           processing: true,
  //             //serverSide: true,
  //              'language': {
  //                 'url': '//cdn.datatables.net/plug-ins/1.10.9/i18n/Portuguese.json'
  //             },
  //             data: {!! $data !!},
  //             columns: [
  //                  {
		// 	            'mData': null,
		// 	            'title':'Publicado',
		// 	            'bSortable': false,
		// 	            'width':10,
		// 	             'className': 'stats-control',
		// 	           'mRender': function (o) {
		// 	                if(o['ativado'] == 1){
		// 	                  return "<i class='glyphicon glyphicon-ok' style='color:green;'></i>";

		// 	                }else{
		// 	                  return "<i class='glyphicon glyphicon-remove' style='color:red;'></i>";

		// 	                }


		// 	            }
		// 	          },
  //                {
  //                   'mData': null,
  //                   'title':'Editar',
  //                   'bSortable': false,
  //                    'width':10,
  //                    'className': 'editar-control',
  //                  'mRender': function (o) {

  //                     return '<a data-item-id="'+o['id']+'" href="{{ URL::to('/') }}/Administrator/Language/'+o['id']+'/edit" class="btn btn-stats stats-button btn-primary" type="button">Editar</a>';

  //                   }
  //               },

  //               { data: 'titulo', title: "Nome" },
  //                 { data: 'tag', title: "Código" },
  //               { data: 'id',title: "ID" }
  //             ]
  //         });


  // //**********************ACTION***************
  // //****************************
  // //Change row color
  // //***************************

  //   $('#tabela tbody').on( 'click', 'tr', function () {
  //       if ( $(this).hasClass('selected') ) {
  //           $(this).removeClass('selected');
  //       }else {
  //           table.$('tr.selected').removeClass('selected');
  //           $(this).addClass('selected');
  //       }
  //   });
  //   //****************************
  // //Get selected rows id
  // //***************************
  //    $('#publicar').click( function () {
  //      var ids = $.map(table.rows('.selected').data(), function (item) {
  //         return item['id'];
  //       });

  //       if(ids!=0){
  //          window.location.href = '{!! URL::to('/') !!}/Administrator/Language/publicar/'+ids[0];
  //        }
  //   });
  //   //****
  //    $('#despublicar').click( function () {
  //      var ids = $.map(table.rows('.selected').data(), function (item) {
  //         return item['id'];
  //       });

  //       if(ids!=0){
  //          window.location.href = '{!! URL::to('/') !!}/Administrator/Language/despublicar/'+ids[0];

  //       }
  //   });
  //    //****
  //     $('#remover').click( function () {
  //       var ids = $.map(table.rows('.selected').data(), function (item) {
  //         return item['id'];
  //       });

  //       if(ids!=0){
  //         if (confirm('Are You Sure?')){

  //          window.location.href = '{!! URL::to('/') !!}/Administrator/Language/delete/'+ids[0];
  //         }
  //       }


  //   });

   });
</script>


@endsection

