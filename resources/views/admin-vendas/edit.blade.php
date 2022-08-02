@extends('layouts.app', ['activePage' => 'vendedores', 'titlePage' => __('Editar vendedor'), 'inicio' => 'dashboard.index'])


@section('content')
    @if (Session::has('error'))

        <body onload="alertMessage(null, 'error', '<?php echo Session::get('error'); ?>')">
    @endif

    <div class="container-fluid">

        <div class="card shadow mb-4 mt-2">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Atualiza vendedor</h6>
            </div>

            <form action="{{ route('admin.vendedores.update', ['admin' => auth()->user()->id ,'vendedore' => $vendedorUsuario->id]) }}" method="POST">
                @method('PUT')
                @csrf
                @include('admin-vendas.inc.form', ['lojas' => $lojas, 'usuario' => $vendedorUsuario])

            </form>
        </div>
    </div>
@endsection
