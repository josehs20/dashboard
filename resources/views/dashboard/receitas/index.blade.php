@extends('layouts.app', ['activePage' => 'receber', 'titlePage' => __('Contas a receber'), 'inicio' => 'dashboard.index'])


@section('content')
    <div class="container-fluid">

        <form action="{{ route('receitas.index') }}" method="GET" class="row">
            @csrf
            <div class="col-md-2 mb-4">
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
                <label for="basic-url" class="form-label">Posição :</label>
                <div class="input-group mb-3">
                    <select name="posicao" class="form-select">
                        @foreach ($posicoes as $text => $p)
                            <option {{ Session::get('posicao') == $p ? 'selected' : '' }} value="{{ $p }}">
                                {{ $text }}</option>
                        @endforeach


                    </select>
                </div>
            </div>
            <div class="col-md-2 mb-4">
                <label for="basic-url" class="form-label mx-2">Nome do cliente</label>
                <div class="input-group mb-3">
                    <input type="text" placeholder="Nome(opcional)" name="cliente" class="form-control">
                </div>
            </div>

            <div class="col-md-2 mb-4">

                <label for="basic-url" class="form-label">Data inicial</label>
                <div class="input-group mb-3">
                    <input required type="date" value="{{ !Session::get('datas') ? '' : Session::get('datas')[0] }}"
                        name="dataInicial" class="form-control" id="basic-url">
                </div>

            </div>
            <div class="col-md-2 mb-4">

                <label for="basic-url" class="form-label">Data final</label>
                <div class="input-group mb-3">
                    <input required type="date" value="{{ !Session::get('datas') ? '' : Session::get('datas')[1] }}"
                        name="dataFinal" class="form-control" id="basic-url">
                </div>

            </div>

            <div class="col-md-2 mb-4 mt-4">

                <button type="submit" class="btn btn-outline-primary mt-2">Buscar</button>
            </div>

        </form>
        <!-- Card Money -->
        <div class="row d-flex justify-content-center">

            @include('templates.inc.cardMoney', [
                'borda' => 'info',
                'titulo' => 'Total',
                'valor' => count($receitas)
                    ? reais($valores['valorAb'] + $valores['valorPg'] + $valores['valorCa'])
                    : '0,00',
                'icone' => 'fas fa-dollar-sign fa-2x text-gray-300',
                'textColor' => 'info',
                'col' => '3',
            ])

            @include('templates.inc.cardMoney', [
                'borda' => 'warning',
                'titulo' => 'Total em aberto',
                'valor' => count($receitas) ? reais($valores['valorAb']) : '0,00',
                'icone' => 'fas fa-dollar-sign fa-2x text-gray-300',
                'textColor' => 'warning',
                'col' => '3',
            ])
            @include('templates.inc.cardMoney', [
                'borda' => 'success',
                'titulo' => 'Total Pago',
                'valor' => count($receitas) ? reais($valores['valorPg']) : '0,00',
                'icone' => 'fas fa-dollar-sign fa-2x text-gray-300',
                'textColor' => 'success',
                'col' => '3',
            ])
            @include('templates.inc.cardMoney', [
                'borda' => 'danger',
                'titulo' => 'Total cancelado',
                'valor' => count($receitas) ? reais($valores['valorCa']) : '0,00',
                'icone' => 'fas fa-dollar-sign fa-2x text-gray-300',
                'textColor' => 'danger',
                'col' => '3',
            ])
        </div>

        <!-- DataTales Example -->
        <div class="card shadow mb-4 mt-2">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Contas a receber</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    @if (count($receitas))
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">Cliente</th>
                                    <th scope="col">Nota</th>
                                    <th scope="col">Detalhes</th>

                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($receitas as $key => $r)
                                    <tr onclick="noneCollapse('noneCollapse<?php echo $r->nota; ?>')" data-bs-toggle="collapse"
                                        href="#clienteReceita{{ $r->nota }}" role="button" aria-expanded="false"
                                        aria-controls="collapseExample">
                                        <td>{{ $r->cliente->nome }}</td>
                                        <td>{{ $r->nota }}</td>

                                        <td><i class="ri-arrow-up-down-line"></i></td>
                                    </tr>


                                    <tr>
                                        <td colspan="3" class="collapse" id="clienteReceita{{ $r->nota }}">
                                            <div class="card card-body d-flex justify-content-center">
                                                @include('dashboard.receitas.inc.tabela-receita')

                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{-- Paginação --}}
                        <div id="paginate">

                            {{ $receitas->withQueryString()->links() }}
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
