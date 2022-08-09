<?php 

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

class ClienteRepository extends AbstractRepository
{
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function selectrelationsCliente($loja_id, $relations)
    {
        $this->model = $this->model->where('loja_id', $loja_id)->with($relations);
    }

    public function selectAtributosCliente($atributos)
    {
        $this->model = $this->model->selectRaw($atributos);
    }

    //usar relations para pegar algum endereco ou receitas
    public function filtroCliente($filtros, $relation = null)
    {
        $this->model = $this->model->whereHas($relation, function ($query) use ($filtros) {

        });
    }

    public function getCliente()
    {
        $this->model->get();
    }
}