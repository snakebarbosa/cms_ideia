@extends('Administrator.admin')



@section('title_content')

   Slides
@endsection

@section('stylesheet')
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
   <link rel="stylesheet" href=" https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css">

@endsection

@section('script')

  <script type="text/javascript" src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>

@endsection

@section('help')
  @include("Administrator.partials._rapido")
@endsection

@section('menu_content')
  <div class=" pull-right">

  <a href="{{ route('Slide.create') }}">
    <button type="button" class="btn btn-success waves-effect" >
      <i class="material-icons">add</i>
      <span>Novo Slide</span>
    </button>
  </a>



  <button id="publicar" type="button" data-target="event" data-obj="#formSlide" data-rel='submit' href="{{route('Slide.publicarcheck')}}" class="btn btn-info  waves-effect" >
      <i class="material-icons">done</i>
      <span>Publicar</span>
    </button>



    <button id="despublicar" type="button" data-target="event" data-obj="#formSlide" data-rel='submit' href="{{route('Slide.despublicarcheck')}}" class="btn bg-deep-orange  waves-effect" >
      <i class="material-icons">clear</i>
      <span>Despublicar</span>
    </button>



  <button id="remover" type="button" data-target="event" data-obj="#formSlide" data-rel='confirm' data-action="submit" alert-text="Deseja eliminar este Slide?"  href="{{route('Slide.removercheck')}}" class="btn btn-danger waves-effect" >
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
           {!! Form::open(array('route' => 'Art.publicarcheck', 'id'=>'formSlide')) !!}
          <table class="tableCMP table table-striped">
            <thead>
              <tr>
                <th>Ações</th>
                <th>Titulo</th>
                <th>Imagem</th>
                <th>Posiçāo</th>
                <th>Ordem</th>
                <th>Data Criação</th>
                <th>Data Publicaçāo</th>
                <th>Data Remoçāo</th>
                <th>Id</th>
              </tr>
            </thead>
            <tbody>

            @foreach ($data as $item)

                <tr>
                  <td>
                       <div class="icon-button-demo">
                        <input type="checkbox" name="check[]" value="{{$item->id}}" id="md_checkbox_{{$item->id}}" class="filled-in chk-col-green checkListar " />
                        <label style="height: 8px;"  for="md_checkbox_{{$item->id}}"></label>

                        <a type="button" class="btn botaoListar @if ($item->ativado) btn-info  @else bg-deep-orange @endif  btn-circle  waves-effect waves-circle waves-float" href =' @if ($item->ativado)  {!! URL::to('/') !!}/Administrator/Slide/despublicar/{{$item->id}} @else {!! URL::to('/') !!}/Administrator/Slide/publicar/{{$item->id}} @endif' >
                          <i class="material-icons">done</i>
                        </a>

                        <a type="button" class="btn botaoListar bg-lime btn-circle waves-effect waves-circle waves-float" href="{{ URL::to('/') }}/Administrator/Slide/{{$item->id}}/edit"  data-toggle="tooltip" data-placement="top" title="Editar">
                          <i class="material-icons">edit</i>
                        </a>

                        <a href="{{route('slide.ordenar',['id' => $item->id, 'order' => $item->order])}}" type="button" class="btn botaoListar bg-teal btn-circle waves-effect waves-circle waves-float"><i class="material-icons">arrow_upward</i></a>
                    </div>
                  </td>
                  <td>{{$item->alias}}</td>
                  <td>
                    @if($item->idImagem && $item->imagems)
                      <a href="javascript:void(0);" class="view-slide-image" data-image-url="{{ url('/') }}/files/images/{{ $item->imagems->url }}" data-image-title="{{ $item->imagems->titulo }}">
                        <img src="{{ url('/') }}/files/images/{{ $item->imagems->url }}" alt="{{ $item->imagems->titulo }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px; cursor: pointer;" class="img-thumbnail">
                      </a>
                    @else
                      <span class="text-muted" style="font-size: 11px;">
                        <i class="material-icons" style="font-size: 14px; vertical-align: middle;">hide_image</i>
                        Sem imagem
                      </span>
                    @endif
                  </td>
                  <td>{{ $item->posicao }}</td>
                  <td> {{$item->order}}.º</td>
                  <td>{{$item->created_at}}</td>
                  <td>{{$item->publicar}}</td>
                  <td>{{$item->despublicar}}</td>
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
<!-- Image Preview Modal -->
<div class="modal fade" id="imagePreviewModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="imagePreviewTitle">Imagem</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body text-center">
        <img id="imagePreviewImg" src="" alt="" style="max-width: 100%; height: auto;">
      </div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
    // Image preview click handler
    $('.view-slide-image').on('click', function() {
      var imageUrl = $(this).data('image-url');
      var imageTitle = $(this).data('image-title');
      
      $('#imagePreviewImg').attr('src', imageUrl);
      $('#imagePreviewTitle').text(imageTitle || 'Imagem do Slide');
      $('#imagePreviewModal').modal('show');
    });
  });
</script>
@endsection

