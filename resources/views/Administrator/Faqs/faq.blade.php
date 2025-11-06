@extends('Administrator.admin')



@section('title_content')

FAQs
@endsection

@section('stylesheet')


@endsection

@section('script')

@section('help')
  @include("Administrator.partials._rapido")
@endsection

@endsection

@section('menu_content')
<div class=" pull-right">

  <a id="novo" href="{{ route('Faq.create') }}">
    <button type="button" class="btn btn-success waves-effect" >
      <i class="material-icons">add</i>
      <span>Novo Faq</span>
    </button>
  </a>


    <button id="publicar" type="button" data-target="event" data-obj="#formFaq" data-rel='submit' href="{{route('Faq.publicarcheck')}}" class="btn btn-info  waves-effect" >
      <i class="material-icons">done</i>
      <span>Publicar</span>
    </button>



    <button id="despublicar" type="button" data-target="event" data-obj="#formFaq" data-rel='submit' href="{{route('Faq.despublicarcheck')}}" class="btn bg-deep-orange   waves-effect" >
      <i class="material-icons">clear</i>
      <span>Despublicar</span>
    </button>


    <button id="remover" data-target="event" data-obj="#formFaq" data-rel='confirm' alert-text="Que queres eliminar Faq?" data-action="submit" href="{{route('Faq.removercheck')}}" type="button" class="btn btn-danger waves-effect"  >
      <i class="material-icons">remove</i>
      <span>Remover</span>
    </button>


    <button id="destacar" type="button" data-target="event" data-obj="#formFaq" data-rel='submit' href="{{route('Faq.destaquecheck')}}" class="btn bg-teal waves-effect" >
      <i class="material-icons">check_box</i>
      <span>Destaque</span>
    </button>



    <button  id="rdestacar" type="button" data-target="event" data-obj="#formFaq" data-rel='submit' href="{{route('Faq.rdestaquecheck')}}" type="button" class="btn btn-warning waves-effect" >
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

          {!! Form::open(array('route' => 'Faq.publicarcheck', 'id'=>'formFaq')) !!}
          <table class=" tableCMP table table-striped">
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

             @foreach ($faqs as $item)

             <tr>
              <td>

               <div class="icon-button-demo">


                <input type="checkbox" name="check[]" value="{{$item->id}}" id="md_checkbox_{{$item->id}}" class="filled-in chk-col-green " unchecked />
                <label style="height: 8px;"  for="md_checkbox_{{$item->id}}"></label>


                <a type="button" class="btn botaoListar @if ($item->ativado) btn-info  @else bg-deep-orange @endif  btn-circle  waves-effect waves-circle waves-float" href =' @if ($item->ativado)  {!! URL::to('/') !!}/Administrator/Faq/despublicar/{{$item->id}} @else {!! URL::to('/') !!}/Administrator/Faq/publicar/{{$item->id}} @endif' >
                  <i class="material-icons">done</i>
                </a>

                <a  type="button" class="btn botaoListar @if ($item->destaque) bg-teal  @else btn-warning @endif  btn-circle waves-effect waves-circle waves-float"  href =' @if ($item->destaque)  {!! URL::to('/') !!}/Administrator/Faq/rdestacar/{{$item->id}} @else {!! URL::to('/') !!}/Administrator/Faq/destacar/{{$item->id}} @endif' >
                  <i  class="material-icons">star</i>
                </a>

                <a type="button" class="btn botaoListar bg-lime btn-circle waves-effect waves-circle waves-float" href="{{ URL::to('/') }}/Administrator/Faq/{{$item->id}}/edit"  data-toggle="tooltip" data-placement="top" title="Editar">
                  <i class="material-icons">edit</i>
                </a>

              </div>
            </td>
            <td>{{$item->alias}}</td>
            <td>{{$item->categorias->titulo}}</td>


           <td>   </td>

           <td>
              {{-- Combined count: legacy + polymorphic --}}
              {{ Count($item->contador) + Count($item->contadores) }}
              @php
                $viewCount = $item->contadores()->where('action_type', 'view')->count();
              @endphp
              @if($viewCount > 0)
                <small class="text-muted"> ({{$viewCount}} views)</small>
              @endif
            </td>

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

