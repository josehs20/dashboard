<?php

namespace App\Repositories;

use App\Jobs\ExportaClienteJob;
use App\Models\CidadeIbge;
use App\Models\Cliente;
use Illuminate\Database\Eloquent\Model;

class ClienteRepository
{
    // private  = auth()->user()->loja_id;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function selectLoja()
    {
        $this->model = $this->model->where('loja_id', auth('api')->user()->loja_id);
    }

    public function selectAtributosCliente($atributos)
    {

        $this->model = $this->model->selectRaw($atributos);
    }

    public function selectRelationsCliente($relations)
    {
        $this->model = $this->model->with($relations);
    }

    //usar relations para pegar enderecos ou receitas
    public function filtroCliente($filtros, $relation = null)
    {
        $filtro = explode(';', $filtros);

        foreach ($filtro as $key => $f) {
            $c = explode(':', $f);
            if ($c[1] == '=' || $c[1] == 'like') {
                $this->model = $this->model->where($c[0], $c[1], $c[2]); //->orWhere($c[0], $c[1], $c[2]);
            } else {
                $in = explode(',', $c[2]);
                $this->model = $this->model->whereIn($c[0], $in); //->orWhere($c[0], $c[1], $c[2]);
            }
        }
    }

    public function getCliente()
    {
        return $this->model->orderBy('nome')->get();
    }

    public function getClienteLimit($limit)
    {
        return $this->model->take($limit)->orderBy('nome')->get();
    }
    public function getCidades()
    {
        return $this->model->get();
    }

    public function clienteEnderecosCidadesIbge($clientes)
    {
        foreach ($clientes as $key => $c) {
            if ($c->enderecos && $c->enderecos->cidadeIbge) {
                $c->enderecos = $c->enderecos->cidadeIbge;
            }
        }
        return $clientes;
    }

    public function create_dado_cliente($dados)
    {
        $verificaCliente = Cliente::where('docto', $dados['create_cliente']['docto'])->first();
      
        if ($verificaCliente) {
            return ['verificaCliente' => $verificaCliente];
        }
     
        $cidade = CidadeIbge::where('codigo', $dados['endereco']['cidade_ibge_id'])->first();
        $dados['endereco']['cidade_ibge_id'] = $cidade->id;

        $lojas = auth('api')->user()->loja->empresa->lojas;
        $cliente = '';
        foreach ($lojas->reverse() as $key => $l) {
            $cliente = $l->clientes()->create($dados['create_cliente']);
            $cliente->enderecos()->create($dados['endereco']);
        }

        return $this->exportCliente($cliente);
    }

    public function update_dado_cliente($dados, $id)
    {
        $cliente = Cliente::with('enderecos')->find($id);
        $lojas = auth('api')->user()->loja->empresa->lojas;
        $alltech_id = $cliente->alltech_id;

        $cidade = CidadeIbge::where('codigo', $dados['endereco']['cidade_ibge_id'])->first();
        $dados['endereco']['cidade_ibge_id'] = $cidade->id;

        foreach ($lojas as $key => $loja) {
            $c = $loja->clientes()->where('alltech_id', $alltech_id)->first();
            if ($c) {
                $c->update($dados['update_cliente']);
                $c->enderecos->update($dados['endereco']);
            }
        }

        $cliente = Cliente::with('enderecos')->find($id);

        return $this->exportCliente($cliente);
    }

    public function exportCliente($cliente)
    {
        $cliente = $cliente;

        $dados = [
            'id' => $cliente->id,
            'loja_id' => $cliente->loja_id,
            'loja_alltech_id' => $cliente->loja->alltech_id,
            'nome' => $cliente->nome,
            'docto' => $_SERVER['REQUEST_METHOD'] == 'PUT' ? '-' . $cliente->docto : $cliente->docto,
            'tipo' =>  $cliente->tipo,
            'email' => trim($cliente->email) ? trim($cliente->email) : null,
            'fone1' => $cliente->fone1,
            'fone2' => $cliente->fone2,
            'celular' => $cliente->celular,
            'cidade_ibge' => $cliente->enderecos->cidadeIbge->codigo,
            'cidade' => $cliente->enderecos->cidadeIbge->nome,
            'uf' => $cliente->enderecos->cidadeIbge->uf,
            'cep' => $cliente->enderecos->cep,
            'bairro' => $cliente->enderecos->bairro,
            'rua' => $cliente->enderecos->rua,
            'numero' => $cliente->enderecos->numero ? $cliente->enderecos->numero : '',
            'compto' => $cliente->enderecos->compto,
            'tipo_endereco' => 'R',
        ];

        $dir = $cliente->loja->empresa->pasta;
        $json =  json_encode($dados, JSON_PRETTY_PRINT);

        //Class de Jobs para exportação 
        ExportaClienteJob::dispatch($json, $dir)->onQueue('appVenda');

        return $dados;
    }
}
