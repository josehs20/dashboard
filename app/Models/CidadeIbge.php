<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CidadeIbge extends Model
{
    protected $table = 'cidades_ibge';
    protected $fillable = ['codigo', 'nome', 'uf'];
}
