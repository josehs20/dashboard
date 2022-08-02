@extends('layouts.app', ['activePage' => 'vendedores', 'titlePage' => __('Vendedores'), 'inicio' => 'dashboard.index'])


@section('content')
    <style>
        .iconsIndexAdmin {
            font-size: 20px;
            color: black;
        }
    </style>
    @if (Session::has('success'))

        <body onload="alertMessage(null, 'success', '<?php echo Session::get('success'); ?>')">
    @endif
    @if (Session::has('error'))

        <body onload="alertMessage(null, 'error', '<?php echo Session::get('error'); ?>')">
    @endif
    <div class="container-fluid">

        <!-- DataTales Example -->
        <div class="card shadow mb-4 mt-2">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Vendedores</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">

                    @if (count($vendedores))
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">Nome</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">E-mail</th>
                                    <th scope="col">tipo</th>
                                    <th scope="col">Loja</th>
                                    <th scope="col"></th>
                                    <th scope="col"></th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody style="cursor: pointer;">
                                @foreach ($vendedores as $v)
                                    <tr>
                                        <td>{{ $v->nome }}</td>
                                        <td>{{ strtoupper($v->status) }}</td>
                                        <td>{{ $v->user_id ? $v->usuario->email : 'Não Registrado' }}</td>
                                        <td>{{ $v->user_id ? strtoupper($v->usuario->perfil) : 'Não Registrado' }}</td>
                                        <td>{{ $v->user_id ? $v->usuario->loja->nome : 'Não Registrado' }}</td>

                                        @if ($v->user_id)
                                            @if ($v->status == 'inativo')
                                                <td><a data-bs-toggle="modal"
                                                        data-bs-target="#modalAtivar{{ $v->id }}"
                                                        class="btn btn-outline-primary">Ativar</a></td>

                                                <td><a href="{{ route('admin.vendedores.edit', ['admin' => auth()->user()->id, 'vendedore' => $v->id]) }}"
                                                        class="btn btn-outline-primary">Editar</a></td>
                                            @else
                                                <td><a data-bs-toggle="modal"
                                                        data-bs-target="#modalDesativar{{ $v->id }}"
                                                        class="btn btn-outline-danger">Desativar</a></td>

                                                <td><a href="{{ route('admin.vendedores.edit', ['admin' => auth()->user()->id, 'vendedore' => $v->id]) }}"
                                                        class="btn btn-outline-primary">Editar</a></td>
                                            @endif

                                            {{-- Modal ativar --}}
                                            <div class="modal fade" id="modalAtivar{{ $v->id }}" tabindex="-1"
                                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Confirmação</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Deseja realmente ativar {{ $v->nome }} ?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Não</button>

                                                            <form action="{{ route('status_vendedor', $v->id) }}"
                                                                method="POST"> @csrf


                                                                <button type="submit" class="btn btn-primary">Sim</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Modal desativar --}}
                                            <div class="modal fade" id="modalDesativar{{ $v->id }}" tabindex="-1"
                                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Confirmação</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Deseja realmente desativar {{ $v->nome }} ?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Não</button>

                                                            <form action="{{ route('status_vendedor', $v->id) }}"
                                                                method="POST"> @csrf


                                                                <button type="submit" class="btn btn-primary">Sim</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            {{-- rota esta como admin mas o id passado é o vendedor de funário --}}
                                            <td><a href="{{ route('admin.vendedores.create', $v->id) }}"
                                                    class="btn btn-outline-primary">Registrar</a></td>
                                        @endif

                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                        {{-- @else
                        <div class="alert alert-warning" role="alert">
                            Nenhuma empresa cadastrada
                        {{-- </div> --}}
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
