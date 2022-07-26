<?php

use App\Models\Igrade;

if (!function_exists('porcentagemFator')) {

    /**
     * description
     *
     * @param
     * @return
     */
    function porcentagemFator($valor, $igrade)
    {
        $valor = $valor;
        $vAdicional = $igrade->fator;
        
        $valorProduto = $igrade->tipo == '%' ? $valor + ($valor / 100 * $vAdicional) : $valor + $vAdicional;
     
         return reais($valorProduto);
    }
}
