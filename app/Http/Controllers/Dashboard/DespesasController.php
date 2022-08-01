<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Despesa;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Session;

class DespesasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $despesas = [];
        $valores = ['valorAb' => [], 'valorPg' => [], 'valorCa' => []];

        if ($request->fornecedor) {
            $nome = $request->fornecedor;

            $consulta = Despesa::with('fornecedor')
                ->where('loja_id', $request->loja)->whereHas('fornecedor', function (Builder $query) use ($nome) {
                    $query->where('nome', 'like', '%' . $nome . '%');
                })->whereIn('posicao', $request->posicao ? [$request->posicao] : ['AB', 'PG', 'CA'])
                ->whereDate('emissao', '>=', $request->dataInicial)->whereDate('vencimento', '<=', $request->dataFinal)
                ->orderBy('emissao');
        } else {

            $consulta = Despesa::with('fornecedor')
                ->where('loja_id', $request->loja ? $request->loja : auth()->user()->loja_id)
                ->whereIn('posicao', $request->posicao ? [$request->posicao] : ['AB', 'PG', 'CA'])
                ->whereDate('emissao', '>=', $request->dataInicial ? $request->dataInicial : date("Y-m-d", strtotime("-3 month")))
                ->whereDate('vencimento', '<=', $request->dataFinal ? $request->dataFinal : date("Y-m-d", strtotime("3 month")))
                ->orderBy('emissao');
        }

        //cria paginação e o grupo de despesa por nota 
        $despesasCollection = $consulta->get();
        $despesasCollection = $despesasCollection->groupBy('nota');

        $despesas = $consulta->groupBy('despesas.nota')->paginate(30);

        //separa valores para soma na view
        foreach ($despesasCollection as $key => $notas) {
            foreach ($notas as $key => $n) {
                if ($n->posicao == "AB") {
                    $valores['valorAb'][] = $n->valor_aberto;
                } elseif ($n->posicao == "PG") {
                    $valores['valorPg'][] = $n->valor;
                } elseif ($n->posicao == "CA") {
                    $valores['valorCa'][] = $n->valor;
                }
            }
        }
        foreach ($valores as $key => $value) {
            $valores[$key] = array_sum($value);
        }
    

        Session::put('datas', [$request->dataInicial, $request->dataFinal]);
        Session::put('posicao', $request->posicao);
        Session::put('loja', $request->loja);
        $posicoes = ['Todas' => '0', 'Pago' => 'PG', 'Aberto' => 'AB', 'Cancelado' => 'CA'];

        return view('dashboard.despesas.index', compact('despesas', 'despesasCollection', 'posicoes', 'valores'));
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
