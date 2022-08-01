<?php

namespace App\Models;

//use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Model;

class Venda extends Model
{
    protected $table = 'vendas';
    protected $fillable = [
        'alltech_id',
        'loja_id',
        'vendedor',
        'descAcres',
        'data',
        'total',
        'data_cancelamento',
        'tipo'
    ];

    protected $dates = ['data', 'data_cancelamento'];

    const TIPOS = [
        'avista' => "Venda a vista",
        'aprazo' => "Venda a prazo"
    ];


    public function loja() {
        return $this->belongsTo("App\Models\Loja");
    }

    public function itens() {
        return $this->hasMany("App\Models\VendaItem");
    }
}
