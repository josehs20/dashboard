<?php

namespace App\Services;

use App\Models\CidadeIbge;
use App\Model\Produto;
use App\Model\Loja;
use DateTime;
use Illuminate\Support\Facades\Storage;

class ImportXml
{
    static function generica($empresa, $filename)
    {
        $self = new ImportXml;
        $dados = $self->getDados($filename, $empresa);
        $dados = $dados['dados'];

        $importado = 0;

        foreach ($dados as $dado) {
            if (isset($dado['@attributes'])) {
                $dado = $dado['@attributes'];
            }
            try {

                if (isset($dado['TIPO']) && $dado['TIPO'] == "LJ") {

                    $alltech_id = preg_replace('/\D/', '', trim(strval($dado['CODIGO'])));

                    if ($empresa->lojas()->where('alltech_id', $alltech_id)->count() == 0) {
                        $empresa->lojas()->create([
                            'nome' => "Loja:  $alltech_id {$dado['NOME']}",
                            'alltech_id' =>  $alltech_id,
                        ]);
                        $importado++;
                    } else {
                        $loja = $empresa->lojas()->where('alltech_id', $alltech_id)->first();
                        $loja->update([
                            'nome' => "Loja:  $alltech_id {$dado['NOME']}",
                            'alltech_id' =>  $alltech_id,
                        ]);
                        $importado++;
                    }
                }
            } catch (\Exception $e) {
                echo $e->getMessage() . ' ,' . $filename;
            }
        }
        return $importado++;
    }

    static function funarios($empresa, $filename)
    {
        $self   = new ImportXml;
        $dados  = $self->getDados($filename, $empresa);
        $dados  = $dados['dados'];
        //vai entrar codigo para importaçõa do funários

    }

    static function caixa($empresa, $filename)
    {

        $self   = new ImportXml;
        $dados  = $self->getDados($filename, $empresa);
        $dados  = $dados['dados'];

        $importado  = 0;

        foreach ($dados as $dado) {
            if (isset($dado['@attributes'])) {
                $dado = $dado['@attributes'];
            }

            // if (is_array($dado['ID_REG'])) {
            //     $dado['ID_REG'] = '0';
            // }

            // if (is_array($dado['HISTORICO'])) {
            //     $dado['HISTORICO'] = '';
            // }
            try {

                $loja       = $empresa->lojas()->where('alltech_id', preg_replace('/\D/', '', trim(strval($dado['ID_LOJA']))))->first();
                //  echo $substr_data;
                $data = is_string($dado['DATA']) ? \Carbon\Carbon::createFromFormat('Ymd H:i:s', substr(str_replace("T", " ", $dado['DATA']), 0, 17)) : null;
                $alltech_id = strval($dado['ID_REG']);
                $caixa = $loja->caixas()->where('alltech_id', $alltech_id)->first();

                //data menor que 12 meses
                if ($loja && !$caixa && $data >= date("Y-m-d H:i:s", strtotime("-12 month"))) {

                    $loja->caixas()->create([
                        'alltech_id' => strval($dado['ID_REG']),
                        'historico'  => strval($dado['HISTORICO']),
                        'controle'   => str_replace(".", '', $dado['CONTROLE']),
                        'parcela'    => (isset($dado['PARCS'])) ? str_replace(".", '', $dado['PARCS']) : null,
                        'documento'  => (isset($dado['DOCTO'])) ? str_replace(".", '', $dado['DOCTO']) : null,
                        'movimento'  => trim(strval($dado['MOVTO'])),
                        'especie'    => trim(strval($dado['ESPECIE'])),
                        'valor'      => strval($dado['VALOR']),
                        'data'       => $data,
                    ]);
                    $importado++;
                    // echo "caixa criado {$dado['CONTROLE']} ---- $data \n";
                } else if ($caixa && $data >= date("Y-m-d H:i:s", strtotime("-12 month"))) {
                    // print_r(date("Y-m-d H:i:s"));
                    // $caixa = $loja->caixas->where('alltech_id', $alltech_id)->first();
                    $caixa->update([
                        'historico'  => strval($dado['HISTORICO']),
                        'controle'   => str_replace(".", '', $dado['CONTROLE']),
                        'parcela'    => (isset($dado['PARCS'])) ? str_replace(".", '', $dado['PARCS']) : null,
                        'documento'  => (isset($dado['DOCTO'])) ? str_replace(".", '', $dado['DOCTO']) : null,
                        'movimento'  => trim(strval($dado['MOVTO'])),
                        'especie'    => trim(strval($dado['ESPECIE'])),
                        'valor'      => strval($dado['VALOR']),
                        'data'       => $data,
                    ]);
                    $importado++;
                }
            } catch (\Exception $e) {
                echo 'exception: ' . $dado['CONTROLE'] . ', ' . $e->getMessage() . ', ' .
                    $filename . '\n';
            }
        }
        return $importado;
    }

