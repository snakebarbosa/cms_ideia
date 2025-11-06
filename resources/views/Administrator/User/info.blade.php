@extends('Administrator.admin')

@push('stylesheet')

@endpush

@section('title_content')
  Mais Informações
@endsection


@section('resetButton')
    <button id="remover" type="button" data-target="event" data-action="nada" data-obj="#formUser" data-rel='confirm' data-url="{{route('user.resetpassword', $data->id)}}" alert-text="Resetar a palavra passe do Utilizador?" class="btn btn-warning waves-effect pull-right" >
      <i class="material-icons">check_box_outline_blank</i>
      <span>Reset Password</span>
    </button>
@endsection

@section('content')

 @include("Administrator.User.partial._navmenu")

<div class="row clearfix ">

               <!-- Basic Examples -->

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header " >
                            <h2>Histórico de Actividades </h2>
                             <ul class="header-dropdown m-r--5">
                                <li class="dropdown">
                                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                        <i class="material-icons">more_vert</i>
                                    </a>
                                    <ul class="dropdown-menu pull-right">
                                        <li><a href="javascript:void(0);">Mais Info</a></li>

                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <div class="body">
                         <div class="body">
                            <div class="table-responsive">
                                <table class="table table-hover dataTable js-exportable" id="tabela_proc">
                                    <thead>
                                      <tr>
                                        <th>Data</th>
                                        <th>Histórico</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      @forelse($atividades as $item)
                                      <tr>
                                       <td>{{ $item->created_at }}</td>
                                        <td>{{ $item->texto }}</td>

                                      </tr>
                                      @empty
                                      <tr>
                                        <td colspan="4">Utilizador sem histórico de atividades!</td>
                                      </tr>
                                      @endforelse


                                    </tbody>
                                  </table>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>

                <!-- #END# Basic Examples -->


            </div>

@endsection

<script>
  
</script>
@section('script_bottom2')

<script src="{{ URL::to('/') }}/js/pages/ui/dialogs.js"></script>

<script>
 
  $(function () {
    $('a.estadop').on('click', function () {
            confirmarEstado($(this).data('value'),$(this).data('estado'));
          });
    });

    function confirmarEstado(id, estado) {
        swal({
            title: "Tem a certeza?",
            text: "Após alterar o estado do Utilizador, este não poderá efectuar o login no bacckoffice!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Alterar",
            closeOnConfirm: false
        }, function () {
            window.location.href = '{{ URL::to('/') }}/Administrator/Admin/estado/'+id+'/'+estado;
        });
  }
</script>


@endsection