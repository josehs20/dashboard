<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Repositories\ClienteRepository;
use Illuminate\Http\Request;

class ClientesController extends Controller
{
    public function __construct(Cliente $cliente)
    {
        $this->cliente = $cliente;
    }

    public function index(Request $request)
    {
        $clienteRepository = new ClienteRepository($this->cliente);
        $loja_id = auth('api')->user()->loja_id;
        $relations = 'enderecos';

        $clienteRepository->selectLoja();

        if ($request->has('atr_cliente')) {
            // $atributos_produto = 'end';
            $clienteRepository->selectAtributosCliente($request->atr_cliente);
        }

        if ($request->has('relations')) {
            $clienteRepository->selectRelationsCliente($request->relations);
        }

        if ($request->has('filtro_cliente')) {
            $clienteRepository->filtroCliente($request->filtro_cliente);
        }

        $clientes = $clienteRepository->getCliente();

        return response()->json($clientes);
    }
}
