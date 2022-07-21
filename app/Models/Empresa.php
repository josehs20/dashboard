<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;

    protected $fillable = ['nome', 'pasta', 'ultima_sincronizacao', 'sincronizar'];
    protected $casts = [
        'ultima_sincronizacao' => 'datetime'
    ];

    public function funcionarios() {
        return $this->hasMany('App\Models\Funario');
    }

    public function lojas() {
        return $this->hasMany('App\Models\Loja');
    }

    public function logs() {
        return $this->hasMany('App\Models\Log');
    }

    public function arquivos() {
        return $this->hasMany('App\Models\ArquivoImportado');
    }

    public function fornecedor() {
        return $this->hasMany('App\Models\Fornecedor');
    }
}
