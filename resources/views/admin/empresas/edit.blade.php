@extends('layouts.app', ['activePage' => 'empresas', 'titlePage' => __('Editar empresa'), 'inicio' => 'empresas.index'])


@section('content')
    @if (Session::has('success'))

        <body onload="alertMessage(null, 'success', '<?php echo Session::get('success'); ?>')">
    @endif
    <div class="container-fluid">

        <div class="card shadow mb-4 mt-2">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Cadastrar empresa</h6>
            </div>

            <form action="{{ route('empresas.update', $empresa->id) }}" method="post">
                @csrf
                @method('PUT')
                @include('admin.empresas.inc.form', ['ftp' => [$empresa->pasta], 'empresa', $empresa])

            </form>

        </div>
    </div>
@endsection
