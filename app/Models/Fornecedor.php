<?php

namespace App\Models;

//use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Model;

class Fornecedor extends Model
{
        //protected $table = 'fornecedors';
       // protected $primaryKey = 'id';
        protected $fillable = ['loja_id', 'alltech_id', 'nome'];
        
        public function loja() {
            return $this->belongsTo('App\Models\Loja');
        }

        public function empresa() {
            return $this->belongsTo('App\Models\Empresa');
        }
        

        public function despesas() {
            return $this->belongsTo('App\Models\Despesa');
        }

    }

 
