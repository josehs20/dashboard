<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Estoque;
use App\Models\Produto;
use App\Repositories\EstoqueProdutoRepository;
use Illuminate\Http\Request;

class ProdutosController extends Controller
{
    //produtos são diretamente relacionados com estoque 
    //por isso a instancia de estoque para as consultas
    public function __construct(Estoque $estoque)
    {
        $this->estoque = $estoque;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    //
    public function index(Request $request)
    {
        $estoqueProdutoRepository = new EstoqueProdutoRepository($this->estoque);
        $loja_id = auth('api')->user()->loja_id;
    
        if ($request->has('atr_produto')) {
            $atributos_produto = 'produto:id,' . $request->atr_produto;
            // $estoques = Estoque::where('loja_id', $loja_id)->with('produtos:id,' . $atributos_produto)->take(10);
            $estoqueProdutoRepository->selectAtributosProduto($loja_id, $atributos_produto);
        } else {
            $produto = 'produto';
            $estoqueProdutoRepository->selectAtributosProduto($loja_id, $produto);
            // $estoques = Estoque::where('loja_id', $loja_id)->with('produtos')->take(10);
        }

        if ($request->has('filtro_produto')) {
            $tabela = 'produtos';
            $estoqueProdutoRepository->filtrosHas_produto($loja_id, $tabela, $request->filtro_produto);
        }

        if ($request->has('atr_estoque')) {

            $estoqueProdutoRepository->selectAtributosEstoque($request->atr_estoque);
        }

        if ($request->has('filtro_estoque')) {
            $estoqueProdutoRepository->filtro_estoque($loja_id, $request->filtro_estoque);
        }
        //  else {
        //     $estoqueProdutoRepository->selectAtributosEstoque($request->atr_estoque);
        // }

        $produtos = $estoqueProdutoRepository->get_estoque_produto();

        return response()->json($produtos, 200);
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
