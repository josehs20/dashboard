<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Produto;
use Illuminate\Support\Facades\Session;


class ProdutosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $produtos = [];

        if ($request->produto && $request->loja) {

            $produtos = Produto::with('estoques', 'estoque', 'grades')->where('loja_id',  $request->loja)
                ->whereRaw("nome like '%{$request->produto}%'")->get()->reject(function ($produto) {
                    return $produto->estoque == null;
                });
            $produtos = $produtos->count() ? $produtos->toQuery()->paginate(30) : [];
        } else {
            $produtos = Produto::with('estoques', 'estoque', 'grades')->where('loja_id',  $request->loja ? $request->loja : auth()->user()->loja_id)
                ->get()->reject(function ($produto) {
                    return $produto->estoque == null;
                });

            $produtos = $produtos->count() ? $produtos->toQuery()->paginate(30) : [];
        }

        Session::put('loja', $request->loja);

        return view('dashboard.produtos.index', compact('produtos'));
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
