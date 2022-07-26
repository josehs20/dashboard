<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Caixa;
use Illuminate\Support\Facades\Session;

class CaixaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $controles = [];
        $caixas = [];

        $caixas = Caixa::where('loja_id', $request->loja ? $request->loja : auth()->user()->loja_id)
            ->whereBetween('data', [$request->dataInicial ? $request->dataInicial : date("Y-m-d", strtotime('-3 month')), $request->dataFinal ? $request->dataFinal : date("Y-m-d", strtotime('3 month'))])
            // ->whereDate('data', '>=', $request->dataInicial)
            // ->whereDate('data', '<=', $request->dataFinal)
            ->orderBy('data')->get();

        foreach ($caixas as $key => $c) {
            $controles[] = $c->controle;
        }

        if ($request->controle) {

            $caixas = $caixas->where('controle', $request->controle);
        }

        $caixas = $caixas->count() ? $caixas->toQuery()->paginate(40) : [];

        $controles = array_unique($controles);

        Session::put('controle', $request->controle ? $request->controle : false);
        Session::put('datas', [$request->dataInicial, $request->dataFinal]);
        Session::put('loja', $request->loja);



        return view('dashboard.caixa.index', compact('caixas', 'controles'));
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