    static function venda($empresa, $filename)
    {
        $self = new ImportXml;
        $dados  = $self->getDados($filename, $empresa);
        $dados  = $dados['dados'];

        $importado  = 0;

        foreach ($dados as $dado) {
            if (isset($dado['@attributes'])) {
                $dado = $dado['@attributes'];
            }

            try {

                $loja       = $empresa->lojas()->where('alltech_id', preg_replace('/\D/', '', trim(strval($dado['ID_LOJA']))))->first();
                $data       = (is_string($dado['EMISSAO']) and strlen($dado['EMISSAO']) >= 8) ? \Carbon\Carbon::createFromFormat('Ymd', substr($dado['EMISSAO'], 0, 8)) : null;

                $alltech_id = trim(strval($dado['NVENDA']));

                $dadosVendaDevolucao = [
                    'alltech_id'        => $alltech_id,
                    'data'              => $data,
                    'tipo'              => strval($dado['TIPO']),
                    // 'nvenda'            => trim(strval($dado['NVENDA'])),
                    'total'             => $dado['VTOTAL'],
                    'data_cancelamento' => (isset($dado['CANCELADO']) and is_string($dado['CANCELADO']) and strlen($dado['CANCELADO']) >= 8) ? \Carbon\Carbon::createFromFormat('Ymd', substr($dado['CANCELADO'], 0, 8)) : null,
                ];

                if ($loja && $data >= date("Y-m-d H:i:s", strtotime("-12 month"))) {

                    if ($dado['TIPO'] == 'V' or $dado['TIPO'] == 'P') {

                        $venda_exists = $loja->vendas()->where('alltech_id', $alltech_id)->first();

                        $dadosVendaDevolucao['tipo'] = ($dado['TIPO'] == 'V') ? 'avista' : 'aprazo';

                        if (!$venda_exists) {
                            $loja->vendas()->create($dadosVendaDevolucao);

                            $importado++;
                        } else {

                            $venda_exists->update($dadosVendaDevolucao);
                            $importado++;
                        }
                    } elseif ($dado['TIPO'] == 'D') {
                        //devolucoes
                        $devolucao_exists = $loja->devolucoes()->where('alltech_id', $alltech_id)->first();

                        if (!$devolucao_exists) {
                            // echo $alltech_id . "d.";
                            $loja->devolucoes()->create($dadosVendaDevolucao);

                            $importado++;
                        } else {
                            //  echo $alltech_id . "v;";
                            //$venda = $loja->devolucoes()->where('alltech_id', $alltech_id)->first();
                            $devolucao_exists->update($dadosVendaDevolucao);
                            $importado++;
                        }
                    }
                }
            } catch (\Exception $e) {
                echo 'exception: ' . $e->getMessage() . ', ' .
                    $filename . '\n';
            }
        }
        return $importado;
    }

    static function vendaItens($empresa, $filename)
    {
        $self = new ImportXml;
        $dados  = $self->getDados($filename, $empresa);
        $dados  = $dados['dados'];

        $importado  = 0;

        foreach ($dados as $dado) {
            if (isset($dado['@attributes'])) {
                $dado = $dado['@attributes'];
            }

            try {

                $loja       = $empresa->lojas()->where('alltech_id', preg_replace('/\D/', '', trim(strval($dado['ID_LOJA']))))->first();
                $data       = is_string($dado['DATA']) ? \Carbon\Carbon::createFromFormat('Ymd', substr($dado['DATA'], 0, 8)) : null;

                // if ($loja && array_key_exists('VENDA', $dado)) {
                if ($loja && $data >= date("Y-m-d H:i:s", strtotime("-12 month")) && $dado['NOTA']) {

                    // if (is_array($dado['CUSTO']) or !$dado['CUSTO']) {
                    //     $dado['CUSTO'] = 0.0;
                    // }

                    // $alltech_id = trim(strval($dado['ID_REG']));
                    $alltech_id = trim($dado['NOTA']);

                    if (trim($dado['MOVTO']) == 'VE' || trim($dado['MOVTO']) == 'BL' || trim($dado['MOVTO']) == 'CN') {

                        $self->importarVenda($loja, $alltech_id, $dado);
                        $importado++;
                    } elseif (trim($dado['MOVTO']) == 'DV') {
                        $self->importarDevolucao($loja, $alltech_id, $dado);
                        $importado++;
                    }
                }
            } catch (\Exception $e) {
                echo $e->getMessage() . ' ,' . $filename;
            }
        }
        return $importado;
    }

    static function fornecedores($empresa, $filename)
    {
        $self = new ImportXml;
        $dados  = $self->getDados($filename, $empresa);
        $dados  = $dados['dados'];
        $importado  = 0;
        //print_r($dados);
        foreach ($dados as $dado) {
            if (isset($dado['@attributes'])) {
                $dado = $dado['@attributes'];
            }
            try {
                // if (array_key_exists('CODIGO', $dado) &&  array_key_exists('NOME', $dado)) {

                $alltech_id = preg_replace('/\D/', '', trim(strval($dado['CODIGO'])));

                if (!$empresa->fornecedor()->where('alltech_id', $alltech_id)->first()) {

                    $empresa->fornecedor()->create([
                        'alltech_id'    => $alltech_id,
                        'nome'          => strval($dado['NOME']),

                    ]);
                    $importado++;
                } else {
                    $fornecedor = $empresa->fornecedor()->where('alltech_id', $alltech_id)->first();

                    $fornecedor->update([
                        'nome'      => strval($dado['NOME']),
                    ]);
                    $importado++;
                }
            } catch (\Exception $e) {
                echo $e->getMessage() . ' ,' . $filename;
            }
        }
        return $importado;
    }

