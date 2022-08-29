@extends('layouts.app', ['activePage' => 'dasboard', 'titlePage' => __('Dasboard'), 'inicio' => 'dashboard.index'])

@section('content')
    @if (Session::has('success'))

        <body onload="alertMessage(null, 'success', '<?php echo Session::get('success'); ?>')">
    @endif
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
        </div>
       
            <h4 class="text-gray-1000 mx-4">Período</h4>
            <div id="buttons-meses" class="btn-group btn-group-toggle mb-4" data-toggle="buttons">
                <label class="btn btn-outline-primary active" id="3meses">
                  <input type="radio" name="options" onclick="consulta_grafico_area(3), consulta_grafico_pie(3)" autocomplete="off" checked>3 Meses
                </label>
                <label class="btn btn-outline-primary " id="6meses">
                  <input type="radio" name="options" onclick="consulta_grafico_area(6), consulta_grafico_pie(6)" autocomplete="off"> 6 Meses
                </label>
                <label class="btn btn-outline-primary " id="9meses">
                    <input type="radio" name="options" onclick="consulta_grafico_area(9), consulta_grafico_pie(9)" autocomplete="off"> 9 Meses
                </label>
                <label class="btn btn-outline-primary " id="12meses">
                    <input type="radio" name="options" onclick="consulta_grafico_area(12), consulta_grafico_pie(12)" autocomplete="off"> 12 Meses
                  </label>
              </div>
       
        <!-- Content Row -->
        <div class="row">

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-md-3 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mx-2">
                                    TOTAL DE VENDAS</div>
                                <div id="vendas" class="h5 mb-0 font-weight-bold text-gray-800 mx-2">100</div>
                            </div>
                            <div class="col-auto">
                                <i class="mr-3 fas fa-dollar-sign fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mx-2">
                                    TOTAL CUSTO</div>
                                <div id="custos" class="h5 mb-0 font-weight-bold text-gray-800 mx-2">100</div>
                            </div>
                            <div class="col-auto">
                                <i class="mr-3 fas fa-dollar-sign fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card border-left-danger shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mx-2">
                                    DEVOLUÇÕES</div>
                                <div id="devolucoes" class="h5 mb-0 font-weight-bold text-gray-800 mx-2">100</div>
                            </div>
                            <div class="col-auto">
                                <i class="mr-3 fas fa-dollar-sign fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mx-2">
                                    LUCRO</div>
                                <div id="lucro" class="h5 mb-0 font-weight-bold text-gray-800 mx-2">100</div>
                            </div>
                            <div class="col-auto">
                                <i class="mr-3 fas fa-dollar-sign fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Content Row -->

        <div class="row">

            <!-- Area Chart -->
            <div class="col-xl-8 col-lg-7">
                <div class="card shadow mb-4">
                    <!-- Card Header - Dropdown -->
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Visão geral</h6>

                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        <div id="chart-area-content" class="chart-area">
                            <canvas id="resumoDeGanhos"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pie Chart -->
            <div class="col-xl-4 col-lg-5">
                <div class="card shadow mb-4">
                    <!-- Card Header - Dropdown -->
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Resumo de pagamentos</h6>

                    </div>
                    <!-- Card Body -->

                    <div class="card-body">
                        <div class="chart-pie pt-4 pb-2">
                            <canvas id="graficoPie"></canvas>
                        </div>
                        <div class="mt-4 text-center small">
                            <h6 id="totalGraficoPie"></h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        {{-- <div class="row">

            <!-- Area Chart -->
            <div class="col-xl-8 col-lg-7">
                  <!-- Project Card Example -->
                  <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Percentual anual</h6>
                    </div>
                    <div class="card-body">
                        <h4 class="small font-weight-bold">Total vendas <span class="float-right">60%</span></h4>
                        <div class="progress mb-4">
                            <div class="progress-bar" role="progressbar" style="width: 60%" aria-valuenow="60"
                                aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <h4 class="small font-weight-bold">Total custos <span class="float-right">20%</span></h4>
                        <div class="progress mb-4">
                            <div class="progress-bar bg-danger" role="progressbar" style="width: 20%" aria-valuenow="20"
                                aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <h4 class="small font-weight-bold">total deoluções <span class="float-right">40%</span></h4>
                        <div class="progress mb-4">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: 50%" aria-valuenow="40"
                            aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <h4 class="small font-weight-bold">Total cancelamento<span class="float-right">80%</span></h4>
                        <div class="progress mb-4">
                            <div class="progress-bar bg-info" role="progressbar" style="width: 80%" aria-valuenow="80"
                            aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <h4 class="small font-weight-bold">total Lucro<span class="float-right">Complete!</span></h4>
                        <div class="progress">
                            <div class="progress-bar bg-success" role="progressbar" style="width: 100%" aria-valuenow="100"
                                aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pie Chart -->
            <div class="col-xl-4 col-lg-5">
                <div class="card shadow mb-4">
                    <!-- Card Header - Dropdown -->
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Resumo de contas</h6>

                    </div>
                    <!-- Card Body -->

                    <div class="card-body">
                        <div class="chart-pie pt-4 pb-2">
                            <canvas id="graficoPieReceber"></canvas>
                        </div>
                        <div class="mt-4 text-center small">
                            <h6 id="graficoPieReceber"></h6>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>
@endsection
<!-- Page level plugins -->
<script src="{{ asset('plugins/chart.js/Chart.min.js') }}" defer></script>

<!-- Page level custom scripts -->
<script src="{{ asset('chart/chart-pie-demo.js') }}" defer></script>
<script src="{{ asset('chart/chart-area-demo.js') }}" defer></script>