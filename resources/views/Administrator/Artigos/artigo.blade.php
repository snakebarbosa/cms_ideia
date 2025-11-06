@extends('Administrator.admin')

@section('title_content')

Artigos
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
  <a href="{{ route('Artigo.create') }}">
    <button type="button" class="btn btn-success waves-effect" >
      <i class="material-icons">add</i>
      <span>Novo Artigo</span>
    </button>
  </a>


    <button id="publicar" type="button" data-target="event" data-obj="#formArt" data-rel='submit' href="{{route('Art.publicarcheck')}}" class="btn btn-info  waves-effect" >
      <i class="material-icons">done</i>
      <span>Publicar</span>
    </button>



    <button id="despublicar" type="button" data-target="event" data-obj="#formArt" data-rel='submit' href="{{route('Art.despublicarcheck')}}" class="btn bg-deep-orange  waves-effect" >
      <i class="material-icons">clear</i>
      <span>Despublicar</span>
    </button>



    <button id="remover" type="button" data-target="event" data-obj="#formArt" data-rel='confirm' data-action="submit" alert-text="Deseja eliminar este Artigo?"  href="{{route('Art.removercheck')}}" class="btn btn-danger waves-effect" >
      <i class="material-icons">remove</i>
      <span>Remover</span>
    </button>


    <button id="destacar" type="button" data-target="event" data-obj="#formArt" data-rel='submit' href="{{route('Art.destaquecheck')}}"  class="btn bg-teal waves-effect" >
      <i class="material-icons">check_box</i>
      <span>Destaque</span>
    </button>


    <button id="rdestacar" type="button" data-target="event" data-obj="#formArt" data-rel='submit' href="{{route('Art.rdestaquecheck')}}"  class="btn btn-warning waves-effect" >
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
           {!! Form::open(array('route' => 'Art.publicarcheck', 'id'=>'formArt')) !!}
          <table class="tableCMP table table-striped">
            <thead>
              <tr>
                <th>Ações</th>
                <th>Titulo</th>
                <th>Imagem</th>
                <th>Pasta</th>
                <th>Tags</th>
                <th>Documentos</th>
                <th>Acessos</th>
                <th>Data Criação</th>
                <th>Data Actualização</th>
                <th>Id</th>
              </tr>
            </thead>
            <tbody>

             @foreach ($art as $item)

             <tr>
              <td>

               <div class="icon-button-demo">


                <input type="checkbox" name="check[]" value="{{$item->id}}" id="md_checkbox_{{$item->id}}" class="filled-in chk-col-green checkListar " />
                <label style="height: 8px;"  for="md_checkbox_{{$item->id}}"></label>


                <a type="button" class="btn botaoListar @if ($item->ativado) btn-info  @else bg-deep-orange @endif  btn-circle  waves-effect waves-circle waves-float" href =' @if ($item->ativado)  {!! URL::to('/') !!}/Administrator/Artigo/despublicar/{{$item->id}} @else {!! URL::to('/') !!}/Administrator/Artigo/publicar/{{$item->id}} @endif' >
                  <i class="material-icons">done</i>
                </a>

                <a  type="button" class="btn botaoListar @if ($item->destaque) bg-teal  @else btn-warning @endif  btn-circle waves-effect waves-circle waves-float"  href =' @if ($item->destaque)  {!! URL::to('/') !!}/Administrator/Artigo/rdestacar/{{$item->id}} @else {!! URL::to('/') !!}/Administrator/Artigo/destacar/{{$item->id}} @endif'>
                  <i  class="material-icons">star</i>
                </a>

                <a type="button" class="btn botaoListar bg-lime btn-circle waves-effect waves-circle waves-float" href="{{ URL::to('/') }}/Administrator/Artigo/{{$item->id}}/edit"  data-toggle="tooltip" data-placement="top" title="Editar">
                  <i class="material-icons">edit</i>
                </a>

              </div>
            </td>
            <td>{{$item->alias}}</td>
            <td>
              @if($item->idImagem && $item->imagems)
                <a href="javascript:void(0);" class="view-article-image" data-image-url="{{ URL::to('/') }}/images/{{ $item->imagems->url }}" data-image-title="{{ $item->imagems->titulo }}">
                  <img src="{{ URL::to('/') }}/images/{{ $item->imagems->url }}" alt="{{ $item->imagems->titulo }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px; cursor: pointer;" class="img-thumbnail">
                </a>
              @else
                <span class="text-muted" style="font-size: 11px;">
                  <i class="material-icons" style="font-size: 14px; vertical-align: middle;">hide_image</i>
                  Sem imagem
                </span>
              @endif
            </td>
            <td><small>{{$item->categorias->titulo}}</small></td>
            <td><small>
             @foreach($item->tags as $t)
           {{ $t->name }}
           @endforeach</small></td>
            <td>
              <span class="badge bg-blue" title="Documentos anexados">
                <i class="material-icons" style="font-size: 14px; vertical-align: middle;">description</i>
                {{$item->anexos->count()}}
              </span>
            </td>
            <td>
              {{-- Combined count: legacy + polymorphic --}}
              {{ Count($item->contadores) }}
              @php
                $viewCount = $item->contadores()->where('action_type', 'view')->count();
              @endphp
              @if($viewCount > 0)
                <small class="text-muted"> ({{$viewCount}} views)</small>
              @endif
            </td>
            <td>{{$item->data_criacao}}</td>
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

{{-- Modal para visualizar imagem do artigo --}}
<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="imageModalLabel">Imagem do Artigo</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body text-center">
        <img id="modalImage" src="" alt="" style="max-width: 100%; height: auto; border-radius: 4px;">
        <p id="modalImageTitle" class="mt-3 text-muted"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>
{{-- Fim Modal --}}

@endsection

@section('script_bottom')

<!-- Custom Js -->

<script type="text/javascript">
$(document).ready(function() {
    // Handle image click to open modal
    $('.view-article-image').on('click', function(e) {
        e.preventDefault();
        var imageUrl = $(this).data('image-url');
        var imageTitle = $(this).data('image-title');
        
        $('#modalImage').attr('src', imageUrl);
        $('#modalImage').attr('alt', imageTitle);
        $('#modalImageTitle').text(imageTitle);
        $('#imageModalLabel').text('Imagem: ' + imageTitle);
        
        $('#imageModal').modal('show');
    });
});
</script>

@endsection

