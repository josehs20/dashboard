<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CidadeIbge;
use App\Models\Cliente;
use App\Repositories\ClienteRepository;
use Illuminate\Http\Request;

class ClientesController extends Controller
{
    public function __construct(Cliente $cliente, CidadeIbge $cidadeIbge)
    {
        $this->cliente = $cliente;
        $this->cidadeIbge = $cidadeIbge;
    }

    public function index(Request $request)
    {
        $clienteRepository = new ClienteRepository($this->cliente);

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
        
        $clientes = [];
        if ($request->has('limit')) {
            $clientes = $clienteRepository->getClienteLimit($request->limit);
        } else {
            $clientes = $clienteRepository->getCliente();
        }

        if (count($clientes)) {
            $clientes = $clienteRepository->clienteEnderecosCidadesIbge($clientes);
        }

        return response()->json($clientes);
    }

    public function get_cidades_ibge(Request $request)
    {
        $cidades = new ClienteRepository($this->cidadeIbge);

        if ($request->has('cidades_ibge')) {
            $cidades->filtroCliente($request->cidades_ibge);
        }

        $cidades = $cidades->getCidades();

        return response()->json($cidades, 200);
    }

    public function create_cliente(Request $request)
    {
        $clienteRepository = new ClienteRepository($this->cliente);
        if ($request->has('json')) {
            $dados = json_decode($request->json, TRUE);
            
            $cliente = $clienteRepository->create_dado_cliente($dados);

            if (array_key_exists('verificaCliente', $cliente)) {
                return response()->json($cliente, 400);
            }

            return response()->json($cliente, 200);
        } else {
            return response()->json('Informe todos os campos obrigatórios', 200);
        }
    }

    public function update_cliente($id, Request $request)
    {
        $clienteRepository = new ClienteRepository($this->cliente);

        if ($request->has('json')) {
            $dados = json_decode($request->json, TRUE);
  
            $cliente = $clienteRepository->update_dado_cliente($dados, $id);

            // $clienteRepository->exportCliente($cliente);
            return response()->json($cliente, 200);
        }
    }
}
