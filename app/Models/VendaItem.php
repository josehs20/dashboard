<?php

namespace App\Models;

//use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Model;

class VendaItem extends Model
{
    protected $table = 'venda_itens';
    protected $fillable = [
        'loja_id',
        'alltech_id',
        'produto_id',
        'estoque_id',
        //'nota',
        'valor',
        'custo',
        'data',
        'quantidade',
        'venda_id'
    ];

    public function loja()
    {
        return $this->belongsTo("App\Models\Loja");
    }

    public function produto()
    {
        return $this->belongsTo("App\Models\Produto");
    }

    public function venda()
    {
        return $this->belongsTo("App\Models\Venda");
    }
    public function estoque()
    {
        return $this->belongsTo("App\Models\Estoque");
    }
}