    static function pagar($empresa, $filename)
    {
        $self = new ImportXml;
        $dados  = $self->getDados($filename, $empresa);
        $dados  = $dados['dados'];

        $importado  = 0;

        foreach ($dados as $dado) {
            if (isset($dado['@attributes'])) {
                $dado = $dado['@attributes'];
            }

            //  if (array_key_exists('ID_REG', $dado)) {
            try {

                $loja       = $empresa->lojas()->where('alltech_id', preg_replace('/\D/', '', trim(strval($dado['ID_LOJA']))))->first();
                $data       = (is_string($dado['EMISSAO']) and strlen($dado['EMISSAO']) >= 8) ? \Carbon\Carbon::createFromFormat('Ymd', substr($dado['EMISSAO'], 0, 8)) : null;

                if ($loja && $data >= date("Y-m-d H:i:s", strtotime("-12 month"))) {

                    $alltech_id = trim(strval($dado['ID_REG']));
                    $fornecedor = $empresa->fornecedor()->where('alltech_id', preg_replace('/\D/', '', trim(strval($dado['RESP']))))->first();

                    $verifica_nota = $loja->despesas()->where('nota', strval($dado['NOTA']))->where('parcela', trim(strval($dado['PARCELA'])))->first();

                    // if ($fornecedor) {

                    if (!$verifica_nota) {

                        $loja->despesas()->create([
                            'alltech_id'    => $alltech_id,
                            'valor'         => strval($dado['VALOR']),
                            'valor_aberto'  => strval($dado['VALORAB']),
                            'emissao'       => (is_string($dado['EMISSAO']) and strlen($dado['EMISSAO']) >= 8) ? \Carbon\Carbon::createFromFormat('Ymd', substr($dado['EMISSAO'], 0, 8)) : null,
                            'vencimento'    => (is_string($dado['VENCTO']) and strlen($dado['VENCTO']) >= 8) ? \Carbon\Carbon::createFromFormat('Ymd', substr($dado['VENCTO'], 0, 8)) : null,
                            'parcela'       => (isset($dado['PARCELA'])) ? str_replace(".", '', $dado['PARCELA']) : null,
                            'posicao'       => trim(strval($dado['POSICAO'])),
                            'nota'          => strval($dado['NOTA']),
                            'fornecedor_id' => $fornecedor ? $fornecedor->id : null
                        ]);

                        $importado++;
                    } else {
                        $verifica_nota->update([
                            'alltech_id'    => $alltech_id,
                            'valor'         => strval($dado['VALOR']),
                            'valor_aberto'  => strval($dado['VALORAB']),
                            'emissao'       => (is_string($dado['EMISSAO']) and strlen($dado['EMISSAO']) >= 8) ? \Carbon\Carbon::createFromFormat('Ymd', substr($dado['EMISSAO'], 0, 8)) : null,
                            'vencimento'    => (is_string($dado['VENCTO']) and strlen($dado['VENCTO']) >= 8) ? \Carbon\Carbon::createFromFormat('Ymd', substr($dado['VENCTO'], 0, 8)) : null,
                            'parcela'       => (isset($dado['PARCELA'])) ? str_replace(".", '', $dado['PARCELA']) : null,
                            'posicao'       => trim(strval($dado['POSICAO'])),
                            'nota'          => trim(strval($dado['NOTA'])),
                            'fornecedor_id'    => $fornecedor ? $fornecedor->id : null
                        ]);

                        $importado++;
                    }
                }
            } catch (\Exception $e) {
                echo $e->getMessage() . ' ,' . $filename;
            }
        }
        return $importado;
    }
    static function clientes($empresa, $filename)
    {
        $self = new ImportXml;
        $dados  = $self->getDados($filename, $empresa);
        $dados  = $dados['dados'];
        $importado  = 0;

        $lojas = $empresa->lojas()->get();

        foreach ($dados as $dado) {
            if (isset($dado['@attributes'])) {
                $dado = $dado['@attributes'];
            }

            //if (array_key_exists('CODIGO', $dado) && array_key_exists('ID_LOJA', $dado)) {

            try {

                foreach ($lojas as $key => $value) {

                    $loja       = $empresa->lojas()->where('alltech_id', $value->alltech_id)->first();

                    if ($loja) {

                        $alltech_id = preg_replace('/\D/', '', trim(strval($dado['CODIGO'])));

                        if (!$loja->clientes()->first()) {
                            $self->clienteVendaAvista($empresa, $importado, $dado);
                        }

                        if (!$loja->clientes()->where('alltech_id', $alltech_id)->first()) {

                            $loja->clientes()->create([
                                'alltech_id'    => $alltech_id,
                                'nome'          => array_key_exists('NOME', $dado) ? strval($dado['NOME']) : null,
                                'docto'         => array_key_exists('DOCTO', $dado) && $dado['DOCTO'] != " " ? strval($dado['DOCTO']) : null,
                                'tipo'          => array_key_exists('TIPO', $dado) && $dado['TIPO'] != " " ? strval($dado['TIPO']) : null,
                                'email'         => array_key_exists('EMAIL', $dado) && $dado['EMAIL'] != " " ? strval($dado['EMAIL']) : null,
                                'fone1'         => array_key_exists('FONE1', $dado) && $dado['FONE1'] != " " ? strval($dado['FONE1']) : null,
                                'fone2'         => array_key_exists('FONE2', $dado) && $dado['FONE2'] != " " ? strval($dado['FONE2']) : null,
                                'celular'       => array_key_exists('CELULAR', $dado) && $dado['CELULAR'] != " " ? strval($dado['CELULAR']) : null,
                            ]);
                            $importado++;
                        } else {
                            $cliente = $loja->clientes()->where('alltech_id', $alltech_id)->first();

                            $cliente->update([
                                'nome'          => array_key_exists('NOME', $dado) ? strval($dado['NOME']) : null,
                                'docto'          => array_key_exists('DOCTO', $dado) && $dado['DOCTO'] != " " ? strval($dado['DOCTO']) : null,
                                'tipo'          => array_key_exists('TIPO', $dado) && $dado['TIPO'] != " " ? strval($dado['TIPO']) : null,
                                'email'         => array_key_exists('EMAIL', $dado) && $dado['EMAIL'] != " " ? strval($dado['EMAIL']) : null,
                                'fone1'         => array_key_exists('FONE1', $dado) && $dado['FONE1'] != " " ? strval($dado['FONE1']) : null,
                                'fone2'         => array_key_exists('FONE2', $dado) && $dado['FONE2'] != " " ? strval($dado['FONE2']) : null,
                                'celular'       => array_key_exists('CELULAR', $dado) && $dado['CELULAR'] != " " ? strval($dado['CELULAR']) : null,
                            ]);
                            $importado++;
                        }
                    }
                }
            } catch (\Exception $e) {
                echo $e->getMessage() . ' ,' . $filename;
            }
        }
        return $importado;
    }

