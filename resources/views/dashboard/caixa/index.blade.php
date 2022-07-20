@extends('layouts.app')


@section('content')
    <div class="container-fluid">

        <div class="row">

            <div class="col-xl-3 col-md-6 mb-4">

                <label for="basic-url" class="form-label">Data inicial</label>
                <div class="input-group mb-3">
                    <input type="date" class="form-control" id="basic-url">
                </div>

            </div>
            <div class="col-xl-3 col-md-6 mb-4">

                <label for="basic-url" class="form-label">Data final</label>
                <div class="input-group mb-3">
                    <input type="date" class="form-control" id="basic-url">
                </div>

            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <label for="basic-url" class="form-label">Controle</label>
                <div class="input-group mb-3">
                    <select class="form-select">
                        <option value="1">One</option>
                        <option value="2">Two</option>
                        <option value="3">Three</option>
                    </select>
                </div>
            </div>
       

        <div class="col-xl-3 col-md-6 mb-4 mt-4">

            <button type="button" class="btn btn-outline-primary mt-2">Buscar</button>
        </div>
    </div>
    <!-- DataTales Example -->
    <div class="card shadow mb-4 mt-2">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Resumo de caixa</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">

                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">Data</th>
                            <th scope="col">Controle</th>
                            <th scope="col">Documento</th>
                            <th scope="col">Movimento</th>
                            <th scope="col">Espécie</th>
                            <th scope="col">Valor</th>
                            <th scope="col">Parcs</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">1</th>
                            <td>Mark</td>
                            <td>Otto</td>
                            <td>@mdo</td>
                            <td>Mark</td>
                            <td>Otto</td>
                            <td>@mdo</td>
                        </tr>
                        <tr>
                            <th scope="row">1</th>
                            <td>Mark</td>
                            <td>Otto</td>
                            <td>@mdo</td>
                            <td>Mark</td>
                            <td>Otto</td>
                            <td>@mdo</td>
                        </tr>
                        <tr>
                            <th scope="row">1</th>
                            <td>Mark</td>
                            <td>Otto</td>
                            <td>@mdo</td>
                            <td>Mark</td>
                            <td>Otto</td>
                            <td>@mdo</td>
                        </tr>
                    </tbody>
                </table>

            </div>
        </div>
    </div>






    </div>
@endsection
