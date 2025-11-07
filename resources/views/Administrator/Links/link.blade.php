@extends('Administrator.admin')


@section('title_content')

   Links
@endsection

@section('stylesheet')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href=" https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css">
@endsection

@section('script')

@endsection

@section('help')
  @include("Administrator.partials._rapido")
@endsection

@section('menu_content')
  <div class=" pull-right">

        <a id="novo" href="{{ route('Link.create') }}">
        <button type="button" class="btn btn-success waves-effect" >
            <i class="material-icons">add</i>
            <span>Novo Link</span>
        </button>
    </a>

    <button id="publicar" type="button" data-target="event" data-obj="#formLink" data-rel='submit' href="{{route('Link.publicarcheck')}}" class="btn btn-info  waves-effect" >
      <i class="material-icons">done</i>
      <span>Publicar</span>
    </button>

    <button id="despublicar" type="button" data-target="event" data-obj="#formLink" data-rel='submit' href="{{route('Link.despublicarcheck')}}" class="btn bg-deep-orange  waves-effect" >
      <i class="material-icons">clear</i>
      <span>Despublicar</span>
    </button>


    <button id="remover" type="button" data-target="event" data-obj="#formLink" data-rel='confirm' data-action="submit" alert-text="Que queres eliminar Link?" href="{{route('Link.removercheck')}}" class="btn btn-danger waves-effect" >
      <i class="material-icons">remove</i>
      <span>Remover</span>
    </button>

    <button id="destacar" type="button" data-target="event" data-obj="#formLink" data-rel='submit' href="{{route('Link.destaquecheck')}}" class="btn bg-teal waves-effect" >
      <i class="material-icons">check_box</i>
      <span>Destaque</span>
    </button>

    <button id="rdestacar" type="button" data-target="event" data-obj="#formLink" data-rel='submit' href="{{route('Link.rdestaquecheck')}}" class="btn btn-warning waves-effect" >
      <i class="material-icons">check_box_outline_blank</i>
      <span>Retirar Destaque</span>
    </button>



  </div>
@endsection


@section('content')

  <div class="content">
    <div class="row clearfix">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">

          <div class="body table-responsive">
            {!! Form::open(array('route' => 'Link.publicarcheck', 'id'=>'formLink')) !!}
            <table class="tableCMP table table-striped">
              <thead>
                <tr>
                  <th>Ações</th>
                  <th>Titulo</th>
                  <th>Pasta</th>
                  <th>Tags</th>
                  <th>Acessos</th>
                  <th>Data Criação</th>
                  <th>Data Actualização</th>
                  <th>Id</th>
                </tr>
              </thead>
              <tbody>

              @foreach ($links as $item)

              <tr>
                <td>

                <div class="icon-button-demo">


                  <input type="checkbox" name="check[]" value="{{$item->id}}" id="md_checkbox_{{$item->id}}" class="filled-in chk-col-green checkListar " />
                  <label style="height: 8px;"  for="md_checkbox_{{$item->id}}"></label>


                  <a type="button" class="btn botaoListar @if ($item->ativado) btn-info  @else bg-deep-orange @endif  btn-circle  waves-effect waves-circle waves-float" href =' @if ($item->ativado)  {!! URL::to('/') !!}/Administrator/Link/despublicar/{{$item->id}} @else {!! URL::to('/') !!}/Administrator/Link/publicar/{{$item->id}} @endif' >
                    <i class="material-icons">done</i>
                  </a>

                  <a  type="button" class="btn botaoListar @if ($item->destaque) bg-teal  @else btn-warning @endif  btn-circle waves-effect waves-circle waves-float"  href =' @if ($item->destaque)  {!! URL::to('/') !!}/Administrator/Link/rdestacar/{{$item->id}} @else {!! URL::to('/') !!}/Administrator/Link/destacar/{{$item->id}} @endif' >
                    <i  class="material-icons">star</i>
                  </a>

                  <a type="button" class="btn botaoListar bg-lime btn-circle waves-effect waves-circle waves-float" href="{{ URL::to('/') }}/Administrator/Link/{{$item->id}}/edit"  data-toggle="tooltip" data-placement="top" title="Editar">
                    <i class="material-icons">edit</i>
                  </a>

                </div>
              </td>
              <td>{{$item->titulo}}</td>
              <td>{{$item->categorias->titulo}}</td>
              <td> @foreach($item->tags as $t)
              {{ $t->name }},
            @endforeach</td>
            <td>
              {{-- Polymorphic contador count --}}
              {{ $item->contadores->count() }}
              @php
                $clickCount = $item->contadores()->where('action_type', 'click')->count();
              @endphp
              @if($clickCount > 0)
                <small class="text-muted"> ({{$clickCount}} clicks)</small>
              @endif
            </td>
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


  // href = '{!! URL::to('/') !!}/Administrator/Link/destacar/'+ids[0];

  // href = '{!! URL::to('/') !!}/Administrator/Link/rdestacar/'+ids[0];


  //  href = '{!! URL::to('/') !!}/Administrator/Link/publicar/'+ids[0];

  //  href = '{!! URL::to('/') !!}/Administrator/Link/despublicar/'+ids[0];

  // href = '{!! URL::to('/') !!}/Administrator/Link/delete/'+ids[0];


   });
</script>


@endsection

