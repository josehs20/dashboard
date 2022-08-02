<?php

namespace App\Http\Controllers\Profiles;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ConfiguracoesController extends Controller
{

    public function profile_admin()
    {
        $usuario = auth()->user();
        return view('admin.profile.edit', compact('usuario'));
    }
    public function profile_adminVenda()
    {
        $usuario = auth()->user();
        return view('admin-vendas.profile.edit', compact('usuario'));
    }

    public function update_profile(Request $request)
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
            'email' => ['unique:users,email,' . auth()->user()->id],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
        ], $mensagens);

        //vendedor esta recebendo id do usuario de já
        $usuario = User::find(auth()->user()->id);

        $usuario->update([
            'name' =>    $request->name,
            'email' =>   $request->email,
            'password' =>   $request->password ? bcrypt($request->password) : $usuario->password,
        ]);

        Session::flash('success', 'Usuário ' . $usuario->name . ' Atualizado com sucesso');
        if (auth()->user()->administrador()) {

           return redirect()->route('profile_admin');
        }else {
            
            return redirect()->route('profile_adminVenda');
        }
    }

    // private function updateProfile()
    // {
    //     # code...
    // }
    // public function editAdmin(Type $var = null)
    // {
        
    // }

    // public function setPerfil(Type $var = null)
    // {
        
    // }
}
