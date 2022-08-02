@extends('layouts.app', ['activePage' => 'configuracoes', 'titlePage' => __('Configurações'), 'inicio' => 'dashboard.index'])


@section('content')
    @if (Session::has('success'))

        <body onload="alertMessage(null, 'success', '<?php echo Session::get('success'); ?>')">
    @endif


    <div class="container-fluid">

        <div class="card shadow mb-4 mt-2">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Atualizar perfil</h6>
            </div>

            <form action="{{ route('update_profile') }}" method="POST">
                @method('PUT')
                @csrf

                @include('templates.inc.formProfile', ['usuario' => $usuario])

            </form>
        </div>
    </div>
@endsection
