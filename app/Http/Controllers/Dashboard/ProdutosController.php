<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Produto;
use Illuminate\Database\Eloquent\Builder;

class ProdutosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
// dd($request->all());
        if ($request->produto) {

            $produtos = Produto::with('estoques')->where('loja_id',  $request->loja)
                ->where('situacao', 'A')->whereRaw("nome like '%{$request->produto}%'")->whereHas('estoque', function (Builder $query) {
                $query->whereNotNull('codbar');
            })->get();

            return view('dashboard.produtos.index', compact('produtos'));
        } else {

            $produtos = Produto::with('estoque')->where('loja_id',  1)
                ->where('situacao', 'a')->whereHas('estoque', function (Builder $query) {
                    $query->whereNotNull('codbar');
                })->paginate(30);

            return view('dashboard.produtos.index', compact('produtos'));
        }
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
