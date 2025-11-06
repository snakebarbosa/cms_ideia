@extends('Administrator.admin')


@section('title_content')
Pastas e Categorias
@endsection

@section('stylesheet')

<style type="text/css">

    .nested_cmsli{
        margin: 0px!important;
		padding: 0;
		height: 22px;
    }
</style>

<!-- JQuery Nestable Css -->
<link href="{{URL::to('/')}}/plugins/nestable/jquery-nestable.css" rel="stylesheet" />

@endsection

@section('script')


@endsection

@section('help')
  @include("Administrator.partials._rapido")
@endsection

@section('menu_content')

@endsection

@section('content')

@include('Administrator.partials._categoria', ['type'=>'artigo','titulo'=>'Categoria Artigos','create'=>'Administrator/Categoria/create/'.'art','button'=>'Nova Categoria','idForm'=>'formCat','tree' => $tree, 'rota' => 'showCatArt', 'edit1' => "Administrator/Categoria", 'edit2' => "edit"])

@include('Administrator.partials._categoria', ['type'=>'documento','titulo'=>'Pastas Documentos','create'=>'Administrator/Categoria/create/'.'documento','button'=>'Nova pasta','idForm'=>'formDocCat','tree' => $treedoc, 'rota' => 'Documentacao.categoria','edit1' => "Administrator/Documentacao", 'edit2' => "documento/editcat"])

@include('Administrator.partials._categoria', ['type'=>'link','titulo'=>'Pastas Links','create'=>'Administrator/Categoria/create/'.'link','button'=>'Nova pasta','idForm'=>'formCatLink','tree' => $treelink, 'rota' => 'Link.categoria', 'edit1' => "Administrator/Links", 'edit2' => "link/editcat"])

@include('Administrator.partials._categoria', ['type'=>'faq','titulo'=>'Pastas FAQs','create'=>'Administrator/Categoria/create/'.'faq','button'=>'Nova Categoria','idForm'=>'formFaqCat','tree' => $treefaq, 'rota' => 'Faq.categoria', 'edit1' => "Administrator/Faqs", 'edit2' => "faq/editcat"])

@include('Administrator.partials._categoria', ['type'=>'evento','titulo'=>'Pastas Eventos','create'=>'Administrator/Categoria/create/'.'evento','button'=>'Nova Categoria','idForm'=>'formEventoCat','tree' => $treeevento, 'rota' => 'Evento.categoria', 'edit1' => "Administrator/Eventos", 'edit2' => "evento/editcat"])

{{-- @include('Administrator.partials._categoria', ['type'=>'artigo','titulo'=>'Categoria Artigos','create'=>'/Administrator/Categoria/create','button'=>'Nova Categoria','idForm'=>'formCat','tree' => $tree, 'rota' => 'showCatArt', 'edit1' => "Administrator/Categoria", 'edit2' => "edit"]) --}}

{{-- @include('Administrator.partials._categoria', ['type'=>'documento','titulo'=>'Pastas Documentos','create'=>'Administrator/Documentacao/createDoc','button'=>'Nova pasta','idForm'=>'formDocCat','tree' => $treedoc, 'rota' => 'Documentacao.categoria','edit1' => "Administrator/Documentacao", 'edit2' => "documento/editcat"]) --}}

{{-- @include('Administrator.partials._categoria', ['type'=>'link','titulo'=>'Pastas Links','create'=>'Administrator/Links/createlink','button'=>'Nova pasta','idForm'=>'formCatLink','tree' => $treelink, 'rota' => 'Link.categoria', 'edit1' => "Administrator/Links", 'edit2' => "link/editcat"]) --}}

{{-- @include('Administrator.partials._categoria', ['type'=>'faq','titulo'=>'Pastas FAQs','create'=>'Administrator/Faqs/createfaq','button'=>'Nova Categoria','idForm'=>'formFaqCat','tree' => $treefaq, 'rota' => 'Faq.categoria', 'edit1' => "Administrator/Faqs", 'edit2' => "faq/editcat"]) --}}

{{-- @include('Administrator.partials._categoria', ['type'=>'evento','titulo'=>'Pastas Eventos','create'=>'Administrator/Eventos/createevento','button'=>'Nova Categoria','idForm'=>'formEventoCat','tree' => $treeevento, 'rota' => 'Evento.categoria', 'edit1' => "Administrator/Eventos", 'edit2' => "evento/editcat"]) --}}



@endsection

@section('script_bottom')


@endsection
