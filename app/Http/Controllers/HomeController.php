<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (auth()->user() && auth()->user()->administrador()) {
            Session::flash('success', 'Bem vindo administrador');
            return redirect()->route('empresas.index');
        }
        if (auth()->user() && auth()->user()->adminVenda() || auth()->user()->perfil == 'consulta') {
            Session::flash('success', 'Bem Vindo '.auth()->user()->name);
            return redirect()->route('dashboard.index');
        }else {
            Auth::logout();
            Session::flash('error', 'Permissão negada');
            return redirect()->route('login');
        }
     
    }
}
