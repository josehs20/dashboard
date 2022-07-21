<?php

namespace App\Models;

//use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Model;

class Caixa extends Model
{
    protected $fillable = [
        'loja_id', 
        'alltech_id', 
        'historico', 
        'valor', 
        'data', 
        'movimento', 
        'especie',
        'controle',
        'documento',
        'parcela'
    ];

    protected $casts = [
        'data' => 'date'
    ];
    
    public function loja() {
        return $this->belongsTo('App\Models\Loja');
    }

    public function descricaoMovimento() {
        switch($this->movimento) {
            case 'AB': return 'Abertura';
            break;
            
            case 'VE': return 'Venda';
            break;
            
            case 'SA': return 'Sangria';
            break;
            
            case 'SU': return 'Suprimento';
            break;
            
            case 'DV': return 'Devolução';
            break;
            
            case 'RC': return 'Recebimento';
            break;
            
            case 'FE': return 'Fechamento';
            break;
            
            case 'CA': return 'Cancelamento';
            break;
            
            case 'PG': return 'Pagamento';
            break;
            
            case 'TR': return 'Emit.Crédito';
            break;
            
            case 'CSA': return 'Canc.Sangria';
            break;
            
            case 'CSU': return 'Canc.Suprito';
            break;
            
            case 'CRC': return 'Canc.Recbto';
            break;
            
            case 'VL': return 'Vale';
            break;
            
            case 'CVL': return 'Canc.Vale';
            break;
            
            case 'CDV': return 'Canc.Devolução';
            break;
        }
    }

    public function descricaoEspecie() {
        switch($this->especie) {
            case 'DN': return 'Dinheiro';
            break;
            case 'TK': return 'Ticket';
            break;
            case 'CR': return 'Crédito';
            break;
            case 'CH': return 'Cheque';
            break;
            case 'PR': return 'Promissória';
            break;
            case 'CT': return 'Cartão';
            break;
            case 'DV': return 'Devolução';
            break;
            case 'TR': return 'Trc.Dinheiro';
            break;
            case 'TC': return 'Trc.Crédito';
            break;
            case 'FE': return 'Fechamento';
            break;
            case 'CP': return 'Comprovante';
            break;
            case 'CC': return 'Cartão Crédito';
            break;
            case 'CD': return 'Cartão Débito';
            break;
        }
    }
}
