@extends('Administrator.admin')

@section('title_content')
Resultados da Pesquisa
@endsection

@section('stylesheet')
<style>
.nav-tabs {
    margin-bottom: 20px;
}
.nav-tabs > li > a {
    color: #333;
}
.nav-tabs > li.active > a,
.nav-tabs > li.active > a:hover,
.nav-tabs > li.active > a:focus {
    color: #fff;
    background-color: #00bcd4;
    border-color: #00bcd4;
}
.badge {
    background-color: #ff5722;
    margin-left: 5px;
}
.search-result-item {
    padding: 15px;
    border-bottom: 1px solid #eee;
    transition: background-color 0.2s;
}
.search-result-item:hover {
    background-color: #f5f5f5;
}
.search-result-title {
    font-size: 16px;
    font-weight: 500;
    color: #00bcd4;
    margin-bottom: 5px;
}
.search-result-meta {
    font-size: 12px;
    color: #999;
    margin-bottom: 5px;
}
.search-result-tags {
    font-size: 12px;
    color: #666;
}
.search-result-tags .tag {
    display: inline-block;
    padding: 2px 8px;
    margin-right: 5px;
    background-color: #e0e0e0;
    border-radius: 3px;
}
.no-results {
    text-align: center;
    padding: 40px;
    color: #999;
}
.search-info {
    margin-bottom: 20px;
    padding: 15px;
    background-color: #f5f5f5;
    border-radius: 4px;
}
.search-info strong {
    color: #00bcd4;
}
</style>
@endsection

@section('help')
  @include("Administrator.partials._rapido")
@endsection

@section('menu_content')
<div class="pull-right">
  <a href="{{ url()->previous() }}">
    <button type="button" class="btn btn-default waves-effect">
      <i class="material-icons">arrow_back</i>
      <span>Voltar</span>
    </button>
  </a>
</div>
@endsection

