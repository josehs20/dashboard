@extends('layouts.app', ['activePage' => 'vendedores', 'titlePage' => __('Registro de vendedor'), 'inicio' => 'dashboard.index'])


@section('content')
    @if (Session::has('error'))

        <body onload="alertMessage(null, 'error', '<?php echo Session::get('error'); ?>')">
    @endif

    <div class="container-fluid">

        <div class="card shadow mb-4 mt-2">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Cadastro de vendedor</h6>
            </div>

            <form action="{{ route('admin.vendedores.store', $vendedor) }}" method="POST">
                @csrf
                @include('admin-vendas.inc.form', ['lojas' => $lojas, 'usuario' => null])

            </form>
        </div>
    </div>
@endsection
