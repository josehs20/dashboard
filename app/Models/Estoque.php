<?php

namespace App\Models;

//use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Model;

class Estoque extends Model
{
    protected $fillable = [
        'loja_id',
        'codbar',
        'i_grade_id',
        'tam',
        'situacao',
        'cor',
        'alltech_id',
        'produto_id',
        'saldo'
    ];

    public function loja() {
        return $this->belongsTo('App\Models\Loja');
    }

    public function produto() {
        return $this->hasOne('App\Models\Produto', 'id', 'produto_id');
    }


    public function produtos() {
        return $this->hasMany('App\Models\Produto', 'id', 'produto_id');
    }
}
