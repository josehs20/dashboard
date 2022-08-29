<?php

namespace App\Repositories;

use App\Jobs\ExportaVendaJob;
use App\Models\Cliente;
use App\Models\Estoque;
use Illuminate\Database\Eloquent\Model;

class EstoqueProdutoRepository
{

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function selectAtributosProduto($loja_id, $atributos_produto)
    {
        $this->model = $this->model->where('loja_id', $loja_id)->whereNotNull('codbar')
            ->with('iGrade', $atributos_produto)->where('situacao', 'A');
    }

    public function filtrosHas_produto($loja_id, $tabela, $filtros)
    {

        $filtros = explode(';', $filtros);
        $this->model = $this->model->whereHas($tabela, function ($query) use ($filtros, $loja_id) {
            foreach ($filtros as $key => $condicao) {
                $c = explode(':', $condicao);
                $this->model = $query->where('loja_id', $loja_id)->where($c[0], $c[1], $c[2]);
            }
        });
    }

    public function filtro_estoque($filtros)
    {
        $filtros = explode(';', $filtros);
        foreach ($filtros as $key => $condicao) {
            $c = explode(':', $condicao);
            $in = explode(',', $c[2]);
            // dd($in);
            $this->model = $this->model->whereIn($c[0], $in);
        }
    }


    public function selectAtributosEstoque($atributos_estoque)
    {
        $this->model = $this->model->selectRaw($atributos_estoque);
    }

    public function get_estoque_produto()
    {
        return $this->model->take(1000)->get();
    }

    public function create_exportacao_venda($dados)
    {
        try {
            $dados->loja_alltech_id = auth('api')->user()->loja->alltech_id;
            $dados->vendedor_alltech_id = auth('api')->user()->funcionario->alltech_id;

            $json = json_encode($dados, JSON_PRETTY_PRINT);

            $dir = auth('api')->user()->loja->empresa->pasta;
    
            //Class Job para exportação
            ExportaVendaJob::dispatch($json, $dir, $dados->loja_alltech_id)->onQueue('appVenda');

            return 'Exportado com sucesso';
        } catch (\Exception $e) {
            return 'Erro ao exportar ' . $e->getMessage();
        }
    }
}
