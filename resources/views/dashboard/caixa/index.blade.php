@extends('layouts.app', ['activePage' => 'caixa', 'titlePage' => __('Resumo de caixa'), 'inicio' => 'dashboard.index'])


@section('content')
    <div class="container-fluid">

        <form action="{{ route('caixa.index') }}" method="GET" class="row">
            @csrf

            <div class="col-xl-3 col-md-6 mb-4">
                <label for="basic-url" class="form-label">Loja :</label>
                <div class="input-group mb-3">
                    <select name="loja" class="form-select">
                        @foreach (auth()->user()->lojas as $l)
                            <option {{ Session::get('loja') == $l->id ? 'selected' : ''}} value="{{ $l->id }}">{{ $l->nome }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-2 mb-4">

                <label for="basic-url" class="form-label">Data inicial</label>
                <div class="input-group mb-3">
                    <input required name="dataInicial"
                        value="{{ !Session::get('datas') ? '' : Session::get('datas')[0] }}" type="date"
                        class="form-control" id="basic-url">
                </div>

            </div>
            <div class="col-md-2 mb-4">

                <label for="basic-url" class="form-label">Data final</label>
                <div class="input-group mb-3">
                    <input required name="dataFinal" type="date"
                        value="{{ !Session::get('datas') ? '' : Session::get('datas')[1] }}" class="form-control"
                        id="basic-url">
                </div>

            </div>
            <div class="col-md-3 mb-4">
                @if (count($controles))
                    <label for="basic-url" class="form-label">Controle</label>
                    <div class="input-group mb-3">
                        <select name="controle" class="form-select">

                            @foreach ($controles as $c)
                                <option {{ Session::get('controle') == $c ? 'selected' : '' }} value="{{ $c }}">
                                    {{ $c }}</option>
                            @endforeach
                            <option {{ !Session::get('controle') ? 'selected' : '' }} value="{{ false }}">Todos
                            </option>
                        </select>
                    </div>
                @endif
            </div>


            <div class="col-md-2 mb-4 mt-4">

                <button type="submit" class="btn btn-outline-primary mt-2">Buscar</button>
            </div>
        </form>
        <!-- DataTales Example -->
        <div class="card shadow mb-4 mt-2">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Resumo de caixa</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    @if (count($caixas))
                        <table id="tableScroll" class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">Data</th>
                                    <th scope="col">Controle</th>
                                    <th scope="col">Documento</th>
                                    <th scope="col">Movimento</th>
                                    <th scope="col">Espécie</th>
                                    <th scope="col">Valor</th>
                                    <th scope="col">Parcs</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($caixas as $c)
                                    <tr>
                                        <td>{{ date('d/m/Y', strtotime($c->data)) }}</td>
                                        <td>{{ $c->controle }}</td>
                                        <td>{{ $c->documento }}</td>
                                        <td>{{ $c->descricaoMovimento() }}</td>
                                        <td>{{ $c->descricaoEspecie() }}</td>
                                        <td>{{ 'R$ '.reais($c->valor) }}</td>
                                        <td>{{ $c->parcela }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        {{-- Paginação --}}
                        <div id="paginate">

                            {{ $caixas->withQueryString()->links() }}
                        </div>
                    @else
                    
                        @if (request()->input('_token'))
                            <div class="alert alert-warning" role="alert">
                                Nenhum registro encontrado!
                            </div>
                        @else
                            <div class="alert alert-warning" role="alert">
                                Faça uma consulta!
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
    <script>
        let elem = document.getElementById('tableScroll')
        let paginate = document.getElementById('paginate')

        elem.clientWidth;
        paginate.style.width = elem.clientWidth + 'px';
    </script>
@endsection
