<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Receita;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Session;

class ReceitasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $receitas = [];

        if ($request->cliente) {
            $nome = $request->cliente;

            $consulta = Receita::with('cliente')
                ->where('loja_id', $request->loja)->whereHas('cliente', function (Builder $query) use ($nome) {
                    $query->where('nome', 'like', '%' . $nome . '%');
                })->whereDate('emissao', '>=', $request->dataInicial)
                ->whereDate('vencimento', '<=', $request->dataFinal)
                ->orderBy('emissao')
                ->get();
        } else {

            $consulta = Receita::with('cliente')
                ->where('loja_id', $request->loja ? $request->loja : auth()->user()->loja_id)
                ->whereDate('emissao', '>=', date("Y-m-d", strtotime("-3 month")))
                ->whereDate('vencimento', '<=', date("Y-m-d", strtotime("3 month")))->orderBy('emissao')
                ->get();
        }

        // organiza as contas em aberta caso seja o mesmo usuario 
        //veirifica a nota caso nao tenha a nota cria array com notas
        foreach ($consulta as $key => $r) {
            if ($r->cliente && $r->cliente->id) {
                if (!array_key_exists($r->cliente->id, $receitas)) {
                    $receitas[$r->cliente->id][$r->nota][] = $r;
                } elseif (!array_key_exists($r->nota, $receitas[$r->cliente->id])) {
                    $receitas[$r->cliente->id][$r->nota][] = $r;
                } else {
                    $receitas[$r->cliente->id][$r->nota][] = $r;
                }
            }
        }
        Session::put('datas', [$request->dataInicial, $request->dataFinal]);

        return view('dashboard.receitas.index', compact('receitas'));
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