@section('content')
<div class="content">
  <div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
      <div class="card">
        <div class="header">
          <h2>
            RESULTADOS DA PESQUISA
            <small>Pesquisa por: <strong>"{{ $searchTerm }}"</strong></small>
          </h2>
        </div>

        <div class="body">
          @if($artigos->count() == 0 && $documentos->count() == 0)
          <div class="no-results">
            <i class="material-icons" style="font-size: 64px; color: #ddd;">search</i>
            <h4>Nenhum resultado encontrado</h4>
            <p>Tente pesquisar com outros termos.</p>
          </div>
          @else
          <div class="search-info">
            <strong>Total de resultados:</strong> {{ $artigos->count() + $documentos->count() }}
            ({{ $artigos->count() }} artigos, {{ $documentos->count() }} documentos)
          </div>

          <!-- Nav tabs -->
          <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="{{ $artigos->count() > 0 ? 'active' : '' }}">
              <a href="#artigos" aria-controls="artigos" role="tab" data-toggle="tab">
                <i class="material-icons" style="vertical-align: middle; font-size: 18px;">article</i>
                Artigos 
                <span class="badge">{{ $artigos->count() }}</span>
              </a>
            </li>
            <li role="presentation" class="{{ $artigos->count() == 0 && $documentos->count() > 0 ? 'active' : '' }}">
              <a href="#documentos" aria-controls="documentos" role="tab" data-toggle="tab">
                <i class="material-icons" style="vertical-align: middle; font-size: 18px;">description</i>
                Documentos 
                <span class="badge">{{ $documentos->count() }}</span>
              </a>
            </li>
          </ul>

          <!-- Tab panes -->
          <div class="tab-content">
            <!-- Artigos Tab -->
            <div role="tabpanel" class="tab-pane {{ $artigos->count() > 0 ? 'active' : '' }}" id="artigos">
              @if($artigos->count() > 0)
                @foreach($artigos as $item)
                <div class="search-result-item">
                  <div class="search-result-title">
                    <a href="{{ route('Artigo.edit', $item->id) }}">
                      {{ $item->alias }}
                    </a>
                  </div>
                  <div class="search-result-meta">
                    <span><i class="material-icons" style="font-size: 14px; vertical-align: middle;">folder</i> 
                      {{ $item->categorias->titulo ?? 'Sem categoria' }}</span>
                    <span style="margin-left: 15px;"><i class="material-icons" style="font-size: 14px; vertical-align: middle;">calendar_today</i> 
                      {{ $item->created_at->format('d/m/Y') }}</span>
                    <span style="margin-left: 15px;"><i class="material-icons" style="font-size: 14px; vertical-align: middle;">{{ $item->ativado ? 'check_circle' : 'cancel' }}</i> 
                      {{ $item->ativado ? 'Publicado' : 'Não publicado' }}</span>
                    @if($item->destaque)
                    <span style="margin-left: 15px;"><i class="material-icons" style="font-size: 14px; vertical-align: middle; color: #ffc107;">star</i> 
                      Destaque</span>
                    @endif
                  </div>
                  @if($item->tags && $item->tags->count() > 0)
                  <div class="search-result-tags">
                    <i class="material-icons" style="font-size: 14px; vertical-align: middle;">label</i>
                    @foreach($item->tags as $tag)
                      <span class="tag">{{ $tag->name }}</span>
                    @endforeach
                  </div>
                  @endif
                  <div style="margin-top: 10px;">
                    <a href="{{ route('Artigo.edit', $item->id) }}" class="btn btn-xs bg-light-blue waves-effect">
                      <i class="material-icons" style="font-size: 16px;">edit</i> Editar
                    </a>
                    @if($item->ativado)
                    <a href="{{ URL::to('/') }}/Administrator/Artigo/despublicar/{{ $item->id }}" class="btn btn-xs bg-deep-orange waves-effect">
                      <i class="material-icons" style="font-size: 16px;">unpublished</i> Despublicar
                    </a>
                    @else
                    <a href="{{ URL::to('/') }}/Administrator/Artigo/publicar/{{ $item->id }}" class="btn btn-xs btn-info waves-effect">
                      <i class="material-icons" style="font-size: 16px;">publish</i> Publicar
                    </a>
                    @endif
                    @if($item->destaque)
                    <a href="{{ URL::to('/') }}/Administrator/Artigo/rdestacar/{{ $item->id }}" class="btn btn-xs btn-warning waves-effect">
                      <i class="material-icons" style="font-size: 16px;">star_outline</i> Remover Destaque
                    </a>
                    @else
                    <a href="{{ URL::to('/') }}/Administrator/Artigo/destacar/{{ $item->id }}" class="btn btn-xs bg-teal waves-effect">
                      <i class="material-icons" style="font-size: 16px;">star</i> Destacar
                    </a>
                    @endif
                  </div>
                </div>
                @endforeach
              @else
                <div class="no-results">
                  <i class="material-icons" style="font-size: 48px; color: #ddd;">article</i>
                  <p>Nenhum artigo encontrado.</p>
                </div>
              @endif
            </div>

            <!-- Documentos Tab -->
            <div role="tabpanel" class="tab-pane {{ $artigos->count() == 0 && $documentos->count() > 0 ? 'active' : '' }}" id="documentos">
              @if($documentos->count() > 0)
                @foreach($documentos as $item)
                <div class="search-result-item">
                  <div class="search-result-title">
                    <a href="{{ route('Documento.edit', $item->id) }}">
                      <i class="material-icons" style="font-size: 18px; vertical-align: middle; color: #f44336;">picture_as_pdf</i>
                      {{ $item->nome }}
                    </a>
                  </div>
                  <div class="search-result-meta">
                    @if($item->categorias)
                    <span><i class="material-icons" style="font-size: 14px; vertical-align: middle;">folder</i> 
                      {{ $item->categorias->titulo }}</span>
                    @endif
                    @if($item->tipos)
                    <span style="margin-left: 15px;"><i class="material-icons" style="font-size: 14px; vertical-align: middle;">category</i> 
                      {{ $item->tipos->tipo }}</span>
                    @endif
                    <span style="margin-left: 15px;"><i class="material-icons" style="font-size: 14px; vertical-align: middle;">calendar_today</i> 
                      {{ $item->created_at->format('d/m/Y') }}</span>
                    <span style="margin-left: 15px;"><i class="material-icons" style="font-size: 14px; vertical-align: middle;">{{ $item->ativado ? 'check_circle' : 'cancel' }}</i> 
                      {{ $item->ativado ? 'Publicado' : 'Não publicado' }}</span>
                    @if($item->destaque)
                    <span style="margin-left: 15px;"><i class="material-icons" style="font-size: 14px; vertical-align: middle; color: #ffc107;">star</i> 
                      Destaque</span>
                    @endif
                  </div>
                  @if($item->tags && $item->tags->count() > 0)
                  <div class="search-result-tags">
                    <i class="material-icons" style="font-size: 14px; vertical-align: middle;">label</i>
                    @foreach($item->tags as $tag)
                      <span class="tag">{{ $tag->name }}</span>
                    @endforeach
                  </div>
                  @endif
                  @if($item->processedUrl)
                  <div class="search-result-meta" style="margin-top: 5px;">
                    <i class="material-icons" style="font-size: 14px; vertical-align: middle;">insert_drive_file</i>
                    <span style="font-family: monospace;">{{ $item->processedUrl }}</span>
                  </div>
                  @endif
                  <div style="margin-top: 10px;">
                    <a href="{{ route('Documento.edit', $item->id) }}" class="btn btn-xs bg-light-blue waves-effect">
                      <i class="material-icons" style="font-size: 16px;">edit</i> Editar
                    </a>
                    @if($item->processedUrl)
                    <a href="{{ URL::to('/') }}/documento/opendoc/{{ $item->processedUrl }}" target="_blank" class="btn btn-xs bg-green waves-effect">
                      <i class="material-icons" style="font-size: 16px;">visibility</i> Ver
                    </a>
                    @endif
                    @if($item->ativado)
                    <a href="{{ URL::to('/') }}/Administrator/Documento/despublicar/{{ $item->id }}" class="btn btn-xs bg-deep-orange waves-effect">
                      <i class="material-icons" style="font-size: 16px;">unpublished</i> Despublicar
                    </a>
                    @else
                    <a href="{{ URL::to('/') }}/Administrator/Documento/publicar/{{ $item->id }}" class="btn btn-xs btn-info waves-effect">
                      <i class="material-icons" style="font-size: 16px;">publish</i> Publicar
                    </a>
                    @endif
                    @if($item->destaque)
                    <a href="{{ URL::to('/') }}/Administrator/Documento/rdestacar/{{ $item->id }}" class="btn btn-xs btn-warning waves-effect">
                      <i class="material-icons" style="font-size: 16px;">star_outline</i> Remover Destaque
                    </a>
                    @else
                    <a href="{{ URL::to('/') }}/Administrator/Documento/destacar/{{ $item->id }}" class="btn btn-xs bg-teal waves-effect">
                      <i class="material-icons" style="font-size: 16px;">star</i> Destacar
                    </a>
                    @endif
                  </div>
                </div>
                @endforeach
              @else
                <div class="no-results">
                  <i class="material-icons" style="font-size: 48px; color: #ddd;">description</i>
                  <p>Nenhum documento encontrado.</p>
                </div>
              @endif
            </div>
          </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

@section('script_bottom')
<script>
$(document).ready(function() {
    // Initialize tooltips if needed
    $('[data-toggle="tooltip"]').tooltip();
    
    // Tab change animation
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        $($(e.target).attr('href')).addClass('animated fadeIn');
    });
});
</script>
@endsection
