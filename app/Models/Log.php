<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $fillable = [ 'log', 'empresa_id' ];

    public function empresa() {
        return $this->belongsTo("App\Models\Empresa");
    }
}
