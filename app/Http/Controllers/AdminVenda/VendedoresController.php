<?php

namespace App\Http\Controllers\AdminVenda;

use App\Http\Controllers\Controller;
use App\Models\Funario;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class VendedoresController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vendedores = [];
        $vendedores = Funario::where('empresa_id', auth()->user()->loja->empresa->id)->get();

        return view('admin-vendas.index', compact('vendedores'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($admin)
    {
        //a rota esta como admin mas o id é do funario (vendedor)
        $vendedor = $admin;
        $lojas = auth()->user()->lojas;
        return view('admin-vendas.create', compact('lojas', 'vendedor'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $admin)
    {

        $mensagens = [
            'required' => 'O :attribute é obrigatório!',
            'unique'   =>  'O :attribute já existe',
            'min' => 'Necessário no minimo 6 caracteres',
            'confirmed' => 'Senhas não conferem',
            'password.min' => 'Necessário no minimo 6 caracteres',
        ];
        $this->validate($request, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], $mensagens);

        $usuario = User::create([
            'name' =>    $request->name,
            'email' =>   $request->email,
            'password' =>   bcrypt($request->password),
            'loja_id' => $request->lojaPadrao,
            'perfil' => $request->tipo_admin,
        ]);

        Funario::find($admin)->update(['user_id' => $usuario->id, 'status' => 'ativo']);

        $this->setLojas($usuario, $request->lojaPadrao);

        Session::flash('success', 'Usuário ' . $usuario->name . ' Criado com sucesso');
        return redirect()->route('admin.vendedores.index', auth()->user()->id);
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
    public function edit($admin, $vendedore)
    {
        $vendedor = Funario::find($vendedore);
        $vendedorUsuario = $vendedor->usuario;
        $lojas = auth()->user()->lojas;
        return view('admin-vendas.edit', compact('lojas', 'vendedorUsuario'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $admin, $vendedore)
    {
        $mensagens = [
            'required' => 'O :attribute é obrigatório!',
            'unique'   =>  'O :attribute já existe',
            'min' => 'Necessário no minimo 6 caracteres',
            'confirmed' => 'Senhas não conferem',
            'password.min' => 'Necessário no minimo 6 caracteres',
        ];
        $this->validate($request, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['unique:users,email,' . $vendedore],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
        ], $mensagens);

        //vendedor esta recebendo id do usuario de já
        $vendedorUsuario = User::find($vendedore);

        $vendedorUsuario->update([
            'name' =>    $request->name,
            'email' =>   $request->email,
            'password' =>   $request->password ? bcrypt($request->password) : $vendedorUsuario->password,
            'loja_id' => $request->lojaPadrao,
            'perfil' => $request->tipo_admin,
        ]);

        $this->setLojas($vendedorUsuario, $request->lojaPadrao);

        Session::flash('success', 'Usuário ' . $vendedorUsuario->name . ' Atualizado com sucesso');
        return redirect()->route('admin.vendedores.index', auth()->user()->id);
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

    public function status_vendedor($vendedor)
    {
        $vendedor = Funario::find($vendedor);
        if ($vendedor && $vendedor->status == 'ativo') {

            $vendedor->update(['status' => 'inativo']);
            Session::flash('success', $vendedor->nome . ' Alterado com sucesso');
        } elseif ($vendedor && $vendedor->status == 'inativo') {

            $vendedor->update(['status' => 'ativo']);
            Session::flash('success', $vendedor->nome . ' Alterado com sucesso');
        } else {
            Session::flash('error', 'Não foi possivel tente novamente em algusn instantes');
        }
        return redirect()->route('admin.vendedores.index', auth()->user()->id);
    }

    private function setLojas($user, $loja)
    {
        $user->lojas()->detach();

        $user->lojas()->attach($loja);
    }
}
