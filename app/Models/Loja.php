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
        return $this->hasMany('App\Models\Caixa');
    }

    public function grades() {
        return $this->hasMany('App\Models\Grade');
    }

    public function iGrades() {
        return $this->hasMany('App\Models\Igrade');
    }


    public function produtos() {
        return $this->hasMany('App\Models\Produto');
    }

    public function estoques() {
        return $this->hasMany('App\Models\Estoque');
    }

    public function vendas() {
        return $this->hasMany('App\Models\Venda');
    }

    public function devolucoes() {
        return $this->hasMany('App\Models\Devolucao');
    }

    public function vendaItens() {
        return $this->hasMany('App\Models\VendaItem');
    }

    public function devolucaoItens() {
        return $this->hasMany('App\Models\DevolucaoItem');
    }

    public function clientes() {
        return $this->hasMany('App\Models\Cliente');
    }

    public function receitas() {
        return $this->hasMany('App\Models\Receita');
    }

    public function despesas() {
        return $this->hasMany('App\Models\Despesa');
    }

    public function fornecedor() {
        return $this->hasMany('App\Models\Fornecedor');
    }
}
