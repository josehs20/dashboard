<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class EmpresasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $empresas = Empresa::orwhereRaw("nome like '%{$request->nome}%'")->orwhereRaw("pasta like '%{$request->nome}%'")->orderBy('nome')->get();
     
        return view('admin.empresas.index', compact('empresas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $ftp = Storage::disk('ftp')->allDirectories();
        $ftp = array_diff($ftp, ['APPVENDA']);

        return view('admin.empresas.create', compact('ftp'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $empresa = Empresa::create($request->all());

        Session::flash('success', 'Empresa '.$empresa->nome.' Criada Com sucesso');

        return redirect()->route('empresas.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $empresa = Empresa::find($id);
        $usuarios = User::whereIn('loja_id', $empresa->lojas->pluck('id'))->get();

        return view('admin.empresas.show', compact('usuarios', 'empresa'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $empresa = Empresa::find($id);
       // dd($empresa);
        return view('admin.empresas.edit', compact('empresa'));
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
        $empresa = Empresa::find($id);
       
        $empresa->update(['nome' => $request->nome, 'sincronizar' => $request->sincronizar ? $request->sincronizar : 0]);
   
        Session::flash('success', 'Empresa '.$empresa->nome.' Atualizada com sucesso');

        return redirect()->route('empresas.edit', $empresa->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $empresa = Empresa::find($id);
        
        $empresa->delete();

        Session::flash('success', 'Empresa '. $empresa->nome.' excluida com sucesso');
        return redirect()->route('empresas.index');
    }
}
