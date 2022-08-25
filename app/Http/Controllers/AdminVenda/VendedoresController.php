<?php

namespace App\Http\Controllers\AdminVenda;

use App\Http\Controllers\Controller;
use App\Models\Funario;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
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
    //admin como param é id do funario 'funcionario empresa/
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

        try {
            $funcionario = Funario::find(decrypt($admin));
        } catch (\Exception $e) {
            Session::flash('error', 'vendedor inválido' . $e->getMessage());
            return redirect()->route('admin.vendedores.index', auth()->user()->id);
        }

        //segurança, verifica se fucionario pertence a mesma empresa do admin venda
        if ($funcionario->empresa->id === auth()->user()->loja->empresa->id) {

            $usuario = User::create([
                'name' =>    $request->name,
                'email' =>   $request->email,
                'password' =>   bcrypt($request->password),
                'loja_id' => $request->lojaPadrao,
                'perfil' => $request->tipo_admin,
            ]);

            if ($request->tipo_admin == 'vendedor') {

                if ($this->requisicaoStoreVendaExterna($funcionario, $usuario, $request)) {
                    Session::flash('success', 'Usuário ' . $usuario->name . ' Criado com sucesso');
                    return redirect()->route('admin.vendedores.index', auth()->user()->id);
                } else {
                    Session::flash('error', 'Não foi possível tente novamente em algusn instantes');
                    return redirect()->route('admin.vendedores.index', auth()->user()->id);
                }
            } else {
                //se não cria só no banco para consulta dashboard
                $funcionario->update(['user_id' => $usuario->id, 'status' => 'ativo']);
                $this->setLojas($usuario, $request->lojaPadrao);
                Session::flash('success', 'Usuário ' . $usuario->name . ' Criado com sucesso');
                return redirect()->route('admin.vendedores.index', auth()->user()->id);
            }
        }
        Session::flash('error', 'vendedor inválido');
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
        $vendedor = Funario::find(decrypt($vendedore));
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
        try {
            $funcionario = Funario::where('user_id', decrypt($vendedore))->first();
        } catch (\Exception $e) {
            Session::flash('error', 'vendedor inválido');
            return redirect()->route('admin.vendedores.index', auth()->user()->id);
        }

        $mensagens = [
            'required' => 'O :attribute é obrigatório!',
            'unique'   =>  'O :attribute já existe',
            'min' => 'Necessário no minimo 6 caracteres',
            'confirmed' => 'Senhas não conferem',
            'password.min' => 'Necessário no minimo 6 caracteres',
        ];
        $this->validate($request, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['unique:users,email,' . $funcionario->usuario->id],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
        ], $mensagens);

        if ($funcionario->empresa->id === auth()->user()->loja->empresa->id) {

            //vendedor esta recebendo id do usuario
            $vendedorUsuario = User::find($funcionario->usuario->id);

            if ($request->tipo_admin == 'vendedor') {
                $response = $this->requisicaoUpdateVendaExterna($funcionario, $vendedorUsuario, $request);

                if ($response['success']) {
                    Session::flash('success', 'Usuário ' . $response['funcionario'] . ' Atualizadp com sucesso');
                    return redirect()->route('admin.vendedores.index', auth()->user()->id);
                } else {
                    Session::flash('error', 'Não foi possível tente novamente em alguns instantes');
                    return redirect()->route('admin.vendedores.index', auth()->user()->id);
                }
            } else {
                $vendedorUsuario->update([
                    'name' =>    $request->name,
                    'email' =>   $request->email,
                    'password' =>   $request->password ? bcrypt($request->password) : $vendedorUsuario->password,
                    'loja_id' => $request->lojaPadrao,
                    'perfil' => $request->tipo_admin,
                ]);
                $funcionario->update(['user_id' => $vendedorUsuario->id, 'status' => 'ativo']);
                $this->setLojas($vendedorUsuario, $request->lojaPadrao);
                Session::flash('success', 'Usuário ' . $vendedorUsuario->name . ' Atualizado com sucesso');
                return redirect()->route('admin.vendedores.index', auth()->user()->id);
            }
        }
        Session::flash('error', 'vendedor inválido');
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


    // -----------------Requisições venda externa---------------

    //cria user para logar como auth no app, venda externa
    private function requisicaoStoreVendaExterna($funcionario, $usuario, $request)
    {
        $token = ['api-key-alltech' => config('app.api_key_alltech')];

        $response =  Http::withHeaders($token)->post('http://localhost:8001/api/create/user/venda-externa', [
            'name' =>    $request->name,
            'email' =>   $request->email,
            'password' =>   bcrypt($request->password),
            'loja' => $request->lojaPadrao,
            'perfil' => $request->tipo_admin,
            'user_alltech' =>$usuario->id,
        ]);

        $response = $response->object();
        if ($response->success == true) {
            
            $funcionario->update(['user_id' => $usuario->id, 'status' => 'ativo']);

            $this->setLojas($usuario, $request->lojaPadrao);

            Session::flash('success', 'Usuário ' . $usuario->name . ' Criado com sucesso');
            return redirect()->route('admin.vendedores.index', auth()->user()->id);
        } else {
            $usuario->delete();
            $response->success = false;
        }
        return $response->success;
    }

    private function requisicaoUpdateVendaExterna($funcionario, $vendedorUsuario, $request)
    {
        $token = ['api-key-alltech' => config('app.api_key_alltech')];
        //mandar os parametros de acordo com nome no model user
        $response =  Http::withHeaders($token)->put('http://localhost:8001/api/update/user/venda-externa/' . $vendedorUsuario->id, [
            'name' =>    $request->name,
            'email' =>   $request->email,
            'password' =>   $request->password ? bcrypt($request->password) : $vendedorUsuario->password,
            'loja' => $request->lojaPadrao,
            'perfil' => $request->tipo_admin,
        ]);

        $response = $response->object();
        
        if ($response->success == true) {

            $vendedorUsuario->update([
                'name' =>    $request->name,
                'email' =>   $request->email,
                'password' =>   $request->password ? bcrypt($request->password) : $vendedorUsuario->password,
                'loja_id' => $request->lojaPadrao,
                'perfil' => $request->tipo_admin,
            ]);

            $funcionario->update(['user_id' => $vendedorUsuario->id, 'status' => 'ativo']);

            $this->setLojas($vendedorUsuario, $request->lojaPadrao);

            return $response = ['success' => true, 'funcionario' => $funcionario->nome];
        } else {
            return $response = ['success' => false];
        }
    }
}
