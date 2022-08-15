<?php

namespace App\Models;

//use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $fillable = [
        'loja_id',
        'alltech_id',
        'nome',
        'docto',
        'tipo',
        'email',
        'fone1',
        'fone2',
        'celular',
    ];


    protected $casts = [
        'data_cadastro' => 'date'
    ];

    public function loja()
    {
        return $this->belongsTo('App\Models\Loja');
    }
    public function receitas()
    {
        return $this->belongsTo('App\Models\Receita');
    }
    public function enderecos()
    {
        return $this->hasOne('App\Models\Endereco');
    }
}
