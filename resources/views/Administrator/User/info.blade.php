@extends('Administrator.admin')

@push('stylesheet')
<style>
.nav-tabs {
    border-bottom: 2px solid #ddd;
}
.nav-tabs > li > a {
    border-radius: 0;
    color: #666;
}
.nav-tabs > li.active > a {
    border-bottom: 3px solid #2196F3;
    color: #2196F3;
    font-weight: bold;
}
.stats-card {
    text-align: center;
    padding: 20px;
    border-radius: 5px;
    margin-bottom: 20px;
}
.stats-card h3 {
    font-size: 48px;
    margin: 10px 0;
    font-weight: bold;
}
.stats-card p {
    font-size: 16px;
    color: #666;
}
</style>
@endpush

@section('title_content')
  Mais Informações - {{ $data->name }}
@endsection


@section('resetButton')
    <button id="remover" type="button" data-target="event" data-action="nada" data-obj="#formUser" data-rel='confirm' data-url="{{route('user.resetpassword', $data->id)}}" alert-text="Resetar a palavra passe do Utilizador?" class="btn btn-warning waves-effect pull-right" >
      <i class="material-icons">check_box_outline_blank</i>
      <span>Reset Password</span>
    </button>
@endsection

@section('content')

 @include("Administrator.User.partial._navmenu")

<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="body">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active">
                        <a href="#activity" data-toggle="tab" aria-expanded="true">
                            <i class="material-icons">history</i> HISTÓRICO DE ATIVIDADES
                        </a>
                    </li>
                    <li role="presentation">
                        <a href="#statistics" data-toggle="tab" aria-expanded="false">
                            <i class="material-icons">bar_chart</i> ESTATÍSTICAS
                        </a>
                    </li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content">
                    <!-- Activity Tab -->
                    <div role="tabpanel" class="tab-pane fade active in" id="activity">
                        <div class="table-responsive m-t-20">
                            <table class="table table-hover table-striped dataTable" id="tabela_activities">
                                <thead>
                                    <tr>
                                        <th>Data</th>
                                        <th>Ação</th>
                                        <th>Descrição</th>
                                        <th>Tipo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($atividades as $item)
                                    <tr>
                                        <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <span class="label 
                                                @if($item->description == 'created') label-success
                                                @elseif($item->description == 'updated') label-info
                                                @elseif($item->description == 'deleted') label-danger
                                                @else label-default
                                                @endif">
                                                {{ ucfirst($item->description) }}
                                            </span>
                                        </td>
                                        <td>{{ $item->subject_type }}</td>
                                        <td>{{ class_basename($item->subject_type) }} #{{ $item->subject_id }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center">Utilizador sem histórico de atividades!</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Statistics Tab -->
                    <div role="tabpanel" class="tab-pane fade" id="statistics">
                        <div class="row m-t-20">
                            <!-- Summary Cards -->
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="stats-card bg-green">
                                    <i class="material-icons" style="font-size: 48px; color: white;">article</i>
                                    <h3 class="text-white">{{ $artigosCount }}</h3>
                                    <p class="text-white">Total de Artigos Criados</p>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="stats-card bg-blue">
                                    <i class="material-icons" style="font-size: 48px; color: white;">description</i>
                                    <h3 class="text-white">{{ $documentosCount }}</h3>
                                    <p class="text-white">Total de Documentos Criados</p>
                                </div>
                            </div>
                        </div>

                        <!-- Chart -->
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="card">
                                    <div class="header">
                                        <h2>EVOLUÇÃO MENSAL (Últimos 12 Meses)</h2>
                                    </div>
                                    <div class="body">
                                        <canvas id="statsChart" height="100"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script_bottom')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize DataTable for activities
    $('#tabela_activities').DataTable({
        'language': {
            'url': '//cdn.datatables.net/plug-ins/1.10.9/i18n/Portuguese.json'
        },
        'order': [[0, 'desc']]
    });

    // Chart data from Laravel
    var monthlyData = @json($monthlyStats);
    
    var labels = monthlyData.map(function(item) { return item.month; });
    var artigosData = monthlyData.map(function(item) { return item.artigos; });
    var documentosData = monthlyData.map(function(item) { return item.documentos; });

    // Create Chart
    var ctx = document.getElementById('statsChart').getContext('2d');
    var chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Artigos',
                    data: artigosData,
                    backgroundColor: 'rgba(76, 175, 80, 0.2)',
                    borderColor: 'rgba(76, 175, 80, 1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                },
                {
                    label: 'Documentos',
                    data: documentosData,
                    backgroundColor: 'rgba(33, 150, 243, 0.2)',
                    borderColor: 'rgba(33, 150, 243, 1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
});

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