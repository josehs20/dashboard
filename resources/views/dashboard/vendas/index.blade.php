@extends('layouts.app', ['activePage' => 'vendas', 'titlePage' => __('Resumo de vendas'), 'inicio' => 'dashboard.index'])


@section('content')
    <div class="container-fluid">

        <form action="{{ route('vendas.index') }}" method="GET" class="row">
            @csrf
            <div class="col-md-3 mb-4 mx-2">
                <label for="basic-url" class="form-label">Loja :</label>
                <div class="input-group mb-3">
                    <select name="loja" class="form-select">
                        @foreach (auth()->user()->lojas as $l)
                            <option {{ Session::get('loja') == $l->id ? 'selected' : '' }} value="{{ $l->id }}">
                                {{ $l->nome }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-2 mb-4">

                <label for="basic-url" class="form-label">Data inicial</label>
                <div class="input-group mb-3">
                    <input required type="date" name="dataInicial"
                        value="{{ !Session::get('datas') ? '' : Session::get('datas')[0] }}" class="form-control"
                        id="basic-url">
                </div>

            </div>
            <div class="col-md-2 mb-4">

                <label for="basic-url" class="form-label">Data final</label>
                <div class="input-group mb-3">
                    <input required type="date" name="dataFinal"
                        value="{{ !Session::get('datas') ? '' : Session::get('datas')[1] }}" class="form-control"
                        id="basic-url">
                </div>

            </div>


            <div class="col-md-1 mb-4 mt-4">

                <button type="submit" class="btn btn-outline-primary mt-2">Buscar</button>
            </div>
        </form>

        <div class="row d-flex">
            @if (count($resumoCollection))
                @include('templates.inc.cardMoney', [
                    'borda' => 'info',
                    'titulo' => 'Total venda',
                    'valor' => reais($valores['total']),
                    'icone' => 'fas fa-dollar-sign fa-2x text-gray-300',
                    'textColor' => 'info',
                    'col' => '3',
                ])

                @include('templates.inc.cardMoney', [
                    'borda' => 'warning',
                    'titulo' => 'Total custo',
                    'valor' => reais($valores['totalCusto']),
                    'icone' => 'fas fa-dollar-sign fa-2x text-gray-300',
                    'textColor' => 'warning',
                    'col' => '3',
                ])
                @include('templates.inc.cardMoney', [
                    'borda' => 'success',
                    'titulo' => 'Lucro',
                    'valor' => reais($valores['totalLucro']),
                    'icone' => 'fas fa-dollar-sign fa-2x text-gray-300',
                    'textColor' => 'success',
                    'col' => '3',
                ])
                @include('templates.inc.cardMoney', [
                    'borda' => 'danger',
                    'titulo' => 'Devolução',
                    'valor' => reais($valores['totalDevolucao']),
                    'icone' => 'fas fa-dollar-sign fa-2x text-gray-300',
                    'textColor' => 'danger',
                    'col' => '3',
                ])
               
                @include('templates.inc.cardMoney', [
                    'borda' => 'danger',
                    'titulo' => 'Cancelamento',
                    'valor' => $valores['totalCancelamento'] == 0 ? $valores['totalCancelamento'] :'-' . reais($valores['totalCancelamento']),
                    'icone' => 'fas fa-dollar-sign fa-2x text-gray-300',
                    'textColor' => 'danger',
                    'col' => '4',
                ])
                @include('templates.inc.cardMoney', [
                    'borda' => 'danger',
                    'titulo' => 'Total à vista',
                    'valor' => reais($valores['totalAvista']),
                    'icone' => 'fas fa-dollar-sign fa-2x text-gray-300',
                    'textColor' => 'danger',
                    'col' => '4',
                ])
                @include('templates.inc.cardMoney', [
                    'borda' => 'danger',
                    'titulo' => 'Total a prazo',
                    'valor' => reais($valores['totalAprazo']),
                    'icone' => 'fas fa-dollar-sign fa-2x text-gray-300',
                    'textColor' => 'danger',
                    'col' => '4',
                ])
            @endif
            <!-- DataTales Example -->
            <div class="card shadow mb-4 mt-2">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Resumo de vendas</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        @if (count($resumoCollection))
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">Data</th>
                                        <th scope="col">Venda à vista</th>
                                        <th scope="col">Venda a prazo</th>
                                        <th scope="col">Devolução</th>
                                        <th scope="col">Cancelamento</th>
                                        <th scope="col">Total</th>
                                        <th scope="col">Detalhes</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($resumoCollection as $resumoDia)
                                        <tr data-bs-toggle="collapse" href="#resumoDia{{ $resumoDia->id }}" role="button"
                                            aria-expanded="false" aria-controls="collapseExample">
                                            {{-- <tr class="btn btn-primary" data-bs-toggle="collapse" href="#resumoDia{{ $resumoDia->id }}" role="button" aria-expanded="false" aria-controls="collapseExample"> --}}
                                            <td>{{ date('d/m/Y', strtotime($resumoDia->data)) }}</td>
                                            <td>R$
                                                {{ reais(array_sum($resumoPd[date('Y-m-d', strtotime($resumoDia->data))]['TotalDiaAvista'])) }}
                                            </td>
                                            <td>R$
                                                {{ reais(array_sum($resumoPd[date('Y-m-d', strtotime($resumoDia->data))]['TotalDiaAprazo'])) }}
                                            </td>
                                            <td>R$
                                                {{ reais(array_sum($resumoPd[date('Y-m-d', strtotime($resumoDia->data))]['TotalDiaDevolucao'])) }}
                                            </td>
                                            <td>{{ array_sum($resumoPd[date('Y-m-d', strtotime($resumoDia->data))]['TotalDiaCancelamento']) == '0' ? 'R$ 0,00' : 'R$ - ' . reais(array_sum($resumoPd[date('Y-m-d', strtotime($resumoDia->data))]['TotalDiaCancelamento'])) }}
                                            </td>
                                            <td>R$
                                                {{ reais(array_sum($resumoPd[date('Y-m-d', strtotime($resumoDia->data))]['TotalDiaAvista']) + array_sum($resumoPd[date('Y-m-d', strtotime($resumoDia->data))]['TotalDiaAprazo']) - array_sum($resumoPd[date('Y-m-d', strtotime($resumoDia->data))]['TotalDiaCancelamento']) + array_sum($resumoPd[date('Y-m-d', strtotime($resumoDia->data))]['TotalDiaDevolucao'])) }}
                                            </td>
                                            <td><i class="ri-arrow-up-down-line"></i></td>

                                        </tr>

                                        <tr>
                                            <td colspan="7" class="collapse" id="resumoDia{{ $resumoDia->id }}">
                                                <div class="card card-body d-flex justify-content-center">
                
                                                    @include('dashboard.vendas.inc.tabela-resumo-dia', [
                                                        'venda' =>
                                                            $resumoPd[date('Y-m-d', strtotime($resumoDia->data))],
                                                    ])
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <div id="paginate">

                                {{ $resumoCollection->withQueryString()->links() }}
                            </div>
                        @else
                            <div class="alert alert-warning" role="alert">
                                Nenhum registro encontrado!
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <script>
            function noneCollapse(elemento) {
                var element = document.getElementById(elemento);
                element.classList.toggle("d-none");
            }
        </script>
    @endsection
