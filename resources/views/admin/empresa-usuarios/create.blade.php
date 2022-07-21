@extends('layouts.app')


@section('content')
    @if (Session::has('error'))

        <body onload="aletMessage(null, 'error', '<?php echo Session::get('error'); ?>')">
    @endif

    <div class="container-fluid">

        <div class="card shadow mb-4 mt-2">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Cadastro de usuário</h6>
            </div>

            <form action="{{ route('empresa.usuarios.store', $empresa) }}" id="empresaUsuariosStore" method="POST">
                @csrf
                @include('admin.empresa-usuarios.inc.form', ['lojas' => $empresa->lojas, 'usuario' => null])

            </form>
        </div>
    </div>
@endsection