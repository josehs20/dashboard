<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Repositories\ClienteRepository;
use Illuminate\Http\Request;

class ClientesController extends Controller
{
    public function __contruct(Cliente $cliente)
    {
        $this->cliente = $cliente;
    }

    public function index(Request $request)
    {
        $clienteRepository = new ClienteRepository($this->cliente);
        $loja_id = auth('api')->user()->id;
        $relations = 'enderecos';

        $clienteRepository->relationWith();
        // $clienteRepository->selectrelationsCliente($loja_id, $relations);

        if ($request->has('atr_cliente')) {
            
        }

        return response()->json($clienteRepository);
    }
}
