<?php

namespace App\Models;

//use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Model;

class DevolucaoItem extends Model
{
    protected $table = 'devolucao_itens';
    protected $fillable = [
        'loja_id',
        'alltech_id',
        'produto_id',
        'estoque_id',
        //'nota',
        'valor', 'custo',
        'data',
        'quantidade',
        'devolucao_id'
    ];

    public function loja()
    {
        return $this->belongsTo("App\Models\Loja");
    }

    public function produto()
    {
        return $this->belongsTo("App\Models\Produto");
    }

    public function devolucao()
    {
        return $this->belongsTo("App\Models\Devolucao");
    }
}
