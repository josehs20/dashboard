@extends('layouts.app')


@section('content')
    <div class="container-fluid">

        <div class="card shadow mb-4 mt-2">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Cadastro de usuário</h6>
            </div>

            <form action="{{ route('usuarios.store') }}" method="post">
                @csrf
                @include('admin.empresa-usuarios.inc.form')

            </form>

        </div>






    </div>
@endsection
