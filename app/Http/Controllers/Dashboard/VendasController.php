<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Devolucao;
use App\Models\Funario;
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
       
        $consulta['venda'] = Venda::with('itens')->where('loja_id', $request->loja ? $request->loja : auth()->user()->loja_id)
            ->whereBetween('data', [$request->dataInicial ? $request->dataInicial : date("Y-m-d", strtotime('-3 month')), $request->dataFinal ? $request->dataFinal : date("Y-m-d", strtotime('3 month'))]);

        $consulta['devolucao'] = Devolucao::with('itens')->where('loja_id', $request->loja ? $request->loja : auth()->user()->loja_id)
            ->whereBetween('data', [$request->dataInicial ? $request->dataInicial : date("Y-m-d", strtotime('-3 month')), $request->dataFinal ? $request->dataFinal : date("Y-m-d", strtotime('3 month'))]);

        $resumo['venda'] = $consulta['venda']->get()->groupBy('tipo');
        $resumo['devolucao'] = $consulta['devolucao']->get()->groupBy('tipo');
        $resumoCollection = $consulta['venda']->groupBy('data')->paginate(20);

        //organiza itens por dia e total de cada tipo total custo e vendedor
        $estrutura = $this->estruturaVendas($resumo);
        $resumoPd = $estrutura['resumoPd'];
        $valores = $estrutura['valores'];

        //estrutura totais por vendedor e dia
        $resumoPd = $this->somaTotaisPorVendedor($resumoPd);


        //calcula totais dos cards
        $valores = $this->calculaTotais($valores, $resumo);

        Session::put('datas', [$request->dataInicial, $request->dataFinal]);
        Session::put('loja', $request->loja);

        return view('dashboard.vendas.index', compact('resumoCollection', 'resumo', 'resumoPd', 'valores'));
    }

    public function estruturaVendas($consulta)
    {
        $vendedores = Funario::where('empresa_id', auth()->user()->loja->empresa->id)->get();
        $resumoPd = [];
        $valores = [
            'totalCusto' => null,
            'totalDevolucao' => null,
            'totalCancelamento' => null,
            'totalLucro' => null,
            'totalAvista' => null,
            'totalAprazo' => null
        ];
        foreach ($consulta as $tipo => $r) {
            foreach ($r as $tipoV => $vendas) {
                foreach ($vendas as $key => $v) {
                    $vendedor = $vendedores->where('alltech_id', $v->vendedor)->first() ? $vendedores->where('alltech_id', $v->vendedor)->first() : '0';
                    $nome = $vendedor == '0' ? 'NI' : explode(" ", $vendedor->nome);
                    $nome = $vendedor == '0' ? $nome : $nome[0];
                    $id = $vendedor == '0' ? '0' : $vendedor->id;

                    $resumoPd[$v->data->format('Y-m-d')]['TotalDiaDevolucao'][] =  $tipo == 'devolucao' && $v->itens ? $v->total : null;
                    $resumoPd[$v->data->format('Y-m-d')]['vendedores'][$id . '-' . $nome]['TotalDiaDevolucao'][] = $tipo == 'devolucao' && $v->itens ? $v->total : null;

                    $resumoPd[$v->data->format('Y-m-d')]['TotalDiaCancelamento'][] = $v->data_cancelamento && $v->itens ? $v->total : null;
                    $resumoPd[$v->data->format('Y-m-d')]['vendedores'][$id . '-' . $nome]['TotalDiaCancelamento'][] = $v->data_cancelamento && $v->itens ? $v->total : null;

                    $resumoPd[$v->data->format('Y-m-d')]['TotalDiaAvista'][] = $v->tipo == 'avista' && $v->itens && !$v->data_cancelamento ? $v->total : null;
                    $resumoPd[$v->data->format('Y-m-d')]['vendedores'][$id . '-' . $nome]['TotalDiaAvista'][] = $v->tipo == 'avista' && $v->itens && !$v->data_cancelamento ? $v->total : null;
                    $valores['totalAvista'][] = $v->tipo == 'avista' && $v->itens && !$v->data_cancelamento ? $v->total : null;

                    $resumoPd[$v->data->format('Y-m-d')]['TotalDiaAprazo'][] = $v->tipo == 'aprazo' && $v->itens && !$v->data_cancelamento ? $v->total : null;
                    $resumoPd[$v->data->format('Y-m-d')]['vendedores'][$id . '-' . $nome]['TotalDiaAprazo'][] = $v->tipo == 'aprazo' && $v->itens && !$v->data_cancelamento ? $v->total : null;
                    $valores['totalAprazo'][] = $v->tipo == 'aprazo' && $v->itens && !$v->data_cancelamento ? $v->total : null;

                    $resumoPd[$v->data->format('Y-m-d')]['custo'][] = $v->itens ?  $v->itens->sum('custo') : null;
                    $resumoPd[$v->data->format('Y-m-d')]['vendedores'][$id . '-' . $nome]['custo'][] = $v->itens ?  $v->itens->sum('custo') : null;
                    // $resumoPd[$v->data->format('Y-m-d')]['custo'][$vendedor->id][] = $v->itens ?  $v->itens->sum('custo') : null;
                    $valores['totalCusto'][] = $v->tipo == 'aprazo' || $v->tipo == 'avista' && $v->itens ? $v->itens->sum('custo') : null;
                }
            }
        }
        $estrutura = ['resumoPd' => $resumoPd, 'valores' => $valores];
        return $estrutura;
    }

    public function somaTotaisPorVendedor($resumo)
    {
        foreach ($resumo as $dia => $r) {
            foreach ($r['vendedores'] as $vendedor => $valores) {
                foreach ($valores as $key => $v) {
                    $resumo[$dia]['vendedores'][$vendedor][$key] = array_sum($v);
                }

                $resumo[$dia]['vendedores'][$vendedor]['total'] = $resumo[$dia]['vendedores'][$vendedor]['TotalDiaAvista'] + $resumo[$dia]['vendedores'][$vendedor]['TotalDiaAprazo'];
                $total = $resumo[$dia]['vendedores'][$vendedor]['total'] - $resumo[$dia]['vendedores'][$vendedor]['TotalDiaCancelamento'] + $resumo[$dia]['vendedores'][$vendedor]['TotalDiaDevolucao'];
                if ($resumo[$dia]['vendedores'][$vendedor]['total'] == 0) {
                    $resumo[$dia]['vendedores'][$vendedor]['percentual'] = 0;
                } else {
                    $custoTotal = $resumo[$dia]['vendedores'][$vendedor]['custo'] + $resumo[$dia]['vendedores'][$vendedor]['TotalDiaCancelamento'] + $resumo[$dia]['vendedores'][$vendedor]['TotalDiaDevolucao'];
                    $resumo[$dia]['vendedores'][$vendedor]['percentual'] =
                        //calculo de margem de lucro
                        (($total - $custoTotal) / $total) * 100;
                }
            }
        }
        return $resumo;
    }

    public function calculaTotais($valores, $resumo)
    {
        $valores['totalAvista'] = $valores['totalAvista'] ? array_sum($valores['totalAvista']) : 0;
        $valores['totalAprazo'] = $valores['totalAprazo'] ? array_sum($valores['totalAprazo']) : 0;
        $valores['totalCusto'] =  $valores['totalCusto'] ? array_sum($valores['totalCusto']) : 0;
        //devolucao valor negativo
        $valores['totalDevolucao'] = count($resumo['devolucao']) ? $resumo['devolucao']['D']->sum('total') : 0;
        $CancelamentoVendaAprazo = count($resumo['venda']) && count($resumo['venda']['aprazo']) ? $resumo['venda']['aprazo']->whereNotNull('data_cancelamento')->sum('total') : 0;
        $CancelamentoVendaAvista = count($resumo['venda']) && count($resumo['venda']['avista']) ? $resumo['venda']['avista']->whereNotNull('data_cancelamento')->sum('total') : 0;
        $valores['totalCancelamento'] = $CancelamentoVendaAprazo + $CancelamentoVendaAvista;

        $valores['total'] = $valores['totalAvista'] + $valores['totalAprazo'] + $valores['totalCancelamento'] - $valores['totalDevolucao'];
        $valores['totalLucro'] = $valores['total'] - $valores['totalCusto'] - $valores['totalCancelamento'] + $valores['totalDevolucao'];

        return $valores;
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
}
