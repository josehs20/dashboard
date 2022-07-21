<?php

namespace App\Models;

//use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Model;

class Receita extends Model
{
    protected $fillable = [
        'loja_id',
        'alltech_id',
        'valor',
        'valor_aberto',
        'emissao',
        'vencimento',
        'parcela',
        'nota',
        'posicao',

        'cliente_id'
    ];

    protected $casts = [
        'emissao'    => 'date',
        'vencimento' => 'date'
    ];

    public function loja() {
        return $this->belongsTo('App\Models\Loja');
    }

    public function cliente() {
        return $this->belongsTo('App\Models\Cliente');
    }

    public function descricaoPosicao() {
        switch($this->posicao) {
            case 'CA': return 'Cancelado';
            break;
            case 'PG': return 'Pago';
            break;
            case 'AB': return 'Aberto';
            break;
        }
    }
}
