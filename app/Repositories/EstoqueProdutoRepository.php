<?php
namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

class EstoqueProdutoRepository {
    
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function selectAtributosProduto($loja_id, $atributos_produto)
    {
        $this->model = $this->model->where('loja_id', $loja_id)->whereNotNull('codbar')
        ->with('iGrade', $atributos_produto);
    }

    public function filtrosHas_produto($loja_id, $tabela ,$filtros)
    {

        $filtros = explode(';', $filtros);
        $this->model = $this->model->whereHas($tabela, function ($query) use ($filtros, $loja_id) {
            foreach ($filtros as $key => $condicao) {
                $c = explode(':', $condicao);
                $this->model = $query->where('loja_id', $loja_id)->where($c[0], $c[1], $c[2]);
            }
        });
    }

    public function filtro_estoque($loja, $filtros)
    {
        $filtros = explode(';', $filtros);
        foreach ($filtros as $key => $condicao) {
            $c = explode(':', $condicao);
            $in = explode(',', $c[2]);
            //dd($in);
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

}