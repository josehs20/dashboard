@extends('layouts.app')


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


        <!-- Topbar Search -->
        <form method="GET" class="d-sm-inline-block form-inline  col-md-6">
            @csrf
            <div class="input-group">
                <input type="text" class="form-control" name="nome" placeholder="Buscar empresa" aria-label="Search"
                    aria-describedby="basic-addon2">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-search fa-sm"></i>
                    </button>
                </div>
            </div>
        </form>
        <!-- DataTales Example -->
        <div class="card shadow mb-4 mt-2">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Empresas</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">

                    @if (count($empresas))
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">Nome</th>
                                    <th scope="col">CNPJ</th>
                                    <th scope="col">Ultima Sincronização</th>
                                    <th scope="col"></th>
                                    <th scope="col"></th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody style="cursor: pointer;">
                                @foreach ($empresas as $em)
                                    <tr>
                                        <td>{{ $em->nome }}</td>
                                        <td>{{ $em->pasta }}</td>
                                        <td>{{ $em->ultima_sincronizacao ? date('d-m-Y H:m:s', strtotime($em->ultima_sincronizacao)) : 'Empresa ainda não sincronizada' }}
                                        </td>
                                        @if (count($em->lojas))
                                            <td><a href="{{ route('empresas.show', $em->id) }}"><i
                                                        class="ri-contacts-fill iconsIndexAdmin"></i></a></td>
                                        @else
                                            <td><a
                                                    onclick="alertMessage(null, 'warning', 'Empresa precisa ser sincronizada')"><i
                                                        class="ri-contacts-fill iconsIndexAdmin"></i></a></td>
                                        @endif
                                        <td><a href="{{ route('empresas.edit', $em->id) }}"><i
                                                    class="ri-edit-2-fill iconsIndexAdmin"></i></a></td>

                                        <form action="{{ route('empresas.destroy', $em->id) }}"
                                            id="empresaDestroy<?php echo $em->id; ?>" method="POST"
                                            onsubmit="confirmDelete(event,'empresaDestroy<?php echo $em->id; ?>', '<?php echo 'Deseja realmente excluir essa empresa ' . $em->nome . ' ?'; ?>', 'warning')">
                                            @csrf
                                            @method('DELETE')
                                            <td><button class="btn" type="submit"> <i
                                                        class="ri-delete-bin-2-fill iconsIndexAdmin"></i></button>
                                            </td>
                                        </form>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    @else
                        <div class="alert alert-warning" role="alert">
                            Nenhuma empresa cadastrada
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
