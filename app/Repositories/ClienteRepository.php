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
    public function getCidades()
    {
        return $this->model->get();
    }

    public function create_dado_cliente($cliente, $endereco)
    {
        // $dados['cliente'] = explode(';', $cliente);
        // $dados['endereco'] = explode(';', $endereco);

        // foreach ($dados as $key => $values) {
        //     foreach ($values as $keyDado => $v) {
        //         $d = explode('=', $v);
        //         if ($d[0] == 'cidade_ibge_id') {
        //             $cidade = CidadeIbge::where('codigo', $d[1])->first();
        //             $dados[$key][$d[0]] = $cidade ? $cidade->id : null;
        //         } else {
        //             $dados[$key][$d[0]] = $d[1];
        //         }
        //         unset($dados[$key][$keyDado]);
        //     }
        // }

        $dados = $this->filtraUrlCrudCliente($cliente, $endereco);

        $verificaCliente = Cliente::where('docto', $dados['cliente']['docto'])->first();

        if ($verificaCliente) {
            return ['verificaCliente' => $verificaCliente];
        }

        $lojas = auth('api')->user()->loja->empresa->lojas;
        $cliente = '';
        foreach ($lojas->reverse() as $key => $l) {
            $cliente = $l->clientes()->create($dados['cliente']);
            $cliente->enderecos()->create($dados['endereco']);
        }

        return ['cliente' => $cliente];
    }


    public function update_dado_cliente($cliente, $endereco, $id)
    {
        $dados = $this->filtraUrlCrudCliente($cliente, $endereco);

        $cliente = Cliente::find($id);
     
        $lojas = auth('api')->user()->loja->empresa->lojas;
        $alltech_id = $cliente->alltech_id;
        foreach ($lojas as $key => $loja) {
            $c = $loja->clientes()->where('alltech_id', $alltech_id)->first();
            if ($c) {   
                $c->update($dados['cliente']); 
                $c->enderecos->update($dados['endereco']);        
            }
        }
       
        return $cliente;
    }

    public function filtraUrlCrudCliente($cliente, $endereco)
    {
        $dados['cliente'] = explode(';', $cliente);
        $dados['endereco'] = explode(';', $endereco);

        foreach ($dados as $key => $values) {
            foreach ($values as $keyDado => $v) {
                $d = explode('=', $v);
                if ($d[0] == 'cidade_ibge_id') {
                    $cidade = CidadeIbge::where('codigo', $d[1])->first();
                    $dados[$key][$d[0]] = $cidade ? $cidade->id : null;
                } else {

                    $dados[$key][$d[0]] = $d[1];
                }
                unset($dados[$key][$keyDado]);
            }
        }
        return $dados;
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