    static function enderecos($empresa, $filename)
    {
        $self = new ImportXml;
        $dados  = $self->getDados($filename, $empresa);
        $dados  = $dados['dados'];

        $importado  = 0;

        $lojas = $empresa->lojas()->get();

        foreach ($dados as $dado) {
            if (isset($dado['@attributes'])) {
                $dado = $dado['@attributes'];
            }

            try {
                if ($dado['TIPO'] == "R") {

                    foreach ($lojas as $loja) {

                        $cliente = $loja->clientes()->where('alltech_id',  preg_replace('/\D/', '', trim(strval($dado['RESP']))))->first();

                        if ($cliente) {

                            $cliente_endereco = $cliente->enderecos()->first();

                            $cidade = CidadeIbge::where('codigo', array_key_exists('CIDADE', $dado) && $dado['CIDADE'] != '0' && $dado['CIDADE'] != ' ' ? $dado['CIDADE'] : null)->first();
                            if (!$cliente_endereco) {
                                $cliente->enderecos()->create([
                                    'cidade_ibge_id' => $cidade ? $cidade->id : null,
                                    'cep' => array_key_exists('CEP', $dado) && $dado['CEP'] != '0' && $dado['CEP'] != ' ' ? intval(trim($dado['CEP'])) : null,
                                    'bairro' => array_key_exists('BAIRRO', $dado) && $dado['BAIRRO'] != '0' && $dado['BAIRRO'] != ' ' ? strval($dado['BAIRRO']) : null,
                                    'rua' => array_key_exists('RUA', $dado) && $dado['RUA'] != '0' && $dado['RUA'] != ' ' ? strval($dado['RUA']) : null,
                                    'numero' => array_key_exists('NUMERO', $dado) && $dado['NUMERO'] != '0' && $dado['NUMERO'] != ' ' ? intval(trim($dado['NUMERO'])) : null,
                                    'compto' => array_key_exists('COMPTO', $dado) && $dado['COMPTO'] != '0'  && $dado['COMPTO'] != ' ' ? strval($dado['COMPTO']) : null,
                                    'tipo' => $dado['TIPO'],
                                ]);
                                $importado++;
                            } else {
                                $cliente->enderecos()->update([
                                    'cidade_ibge_id' => $cidade ? $cidade->id : null,
                                    'cep' => array_key_exists('CEP', $dado) && $dado['CEP'] != '0' && $dado['CEP'] != ' ' ? intval(trim($dado['CEP'])) : null,
                                    'bairro' => array_key_exists('BAIRRO', $dado) && $dado['BAIRRO'] != '0' && $dado['BAIRRO'] != ' ' ? strval($dado['BAIRRO']) : null,
                                    'rua' => array_key_exists('RUA', $dado) && $dado['RUA'] != '0' && $dado['RUA'] != ' ' ? strval($dado['RUA']) : null,
                                    'numero' => array_key_exists('NUMERO', $dado) && $dado['NUMERO'] != '0' && $dado['NUMERO'] != ' ' ? intval(trim($dado['NUMERO'])) : null,
                                    'compto' => array_key_exists('COMPTO', $dado) && $dado['COMPTO'] != '0'  && $dado['COMPTO'] != ' ' ? strval($dado['COMPTO']) : null,
                                    'tipo' => $dado['TIPO'],
                                ]);
                                $importado++;
                            }
                        }
                    }
                }
            } catch (\Exception $e) {
                echo $e->getMessage() . ' ,' . $filename;
            }
        }
        return $importado;
    }

