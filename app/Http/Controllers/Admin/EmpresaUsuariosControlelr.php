<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class EmpresaUsuariosControlelr extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($empresa)
    {
        $empresa = Empresa::find($empresa);
        return view('admin.empresa-usuarios.create', compact('empresa'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $empresa)
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

        if ($request->lojas) {
            if (in_array($request->lojaPadrao, $request->lojas)) {
                $this->setLojas($usuario, $request->lojas);
            } else {
                $lojas = $request->lojas;
                //dd($lojas);
                array_push($lojas, $request->lojaPadrao);
                $this->setLojas($usuario, $lojas);
            }
        } else {
            $this->setLojas($usuario, [$request->lojaPadrao]);
        }
        $empresa = Empresa::find($empresa);
        Session::flash('success', 'Usuário ' . $usuario->name . ' Criado com sucesso');
        return redirect()->route('empresas.show', $empresa);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($empresa, $usuario)
    {
        $empresa = Empresa::find($empresa);
        $usuario = User::find($usuario);
        return view('admin.empresa-usuarios.edit', compact('empresa', 'usuario'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $empresa, $usuario)
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
            'email' => ['unique:users,email,' . $usuario],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
        ], $mensagens);

        $usuario = User::find($usuario);
        
        $usuario->update([
            'name' =>    $request->name,
            'email' =>   $request->email,
            'password' =>   $request->password ? bcrypt($request->password) : $usuario->password,
            'loja_id' => $request->lojaPadrao,
            'perfil' => $request->tipo_admin,
        ]);

        if ($request->lojas) {
            if (in_array($request->lojaPadrao, $request->lojas)) {
                $this->setLojas($usuario, $request->lojas);
            } else {
                $lojas = $request->lojas;
                //dd($lojas);
                array_push($lojas, $request->lojaPadrao);
                $this->setLojas($usuario, $lojas);
            }
        } else {
            $this->setLojas($usuario, [$request->lojaPadrao]);
        }
        $empresa = Empresa::find($empresa);
        Session::flash('success', 'Usuário ' . $usuario->name . ' Atualizado com sucesso');
        return redirect()->route('empresas.show', $empresa);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($empresa, $usuario)
    {
        $usuario = User::find($usuario);
        $usuario->delete();

        Session::flash('success', 'Usuário ' . $usuario->name . ' excluído com sucesso');
        return redirect()->route('empresas.show', $empresa);
    }

    private function setLojas($user, $lojas)
    {
        $user->lojas()->detach();
        foreach ($lojas as $loja) {
            $user->lojas()->attach($loja);
        }
    }
}
