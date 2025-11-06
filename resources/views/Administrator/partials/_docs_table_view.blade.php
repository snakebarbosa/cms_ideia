@php
  if (isset($documento)) {
    $documentos = explode(',', $documento);
  }
@endphp
<div class="content">
  <div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
      <div class="card">

        <div class="body table-responsive">
           {!! Form::open(array('route' => 'Doc.publicarcheck', 'id'=>'formDoc')) !!}
          <table class="tableCMP table table-striped" @if($pageLength)  data-page-length='7' @endif>
            <thead>
              <tr>
                <th>Ações</th>
                <th>Titulo</th>
                <th>Pasta</th>

                @if ($buttons) {{-- $buttons uma variavel que quando uma view extends essa views escolhe se os titulos da tabela aparece ou nao--}}
                    <th>Tags</th>
                    <th>Tamanho Ficheiro</th>
                    <th>Acessos</th>
                 @endif

                <th>Data Criação</th>
                <th>Data Actualização</th>
                <th>Link</th>
                <th>Id</th>
              </tr>
            </thead>
            <tbody>

             @foreach ($documents as $item)
                @php
                    $url2 = json_decode($item['url']);
                @endphp
             <tr>
              <td>

               <div class="icon-button-demo">


                <input type="checkbox"  name="check[]" value="{{$item->id}}" id="md_checkbox_{{$item->id}}" class="filled-in chk-col-green checkBoxTable" @if(isset($documentos)) @if(in_array($item->id, $documentos)) checked @endif @endif  unchecked/>
                <label style="height: 8px;"  for="md_checkbox_{{$item->id}}"></label>


    
                @if ($buttons)
                    <a type="button" class="btn botaoListar @if ($item->ativado) btn-info  @else bg-deep-orange @endif  btn-circle  waves-effect waves-circle waves-float" href =' @if ($item->ativado)  {!! URL::to('/') !!}/Administrator/Documentacao/despublicar/{{$item->id}} @else {!! URL::to('/') !!}/Administrator/Documentacao/publicar/{{$item->id}} @endif'>
                    <i class="material-icons">done</i>
                    </a>

                    <a  type="button" class="btn botaoListar @if ($item->destaque) bg-teal  @else btn-warning @endif  btn-circle waves-effect waves-circle waves-float"  href =' @if ($item->destaque)  {!! URL::to('/') !!}/Administrator/Documentacao/rdestacar/{{$item->id}} @else {!! URL::to('/') !!}/Administrator/Documentacao/destacar/{{$item->id}} @endif' >
                    <i  class="material-icons">star</i>
                    </a>

                    <a type="button" class="btn botaoListar bg-lime btn-circle waves-effect waves-circle waves-float" href="{{ URL::to('/') }}/Administrator/Documento/{{$item->id}}/edit"  data-toggle="tooltip" data-placement="top" title="Editar">
                    <i class="material-icons">edit</i>
                    </a>
                @endif

              </div>
            </td>

            <td>{{$item->nome}}</td>

            <td><small>{{$item->categorias->titulo}}</small></td>

            @if ($buttons) 
                <td>
                    <small>
                        @foreach($item->tags as $t)
                            {{ $t->name }},
                        @endforeach
                    </small>
                </td>

                <td>
                    <small>{{ App\Helpers\Helpers::getDocumentFileSize($url2) }}</small>
                </td>

                <td>
                    {{-- Combined count: legacy + polymorphic --}}
                    {{ Count($item->contadores) }}
                    @php
                        $downloadCount = $item->contadores()->where('action_type', 'download')->count();
                        $viewCount = $item->contadores()->where('action_type', 'view')->count();
                    @endphp
                    @if($downloadCount > 0 || $viewCount > 0)
                        <small class="text-muted">
                            @if($viewCount > 0)({{$viewCount}} views)@endif
                            @if($downloadCount > 0)({{$downloadCount}} downloads)@endif
                        </small>
                    @endif
                </td>
            @endif

           <td>{{$item->data_criacao}}</td>
           <td>{{$item->updated_at}}</td> 
           <td><a class="btn btn-default fontBlue icon_download" target="_blank" href="{{ URL::to('/') }}/documento/opendoc/{{ $url2->pt }}" role="button"> <span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span></a></td>
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