    static function receitas($empresa, $filename)
    {
        $self = new ImportXml;
        $dados  = $self->getDados($filename, $empresa);
        $dados  = $dados['dados'];

        $importado  = 0;

        foreach ($dados as $dado) {
            if (isset($dado['@attributes'])) {
                $dado = $dado['@attributes'];
            }

            try {

                $loja       = $empresa->lojas()->where('alltech_id', preg_replace('/\D/', '', trim(strval($dado['ID_LOJA']))))->first();
                $data       = (is_string($dado['EMISSAO']) and strlen($dado['EMISSAO']) >= 8) ? \Carbon\Carbon::createFromFormat('Ymd', substr($dado['EMISSAO'], 0, 8)) : null;

                if ($loja && $data >= date("Y-m-d H:i:s", strtotime("-12 month"))) {

                    $alltech_id =  trim(strval($dado['ID_REG']));
                    $verifica_nota = $loja->receitas()->where('nota', trim(strval($dado['NOTA'])))->where('parcela', trim(strval($dado['PARCELA'])))->first();
                    $cliente = $loja->clientes()->where('alltech_id', preg_replace('/\D/', '', trim(strval($dado['RESP']))))->first();
                    //echo $data . "\n";
                    if (!$verifica_nota) {
                        $loja->receitas()->create([
                            'alltech_id'    => $alltech_id,
                            'valor'         => strval($dado['VALOR']),
                            'valor_aberto'  => strval($dado['VALORAB']),
                            'emissao'       => $data,
                            'vencimento'    => (is_string($dado['VENCTO']) and strlen($dado['VENCTO']) >= 8) ? \Carbon\Carbon::createFromFormat('Ymd', substr($dado['VENCTO'], 0, 8)) : null,
                            'parcela'       => (isset($dado['PARCELA'])) ? str_replace(".", '', $dado['PARCELA']) : null,
                            'posicao'       => trim(strval($dado['POSICAO'])),
                            'nota'          => trim(strval($dado['NOTA'])),
                            'cliente_id'    => $cliente ? $cliente->id : null
                        ]);

                        $importado++;
                    } else {
                        $verifica_nota->update([
                            'alltech_id'    => $alltech_id,
                            'valor'         => strval($dado['VALOR']),
                            'valor_aberto'  => strval($dado['VALORAB']),
                            'emissao'       => $data,
                            'vencimento'    => (is_string($dado['VENCTO']) and strlen($dado['VENCTO']) >= 8) ? \Carbon\Carbon::createFromFormat('Ymd', substr($dado['VENCTO'], 0, 8)) : null,
                            'parcela'       => (isset($dado['PARCELA'])) ? str_replace(".", '', $dado['PARCELA']) : null,
                            'posicao'       => trim(strval($dado['POSICAO'])),
                            'nota'       => trim(strval($dado['NOTA'])),
                            'cliente_id'    => $cliente ? $cliente->id : null
                        ]);

                        $importado++;
                    }
                }
            } catch (\Exception $e) {
                echo $e->getMessage() . ' ,' . $filename;
            }
        }
        return $importado;
    }

    static function grades($empresa, $filename)
    {
        $self = new ImportXml;
        $dados  = $self->getDados($filename, $empresa);
        $dados  = $dados['dados'];
        $importado = 0;

        $lojas = $empresa->lojas()->get();

        foreach ($dados as $dado) {
            if (isset($dado['@attributes'])) {
                $dado = $dado['@attributes'];
            }
            //if (array_key_exists('CODIGO', $dado)) {

            foreach ($lojas as $value) {

                try {
                    $loja       = $empresa->lojas()->where('alltech_id', $value->alltech_id)->first();

                    if ($loja) {

                        $alltech_id = preg_replace('/\D/', '', trim(strval($dado['CODIGO'])));

                        if (!$loja->grades()->where('alltech_id', $alltech_id)->first()) {

                            $loja->grades()->create([
                                'alltech_id' => $alltech_id,
                                'nome' => strval($dado['NOME']),
                            ]);
                            $importado++;
                        } else {
                            $grade =  $loja->grades()->where('alltech_id', $alltech_id)->first();

                            $grade->update([
                                'nome' => strval($dado['NOME']),
                            ]);
                            $importado++;
                        }
                    }
                } catch (\Exception $e) {
                    echo $e->getMessage() . ' ,' . $filename;
                }
            }
        }
        return  $importado++;
    }

    static function iGrades($empresa, $filename)
    {
        $self = new ImportXml;
        $dados  = $self->getDados($filename, $empresa);
        $dados  = $dados['dados'];
        $importado = 0;

        $lojas = $empresa->lojas()->get();

        foreach ($dados as $dado) {
            if (isset($dado['@attributes'])) {
                $dado = $dado['@attributes'];
            }
            //  if (array_key_exists('CODIGO', $dado)) {
            foreach ($lojas as $value) {

                try {
                    $loja       = $empresa->lojas()->where('alltech_id', $value->alltech_id)->first();

                    $alltech_id = preg_replace('/\D/', '', trim(strval($dado['CODIGO'])));
                    $grade_alltech_id =  preg_replace('/\D/', '', trim(strval($dado['GRADE'])));

                    $grade = $loja->grades()->where('alltech_id', $grade_alltech_id)->first();

                    if ($loja && $grade) {

                        if (!$loja->iGrades()->where('alltech_id', $alltech_id)->where('id_grade_alltech_id', $grade_alltech_id)->first()) {

                            $loja->iGrades()->create([
                                'alltech_id' => $alltech_id,
                                'id_grade_alltech_id' =>  $grade_alltech_id,
                                'grade_id' => $grade ? $grade->id : null,
                                'tam' => strval($dado['TAM']),
                                'fator' => intval($dado['FATOR']),
                                'tipo' => strval($dado['TIPO']),
                            ]);
                            $importado++;
                        } else {
                            $igrade =  $loja->iGrades()->where('alltech_id', $alltech_id)->where('id_grade_alltech_id', $grade_alltech_id)->first();

                            $igrade->update([
                                'id_grade_alltech_id' =>  $grade_alltech_id,
                                'grade_id' => $grade->id ? $grade->id : null,
                                'tam' => strval($dado['TAM']),
                                'fator' => intval($dado['FATOR']),
                                'tipo' => strval($dado['TIPO']),
                            ]);
                            $importado++;
                        }
                    }
                } catch (\Exception $e) {
                    echo $e->getMessage() . ' ,' . $filename;
                }
            }
        }
        return  $importado++;
    }



