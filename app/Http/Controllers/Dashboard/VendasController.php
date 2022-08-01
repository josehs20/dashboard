<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Devolucao;
use App\Models\Venda;
use App\Models\VendaItem;
use Illuminate\Support\Facades\Session;

class VendasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $resumo = ['venda' => [], 'devolucao' => []];
        $resumoPd = [];
        $custo = [];
        $valores = [
            'totalCusto' => null,
            'totalDevolucao' => null,
            'totalCancelamento' => null,
            'totalLucro' => null,
            'totalAvista' => null,
            'totalAprazo' => null
        ];

        $consulta['venda'] = Venda::with('itens')->where('loja_id', $request->loja ? $request->loja : auth()->user()->loja_id)

            ->whereBetween('data', [$request->dataInicial ? $request->dataInicial : date("Y-m-d", strtotime('-3 month')), $request->dataFinal ? $request->dataFinal : date("Y-m-d", strtotime('3 month'))]);
        $consulta['devolucao'] = Devolucao::with('itens')->where('loja_id', $request->loja ? $request->loja : auth()->user()->loja_id)
            ->whereBetween('data', [$request->dataInicial ? $request->dataInicial : date("Y-m-d", strtotime('-3 month')), $request->dataFinal ? $request->dataFinal : date("Y-m-d", strtotime('3 month'))]);

        $resumo['venda'] = $consulta['venda']->get()->groupBy('tipo');
        $resumo['devolucao'] = $consulta['devolucao']->get()->groupBy('tipo');
        $resumoCollection = $consulta['venda']->groupBy('data')->paginate(20);

        //organiza itens por dia e total de cada tipo total custo
        foreach ($resumo as $tipo => $r) {
            foreach ($r as $tipoV => $vendas) {
                foreach ($vendas as $key => $v) {

                    $resumoPd[$v->data->format('Y-m-d')]['vendedor'] = 

                    $resumoPd[$v->data->format('Y-m-d')]['TotalDiaDevolucao'][] =  $tipo == 'devolucao' && $v->itens ? $v->total : null;

                    $resumoPd[$v->data->format('Y-m-d')]['TotalDiaCancelamento'][] = $v->data_cancelamento && $v->itens ? $v->total : null;

                    $resumoPd[$v->data->format('Y-m-d')]['TotalDiaAvista'][] = $v->tipo == 'avista' && $v->itens && !$v->data_cancelamento ? $v->total : null;
                    $valores['totalAvista'][] = $v->tipo == 'avista' && $v->itens && !$v->data_cancelamento ? $v->total : null;

                    $resumoPd[$v->data->format('Y-m-d')]['TotalDiaAprazo'][] = $v->tipo == 'aprazo' && $v->itens && !$v->data_cancelamento ? $v->total : null;
                    $valores['totalAprazo'][] = $v->tipo == 'aprazo' && $v->itens && !$v->data_cancelamento ? $v->total : null;

                    $resumoPd[$v->data->format('Y-m-d')]['custo'] = $v->itens ?  $v->itens->sum('custo') : null;
                    $custo[] = $v->tipo == 'aprazo' || $v->tipo == 'avista' && $v->itens ? $v->itens->sum('custo') : null;
                }
            }
        }

        $valores['totalAvista'] = $valores['totalAvista'] ? array_sum($valores['totalAvista']) : 0;
        $valores['totalAprazo'] = $valores['totalAprazo'] ? array_sum($valores['totalAprazo']) : 0;
        $valores['totalCusto'] = $custo ? array_sum($custo) : 0;
        //devolucao valor negativo
        $valores['totalDevolucao'] = count($resumo['devolucao']) ? $resumo['devolucao']['D']->sum('total') : 0;

        $CancelamentoVendaAprazo = count($resumo['venda']) && count($resumo['venda']['aprazo']) ? $resumo['venda']['aprazo']->whereNotNull('data_cancelamento')->sum('total') : 0;
        $CancelamentoVendaAvista = count($resumo['venda']) && count($resumo['venda']['avista']) ? $resumo['venda']['avista']->whereNotNull('data_cancelamento')->sum('total') : 0;
        $valores['totalCancelamento'] = $CancelamentoVendaAprazo + $CancelamentoVendaAvista;

        $valores['total'] = $valores['totalAvista'] + $valores['totalAprazo'] + $valores['totalCancelamento'] - $valores['totalDevolucao'];
        $valores['totalLucro'] = $valores['total'] - $valores['totalCusto'] - $valores['totalCancelamento'] + $valores['totalDevolucao'];

        Session::put('datas', [$request->dataInicial, $request->dataFinal]);
        Session::put('loja', $request->loja);

        return view('dashboard.vendas.index', compact('resumoCollection', 'resumo', 'resumoPd', 'valores'));
    }

    public function produtos_mais_vendidos(Request $request)
    {

        $produtosMaisVendidos = VendaItem::with('produto')->selectRaw("produto_id, count(*) total_vendas")
            ->where('loja_id', $request->loja ? $request->loja : auth()->user()->loja_id)
            ->whereDate('data', '>=', $request->dataInicial ? $request->dataInicial : date("Y-m-d", strtotime("-3 month")))
            ->whereDate('data', '<=', $request->dataFinal ? $request->dataFinal : date("Y-m-d", strtotime("3 month")))
            ->groupByRaw('produto_id')->orderByRaw("count(*) desc")->paginate(30);

        Session::put('datas', [$request->dataInicial, $request->dataFinal]);

        return view('dashboard.produtos-mais-vendidos.index', compact('produtosMaisVendidos'));
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
