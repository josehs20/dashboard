@extends('layouts.app', ['activePage' => 'empresas', 'titlePage' => __('Cadastrar empresa'), 'inicio' => 'empresas.index'])


@section('content')
    <div class="container-fluid">

        <div class="card shadow mb-4 mt-2">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Cadastrar empresa</h6>
            </div>

            <form action="{{ route('empresas.store') }}" method="post">
                @csrf
                @include('admin.empresas.inc.form', ['ftp' => $ftp, 'empresa' => null])

            </form>

        </div>






    </div>
@endsection