    static function produtos($empresa, $filename)
    {

        $self = new ImportXml;
        $dados  = $self->getDados($filename, $empresa);
        $dados  = $dados['dados'];

        $importado  = 0;
        $lojas = $empresa->lojas()->get();

        foreach ($dados as $dado) {
            if (isset($dado['@attributes'])) {
                $dado = $dado['@attributes'];
            }
            foreach ($lojas as $value) {

                try {
                    $loja       = $empresa->lojas()->where('alltech_id', $value->alltech_id)->first();

                    $grade_alltech_id = array_key_exists('GRADE', $dado) ? preg_replace('/\D/', '', trim(strval($dado['GRADE']))) : null;

                    $grade = $loja->grades()->where('alltech_id', $grade_alltech_id)->first();

                    $alltech_id = preg_replace('/\D/', '', trim(strval($dado['CODIGO'])));

                    //if ($loja && $alltech_id && array_key_exists('UN', $dado) && array_key_exists('VENDA', $dado) && array_key_exists('CUSTO', $dado) && array_key_exists('SITUACAO', $dado) && $dado['SITUACAO'] == 'A') {
                    $produto = $loja->produtos()->where('alltech_id', $alltech_id)->first();

                    if ($dado['SITUACAO'] == 'A') {

                        if (!$produto) {
                            // echo $alltech_id . ".";
                            $loja->produtos()->create([
                                'alltech_id'    => $alltech_id,
                                'refcia'        => array_key_exists('REFCIA', $dado) && trim(strval($dado['REFCIA'])) != "" ? trim(strval($dado['REFCIA'])) : null,
                                'nome'          => array_key_exists('NOME', $dado) ? trim(strval($dado['NOME'])) : 'Produto sem nome',
                                'custo'         => array_key_exists('CUSTO', $dado) ? strval($dado['CUSTO']) : null,
                                'preco'         => array_key_exists('VENDA', $dado) ? strval($dado['VENDA']) : null,
                                'un'            => array_key_exists('UN', $dado) ? strval($dado['UN']) : null,
                                'situacao'      => array_key_exists('SITUACAO', $dado) ? strval($dado['SITUACAO']) : null,
                                'grade_id' => $grade && $grade_alltech_id != '0' ? $grade->id : null,
                            ]);
                            $importado++;
                        } else {
                            // $produto = $loja->produtos()->where('alltech_id', $alltech_id)->first();
                            //echo $alltech_id . ";";
                            $produto->update([
                                'nome'          => array_key_exists('NOME', $dado) ? trim(strval($dado['NOME'])) : 'Produto sem nome',
                                'refcia'        => array_key_exists('REFCIA', $dado) && trim(strval($dado['REFCIA'])) != "" ? trim(strval($dado['REFCIA'])) : null,
                                'custo'         => array_key_exists('CUSTO', $dado) ? strval($dado['CUSTO']) : null,
                                'preco'         => array_key_exists('VENDA', $dado) ? strval($dado['VENDA']) : null,
                                'situacao'      => array_key_exists('SITUACAO', $dado) ? strval($dado['SITUACAO']) : null,
                                'grade_id' => $grade && $grade_alltech_id != '0' ? $grade->id : null,
                            ]);
                            $importado++;
                        }
                    } elseif ($dado['SITUACAO'] == 'I') {
                        //} elseif (array_key_exists('SITUACAO', $dado) && $dado['SITUACAO'] == 'I') {     
                        $produto = $loja->produtos()->where('alltech_id', $alltech_id)->delete();
                    }
                } catch (\Exception $e) {
                    echo $e->getMessage() . ' ,' . $filename;
                }
            }
        }
        return $importado;
    }

    static function estoque($empresa, $filename)
    {
        $self = new ImportXml;
        $dados  = $self->getDados($filename, $empresa);
        $dados  = $dados['dados'];

        $importado  = 0;

        foreach ($dados as $dado) {
            if (isset($dado['@attributes'])) {
                $dado = $dado['@attributes'];
            }
            try {
                /*percorre cada dado para verificar as chave do array de dado
                 *para identificar a chave da loja */
                foreach ($dado as $key => $value) {

                    if (substr($key, 0, 3) == 'LJ0') {
                        $lojaId = substr($key, -1);
                        // $key_dado = $key;
                        $loja = $empresa->lojas()->where('alltech_id', preg_replace('/\D/', '', trim(strval($lojaId))))->first();
                        $self->insereEstoque($loja, $dado, $key);

                        $importado++;
                    } else {
                        $lojaId = substr($key, -2);
                        // $key_dado = $key;
                        $loja = $empresa->lojas()->where('alltech_id', preg_replace('/\D/', '', trim(strval($lojaId))))->first();
                        $self->insereEstoque($loja, $dado, $key);

                        $importado++;
                    }
                }
                //echo $importado . "\n";
            } catch (\Exception $e) {
                echo $e->getMessage() . ' ,' . $filename;
            }
        }
        return $importado;
    }

