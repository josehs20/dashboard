<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Caixa;
use App\Models\Devolucao;
use App\Models\Venda;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('dashboard.index');
    }

    public function grafico_pie(Request $request)
    {
        $cores = ['#4e73df', '#36b9cc', '#f6c23e', '#e74a3b', '#858796', '#5a5c69', '#5a5c69', '#00FFFF', '#696969', '8FBC8F', '20B2AA'];
        $data = [];
        $caixas = [];
        $periodo = $request->periodo ? $request->periodo : 3;
        $caixas = Caixa::whereIn('loja_id', auth()->user()->lojas->pluck('id'))
            ->whereDate('data', '>=', date("Y-m-d", strtotime('-'.$periodo.'month')))->get()->groupBy('especie');

        $i = 0;
        foreach ($caixas as $key => $c) {
            if ($key != 'FE' && $key != 'CP') {

                $data[$this->converteEspecieCaixa($key)] =  ['qtd' => count($caixas[$key]), 'cor' => $key == 'DN' ? '#1cc88a' : $cores[$i]];

                $i++;
            }
        }

        return json_encode($data);
    }


    public function converteEspecieCaixa($especie)
    {
        switch ($especie) {
            case 'DN':
                return 'Dinheiro';
                break;
            case 'TK':
                return 'Ticket';
                break;
            case 'CR':
                return 'Crédito';
                break;
            case 'CH':
                return 'Cheque';
                break;
            case 'PR':
                return 'Promissória';
                break;
            case 'CT':
                return 'Cartão';
                break;
            case 'DV':
                return 'Devolução';
                break;
            case 'TR':
                return 'Trc.Dinheiro';
                break;
            case 'TC':
                return 'Trc.Crédito';
                break;
            case 'FE':
                return 'Fechamento';
                break;
            case 'CP':
                return 'Comprovante';
                break;
            case 'CC':
                return 'Cartão Crédito';
                break;
            case 'CD':
                return 'Cartão Débito';
                break;
        }
    }

    public function grafico_area(Request $request)
    {
        $periodo = $request->periodo ? $request->periodo : 3;
        
        $totais = [];
        $totaldevolucoes = [];
        $meses = [
            '01' => 'Jan', '02' => 'Fev', '03' => 'Mar', '04' => 'Abr', '05' => 'Mai', '06' => 'Jun', '07' => 'Jul',
            '08' => 'Ago', '09' => 'Set', '10' => 'Out', '11' => 'Nov', '12' => 'Dez',
        ];
        $valores = ['Lucro' => null, 'Custo' => null];

        $vendas = Venda::with('itens')->whereIn('loja_id', auth()->user()->lojas->pluck('id'))
            ->whereDate('data', '>=', date("Y-m-d", strtotime('-'.$periodo.'month')))->get();

        $devolucoes = Devolucao::with('itens')->whereIn('loja_id', auth()->user()->lojas->pluck('id'))
            ->whereDate('data', '>=', date("Y-m-d", strtotime('-'.$periodo.'month')))->get();

        // $totalVendasMes = $queryV->selectRaw("cast(data as date) data, sum(total) total_valor")->groupByRaw('month(data)');

        //$totalDevolucoesMes = $queryD->selectRaw("cast(data as date) data, sum(total) total_valor")->groupByRaw('month(data)')->get();

        // $vedasItensCusto = $queryV->get();
        foreach ($vendas as $key => $v) {
            if (count($v->itens)) {
                $totais[$v->data->format('m-Y')]['total'][] = $v->total;
                foreach ($v->itens as $key => $i) {
                    $totais[$v->data->format('m-Y')]['custo'][] = $i->custo;
                }
            }
        }

        foreach ($devolucoes as $key => $d) {         
                $totaldevolucoes[$d->data->format('m-Y')]['total'][] = $d->total;
        }

        foreach ($totais as $key => $v) {
            $totais[$key]['data'] = $key;
            $totais[$key]['devolucoes'] = array_key_exists($key, $totaldevolucoes)  ? array_sum($totaldevolucoes[$key]['total']) : 0;
            $totais[$key]['total'] = array_sum($totais[$key]['total']);
            $totais[$key]['custo'] = array_sum($totais[$key]['custo']);
            $totais[$key]['lucro'] = array_key_exists($key, $totaldevolucoes) ? $totais[$key]['total'] - $totais[$key]['custo'] + array_sum($totaldevolucoes[$key]['total']) :  $totais[$key]['total'] - $totais[$key]['custo'];
        }

        return json_encode($totais);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
