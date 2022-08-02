<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Funario extends Model
{
    protected $table = 'funarios';
    protected $fillable = [
        'nome',
        'alltech_id',
        'tipo',
        'status',
        'user_id',
    ];


    public function empresa()
    {
        return $this->belongsTo('App\Models\Empresa');
    }

    public function usuario()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
}
