<?php

namespace App\Models;

//use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    protected $fillable = ['loja_id', 'alltech_id', 'refcia', 'nome', 'custo', 'preco', 'un', 'situacao', 'grade_id'];

    public function loja()
    {
        return $this->belongsTo('App\Models\Loja');
    }

    public function estoques()
    {
        return $this->hasOne('App\Models\Estoque');
    }

    public function saldo($loja_id)
    {
        return $this->estoques()->count() > 0 ? $this->estoques()->first()->saldo : 0;
    }

    public function grades()
    {
        return $this->belongsTo('App\Models\Grade', 'grade_id', 'id');
    }

    public function iGrades()
    {
        return $this->belongsTo('App\Models\Igrade');
    }

    public function descricaoProduto()
    {
        switch ($this->situacao) {
            case 'A':
                return 'Ativo';
                break;

            case 'I':
                return 'Inativo';
                break;
        }
    }
}
