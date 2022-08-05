@extends('layouts.app', ['activePage' => 'empresas', 'titlePage' => __('Empresa usuários'), 'inicio' => 'empresas.index'])


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
    <div class="container-fluid">

        <!-- DataTales Example -->
        <div class="card shadow mb-4 mt-2">
            <div class="card-header py-3">
                <div class="d-flex bd-highlight">
                    <div class="p-2 w-100 font-weight-bold text-primary">Usuarios da empresa {{ $empresa->nome }}</div>
                    <div class="p-2 flex-shrink-1 bd-highlight"><a class="btn btn-outline-primary btn-sm"
                            href="{{ route('empresa.usuarios.create', $empresa->id) }}">
                            <h6> + usuário</h6>
                        </a></div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    @if (count($usuarios))
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">Nome</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Loja</th>
                                    <th scope="col">Tipo</th>
                                    <th scope="col"></th>
                                    <th scope="col"></th>
                                    <th scope="col"></th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($usuarios as $us)
                                    <tr>
                                        <td>{{ $us->name }}</td>
                                        <td>{{ $us->email }}</td>
                                        <td>{{ $us->perfil == 'adminVenda' ? 'Admin venda' : ($us->perfil == 'vendedor' ? 'Vendedor Externo' : 'Somente consulta') }}</td>
                                        <td>{{ $us->loja->alltech_id }}
                                        </td>

                                        <td><a href="{{ route('empresa.usuarios.edit', ['empresa' => $us->loja->empresa->id, 'usuario' => $us->id]) }}"><i
                                                    class="ri-edit-2-fill iconsIndexAdmin"></i></a></td>

                                        <form
                                            action="{{ route('empresa.usuarios.destroy', ['empresa' => $us->loja->empresa->id, 'usuario' => $us->id]) }}"
                                            id="empresaUsuariosDestroy{{$us->id}}"
                                            onsubmit="confirmDelete(event,'empresaUsuariosDestroy<?php echo $us->id?>', '<?php echo 'Deseja realmente excluir usuário ' . $us->name . ' ?'; ?>', 'warning')"
                                            method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <td><button class="btn" type="submit"><i
                                                        class="ri-delete-bin-2-fill iconsIndexAdmin"></i></button>
                                        </form>

                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    @else
                        <div class="alert alert-warning" role="alert">
                            Nenhuma usuário cadastrado para esta empresa
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
