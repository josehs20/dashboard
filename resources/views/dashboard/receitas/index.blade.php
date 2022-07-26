@extends('layouts.app')


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
                        
                            <option value="PG">Pago</option>
                            <option value="AB">Aberto</option>
                            <option value="CA">Cancelado</option>
                        
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
                    <input required type="date" value="{{ !Session::get('datas') ? '' : Session::get('datas')[0] }}" name="dataInicial" class="form-control" id="basic-url">
                </div>

            </div>
            <div class="col-md-2 mb-4">

                <label for="basic-url" class="form-label">Data final</label>
                <div class="input-group mb-3">
                    <input required type="date" value="{{ !Session::get('datas') ? '' : Session::get('datas')[1] }}" name="dataFinal" class="form-control" id="basic-url">
                </div>

            </div>

            <div class="col-md-2 mb-4 mt-4">

                <button type="submit" class="btn btn-outline-primary mt-2">Buscar</button>
            </div>

        </form>
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
                                    <th scope="col">Detalhes</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($receitas as $key => $cliente)
                                    <tr onclick="noneCollapse('noneCollapse<?php echo $key; ?>')" data-bs-toggle="collapse"
                                        href="#clienteReceita{{ $key }}" role="button" aria-expanded="false"
                                        aria-controls="collapseExample">
                                        <td>{{ current($cliente)[0]->cliente->nome }}</td>

                                        <td><i class="ri-arrow-up-down-line"></i></td>
                                    </tr>
                                    <tr style="width: 120%;" class="row">
                                        <td id="noneCollapse{{ $key }}" class="d-none">
                                            <div class="collapse" id="clienteReceita{{ $key }}">
                                                <div class="card card-body">

                                                    <div class="table-responsive">
                                                        <table class="table table-hover">
                                                            <thead>
                                                                <tr>
                                                                    <th scope="col">Nota</th>
                                                                    <th scope="col">Emissão</th>
                                                                    <th scope="col">Vencimento</th>
                                                                    <th scope="col">Valor</th>
                                                                    <th scope="col">V.Aberto</th>
                                                                    <th scope="col">Parcela</th>
                                                                    <th scope="col">Posição</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($cliente as $chave => $notas)
                                                               
                                                                    @foreach ($notas as $n)
                                                                    
                                                                        <tr>
                                                                            <td>{{ $n->nota }}</td>
                                                                            <td>{{date('d/m/Y', strtotime($n->emissao)) }}</td>
                                                                            <td>{{date('d/m/Y', strtotime($n->vencimento)) }}</td>
                                                                            <td>{{ $n->valor }}</td>
                                                                            <td>{{ $n->valor_aberto }}</td>
                                                                            <td>{{ $n->parcela }}</td>
                                                                            <td>{{ $n->posicao }}</td>

                                                                        </tr>
                                                                    @endforeach
                                                                @endforeach

                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    {{-- @foreach ($cliente as $n)
                                        {{ dd($n) }}
                                    @endforeach --}}
                                @endforeach


                            </tbody>
                        </table>
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
