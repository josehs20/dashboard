<?php

namespace App\Models;

//use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Model;

class Devolucao extends Model
{
    protected $table = 'devolucoes';
    protected $fillable = [
        'alltech_id',
        'loja_id',
        'tipo',
        'vendedor',
        'descAcres',
        'data',
        'total',
        'data_cancelamento'
    ];

    protected $casts = [
        'data' => 'date',
        'data_cancelamentl' => 'date'
    ];

    public function loja() {
        return $this->belongsTo("App\Models\Loja");
    }

    public function itens() {
        return $this->hasMany("App\Models\DevolucaoItem");
    }
}
