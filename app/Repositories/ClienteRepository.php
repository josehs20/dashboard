<?php

namespace App\Repositories;

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
        $this->model = $this->model->where('loja_id', auth()->user()->loja_id);
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
            $in = explode(',', $c[2]);
            $this->model = $this->model->whereIn($c[0], $in); //->orWhere($c[0], $c[1], $c[2]);
        }
    
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
    public function getCliente()
    {
        return $this->model->orderBy('nome')->get();
    }
}
