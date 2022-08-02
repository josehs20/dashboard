@extends('layouts.app', ['activePage' => 'maisVendidos', 'titlePage' => __('Mais vendidos'), 'inicio' => 'dashboard.index'])


@section('content')

    <div class="container-fluid">

        <form action="{{ route('produtos_mais_vendidos') }}" method="GET" class="row">
            @csrf
            <div class="col-md-3 mb-4">
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
            <div class="col-xl-3 col-md-6 mb-4">

                <label for="basic-url" class="form-label">Data inicial</label>
                <div class="input-group mb-3">
                    <input type="date" name="dataInicial" value="{{ !Session::get('datas') ? '' : Session::get('datas')[0] }}" class="form-control" id="basic-url">
                </div>

            </div>
            <div class="col-xl-3 col-md-6 mb-4">

                <label for="basic-url" class="form-label">Data final</label>
                <div class="input-group mb-3">
                    <input type="date" name="dataFinal" value="{{ !Session::get('datas') ? '' : Session::get('datas')[1] }}" class="form-control" id="basic-url">
                </div>

            </div>


            <div class="col-xl-3 col-md-6 mb-4 mt-4">

                <button type="submit" class="btn btn-outline-primary mt-2">Buscar</button>
            </div>
        </form>
        <!-- DataTales Example -->
        <div class="card shadow mb-4 mt-2">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Produtos mais vendidos</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">

                    @if (count($produtosMaisVendidos))
                        
                
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Produto</th>
                                <th scope="col">Quantidade de vendas</th>
 
                            </tr>
                        </thead>
                        <tbody>
                          @php
                              $itens = $produtosMaisVendidos->currentPage() * count($produtosMaisVendidos);
                              $i = 1;
                          @endphp
                          {{-- {{dd(($i - count($produtosMaisVendidos)) + 1 )}} --}}
                            @foreach ($produtosMaisVendidos as $key => $p)
                            <tr>
                                <th scope="row">{{($itens - count($produtosMaisVendidos)) + $i++ }}</th>
                                <td>{{$p->produto->nome}}</td>
                                <td>{{$p->total_vendas}}</td>
                              
                            </tr>
                            @endforeach                                                   
                        </tbody>
                    </table>
                        {{-- Paginação --}}
                        <div id="paginate">

                            {{ $produtosMaisVendidos->withQueryString()->links() }}
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
@endsection