    public function insereEstoque($loja, $dado, $key)
    {
        //monta consulta de loja pela chave da loja existente no arquivo
        $situacao = array_key_exists('SITUACAO', $dado) ? $dado['SITUACAO'] : null;
        $produto_estoque = false;
        $produto_alltech_id = array_key_exists('CODIGO', $dado) && preg_replace('/\D/', '', trim(strval($dado['CODIGO']))) != '' ? preg_replace('/\D/', '', trim(strval($dado['CODIGO']))) : null;

        if ($loja &&  $situacao == 'A') {

            //codigo verificação no produto
            $produto = $loja->produtos()->where('alltech_id', $produto_alltech_id)->first();

            if ($produto) {
                $quantidade = $dado[$key];

                //codbar verificação no estoque
                if ($produto->grade_id) {

                    $produto_estoque =  $loja->estoques()->where('produto_id', $produto->id)->where('codbar', trim($dado['CODBAR']))->first();
                } else {
                    $produto_estoque =  $loja->estoques()->where('produto_id', $produto->id)->first();
                }

                if (!$produto_estoque) {

                    $loja->estoques()->create([
                        'alltech_id'    => $produto_alltech_id,
                        'codbar'    => array_key_exists('CODBAR', $dado) ? trim($dado['CODBAR']) : null,
                        'tam'    => array_key_exists('TAM', $dado) &&  trim($dado['TAM']) != '' ? trim(strval($dado['TAM'])) : null,
                        'cor'    => array_key_exists('COR', $dado) &&  preg_replace('/\D/', '', trim($dado['COR'])) != '0' ? preg_replace('/\D/', '', intval(trim($dado['COR']))) : null,
                        'produto_id'    => $produto->id,
                        'saldo'         => $quantidade ? $quantidade : 0,
                        //'situacao' => strval($dado['SITUACAO']),
                    ]);
                    //   echo 'cr '.trim($dado['CODBAR']) . '\n';
                    // $importado++;
                } else {

                    $produto_estoque->update([
                        'alltech_id'    => $produto_alltech_id,
                        'codbar'    => array_key_exists('CODBAR', $dado) ? trim(strval($dado['CODBAR'])) : null,
                        'tam'    => array_key_exists('TAM', $dado) &&  trim($dado['TAM']) != '' ? trim(strval($dado['TAM'])) : null,
                        'cor'    => array_key_exists('COR', $dado) &&  preg_replace('/\D/', '', trim($dado['COR'])) != '0' ? preg_replace('/\D/', '', intval(trim($dado['COR']))) : null,
                        'produto_id'    => $produto->id,
                        'saldo'         => $quantidade ? $quantidade : 0,
                        //'situacao' => strval($dado['SITUACAO']),
                    ]);
                    //  echo 'up ' . trim($dado['CODBAR']) . "\n";
                    // echo $quantidade ? $quantidade :  $key_dado . "\n";
                    //$importado++;
                }
                echo trim($dado['CODBAR']) . "\n";
            }
        }

        if ($loja && $situacao == 'I') {

            //vem de codbar verificação no estoque
            $produto = $loja->produtos()->where('alltech_id', $produto_alltech_id)->first();

            if ($produto) {

                if ($produto->grade_id) {

                    $produto_estoque = $loja->estoques()->where('codbar', trim($dado['CODBAR']))->first();
                    //echo $produto . '.'."\n";
                    $produto_estoque ? $produto_estoque->delete() : false;
                } else {

                    $produto_estoque =  $loja->estoques()->where('produto_id', $produto->id)->first();
                    //echo $produto . ',' . "\n";
                    $produto_estoque ? $produto_estoque->delete() : false;
                }
            }
        }
        // return $importado;
    }

    /** Privates */
    public function importarVenda($loja, $alltech_id, $dado)
    {
        //['CODBAR'] -> pertence -> PRODUTO 
        $estoque = $loja->estoques()->where('codbar',  trim(strval($dado['CODBAR'])))->first();
        $produto = $loja->produtos()->where('alltech_id',   preg_replace('/\D/', '', trim(strval($dado['CODIGO']))))->first();

        $venda   = $loja->vendas()->where('alltech_id', $alltech_id)->first();

        if ($venda) {
            $produto_vendaItens_nota = $loja->vendaItens()->where('alltech_id', $venda->nvenda)->first();
            if (!$produto_vendaItens_nota) {

                echo "criando venda -- $alltech_id \n";
                $loja->vendaItens()->create([
                    'alltech_id' => $alltech_id,
                    //'nota'       => trim(strval($dado['NOTA'])),
                    'estoque_id' => $estoque ? $estoque->id : null,
                    'produto_id' => $produto ? $produto->id : null, //produto CodBar
                    'venda_id'   => $venda->id,
                    'valor'      => strval($dado['VENDA']),
                    'custo'      => strval($dado['CUSTO']),
                    'quantidade' => abs(intval($dado['QUANTIDADE'])),
                    'data'       => is_string($dado['DATA']) ? \Carbon\Carbon::createFromFormat('Ymd', substr($dado['DATA'], 0, 8)) : null,
                ]);
            } else {
                //echo "atualiza venda -- $alltech_id \n";
                $produto_vendaItens_nota->update([
                    'alltech_id' => $alltech_id,
                    'produto_id' => $produto ? $produto->id : null,
                    //'nota'       => trim(strval($dado['NOTA'])),
                    'venda_id'   => $venda ? $venda->id : null,
                    'valor'      => strval($dado['VENDA']),
                    'quantidade' => abs(intval($dado['QUANTIDADE'])),
                    'custo'      => strval($dado['CUSTO']),
                    'data'       => is_string($dado['DATA']) ? \Carbon\Carbon::createFromFormat('Ymd', substr($dado['DATA'], 0, 8)) : null
                ]);
            }
        }
    }

