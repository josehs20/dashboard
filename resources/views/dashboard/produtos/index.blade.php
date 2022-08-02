@extends('layouts.app', ['activePage' => 'estoques', 'titlePage' => __('Estoque'), 'inicio' => 'dashboard.index'])


@section('content')
    <div class="container-fluid">

        <form action="{{ route('produtos.index') }}" method="GET" class="row">
            @csrf
            <div class="col-xl-3 col-md-6 mb-4">
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
            <div class="col-md-6 mb-4">
                <label for="basic-url" class="form-label mx-2">Nome do produto</label>
                <div class="input-group mb-3">
                    <input type="text" placeholder="Nome" name="produto" class="form-control">
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4 mt-4">

                <button type="submit" class="btn btn-outline-primary mt-2">Buscar</button>
            </div>
        </form>

        <!-- DataTales Example -->
        <div class="card shadow mb-4 mt-2">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Consulta de estoque</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">

                    @if (count($produtos))
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">Produto</th>
                                    <th scope="col">Custo</th>
                                    <th scope="col">Venda</th>
                                    <th scope="col">Saldo</th>
                                </tr>
                            </thead>
                            <tbody>
                       
                                @foreach ($produtos as $key => $p)
                                    @if ($p->estoque)
                                      
                                        @if ($p->grades)
                                            @include('dashboard.produtos.inc.produtoGrade')
                                        @else
                                            <tr>
                                                <td>{{ $p->nome }}</td>
                                                <td>{{ $p->custo }}</td>
                                                <td>{{ $p->preco }}</td>

                                                <td>{{ $p->estoque->saldo }}</td>

                                            </tr>
                                        @endif
                                    @endif
                                @endforeach
                            </tbody>
                        </table>

                        <div id="paginate">

                            {{ $produtos->withQueryString()->links() }}
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
