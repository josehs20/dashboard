<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loja extends Model
{
    use HasFactory;
    protected $fillable = ['nome', 'alltech_id', 'empresa_id'];

    public function empresa() {
        return $this->belongsTo('App\Models\Empresa');
    }

    public function users() {
        return $this->belongsToMany('App\Models\User');
    }

    public function caixas() {
        return $this->hasMany('App\Model\Caixa');
    }

    public function grades() {
        return $this->hasMany('App\Model\Grade');
    }

    public function iGrades() {
        return $this->hasMany('App\Model\Igrade');
    }


    public function produtos() {
        return $this->hasMany('App\Model\Produto');
    }

    public function estoques() {
        return $this->hasMany('App\Model\Estoque');
    }

    public function vendas() {
        return $this->hasMany('App\Model\Venda');
    }

    public function devolucoes() {
        return $this->hasMany('App\Model\Devolucao');
    }

    public function vendaItens() {
        return $this->hasMany('App\Model\VendaItem');
    }

    public function devolucaoItens() {
        return $this->hasMany('App\Model\DevolucaoItem');
    }

    public function clientes() {
        return $this->hasMany('App\Model\Cliente');
    }

    public function receitas() {
        return $this->hasMany('App\Model\Receita');
    }

    public function despesas() {
        return $this->hasMany('App\Model\Despesa');
    }

    public function fornecedor() {
        return $this->hasMany('App\Model\Fornecedor');
    }
}