    public function importarDevolucao($loja, $alltech_id, $dado)
    {
        $estoque = $loja->estoques()->where('codbar',  trim(strval($dado['CODBAR'])))->first();
        $produto = $loja->produtos()->where('alltech_id',   preg_replace('/\D/', '', trim(strval($dado['CODIGO']))))->first();

        $devolucao  = $loja->devolucoes()->where('alltech_id', trim($dado['NOTA']))->first();

        if ($devolucao) {
            $produto_devolucaoItens_nota = $loja->devolucaoItens()->where('alltech_id', $devolucao->nvenda)->first();
            if (!$produto_devolucaoItens_nota) {

                echo "criando  devoluções  -- $alltech_id \n";
                $loja->devolucaoItens()->create([
                    'alltech_id'    => $alltech_id,
                    'estoque_id' => $estoque ? $estoque->id : null,
                    'produto_id' => $produto ? $produto->id : null, //produto CodBar
                    'devolucao_id'  =>  $devolucao->id,
                   // 'nota'          => trim(strval($dado['NOTA'])),
                    'valor'         => strval($dado['VENDA']),
                    'custo'         => strval($dado['CUSTO']),
                    'quantidade'    => abs(intval($dado['QUANTIDADE'])),
                    'data'          => is_string($dado['DATA']) ? \Carbon\Carbon::createFromFormat('Ymd', substr($dado['DATA'], 0, 8)) : null
                ]);
            } else {
                  echo "atualizando devolucao -- $alltech_id";

                $produto_devolucaoItens_nota->update([
                    'alltech_id' => $alltech_id,
                    'produto_id'    => $produto ? $produto->id : null,
                    'estoque_id' => $estoque ? $estoque->id : null,
                    'devolucao_id'  => $devolucao ? $devolucao->id : null,
                    //'nota'          => trim(strval($dado['NOTA'])),
                    'valor'         => strval($dado['VENDA']),
                    'custo'         => strval($dado['CUSTO']),
                    'quantidade'    => abs(intval($dado['QUANTIDADE'])),
                    'data'          => is_string($dado['DATA']) ? \Carbon\Carbon::createFromFormat('Ymd', substr($dado['DATA'], 0, 8)) : null
                ]);
            }
        }
    }

    /** Uso interno */
    public function getDados($filename, $empresa)
    {
        try {

            $xml = simplexml_load_string(utf8_encode(Storage::get($filename)), "SimpleXMLElement", LIBXML_NOCDATA);
            $json       = json_encode($xml);
            $dados      = json_decode($json, TRUE);

            return [
                'dados' => $dados['ROWDATA']['ROW'],
                'loja'  => null
            ];
        } catch (\Exception $e) {

            // function utf8_for_xml para caso tenha caracter especial e não conseguir rodar o utf8_encode
            $xml        = simplexml_load_string($this->utf8_for_xml(Storage::get($filename)), "SimpleXMLElement", LIBXML_NOCDATA);
            $json       = json_encode($xml);
            $dados      = json_decode($json, TRUE);

            return [
                'dados' => $xml == false ? [] : $dados['ROWDATA']['ROW'],
                'loja'  => null
            ];
            // print_r($dados);
        } catch (\Exception $e) {
            echo $e->getMessage() . ' ,' . $filename;
        }
    }

    public function utf8_for_xml($string)
    {
        return preg_replace('/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u', ' ', $string);
    }

    public function clienteVendaAvista($empresa, $importado)
    {
        $lojas = $empresa->lojas()->get();

        $dadosClienteVendaAvista = [
            'alltech_id'    => '999999',
            'nome'          => 'VENDA A VISTA',
            'docto'         => null,
            'tipo'          => 'F',
            'email'         => null,
            'fone1'         => null,
            'fone2'         => null,
            'celular'       => null,
        ];

        foreach ($lojas as $key => $value) {

            $loja       = $empresa->lojas()->where('alltech_id', $value->alltech_id)->first();

            if ($loja) {


                if (!$loja->clientes()->where('alltech_id', '999999')->first()) {

                    $loja->clientes()->create($dadosClienteVendaAvista);

                    $importado++;
                } else {
                    $loja->clientes()->where('alltech_id', '999999')->first()->update($dadosClienteVendaAvista);
                    $importado++;
                }
            }
        }

        return $importado;
    }

    // public function compararArrays($dadosBanco, $lojas)
    // {
    //     ini_set('memory_limit', '512M');
    //     $dadosB = [];
    //     $banco = [];

    //     for ($i = 1; $i <= count($lojas); $i++) {
    //         if (sizeof($dadosBanco[$i]) > 0) {
    //             foreach ($dadosBanco[$i] as $value) {

    //                 $dadosB[] =  $value['alltech_id'];
    //                 $banco[$i] = $dadosB;
    //             }
    //         } else {
    //             $banco[$i] = $dadosB;
    //         }
    //         $dadosB = array();
    //     }
    //     return $banco;
    // }

    // public function getLojas($keyValue = false)
    // {


    //     if ($keyValue) {
    //         return [
    //             'LJ01' => '1.', 'LJ02' => '2.', 'LJ03' => '3.', 'LJ04' => '4.', 'LJ05' => '5.', 'LJ06' => '6.', 'J07' => '7.', 'J08' => '8.', 'J09' => '9.', 'J10' => '10.', 'J11' => '11.', 'J12' => '12.', 'J13' => '13.', 'J14' => '14.', 'J15' => '15.', 'J16' => '16.', 'J17' => '17.', 'J18' => '18.', 'J19' => '19.', 'J20' => '20.', 'J21' => '21.'
    //         ];
    //     }

    //     return [
    //         ['1.', 'LJ01'], ['2.', 'LJ02'], ['3.', 'LJ03'], ['4.', 'LJ04'], ['5.', 'LJ05'], ['6.', 'LJ06'], ['7.', 'J07'], ['8.', 'J08'], ['9.', 'J09'], ['10.', 'J10'], ['11.', 'J11'], ['12.', 'J12'], ['13.', 'J13'], ['14.', 'J14'], ['15.', 'J15'], ['16.', 'J16'], ['17.', 'J17'], ['18.', 'J18'], ['19.', 'J19'], ['20.', 'J20'], ['21.', 'J21']
    //     ];
    // }
}
