<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::resource('/dashboard', App\Http\Controllers\Dashboard\HomeController::class);
Route::get('/grafico-pie', [App\Http\Controllers\Dashboard\HomeController::class, 'grafico_pie']);
Route::get('/grafico-area', [App\Http\Controllers\Dashboard\HomeController::class, 'grafico_area']);

Route::resource('/caixa', App\Http\Controllers\Dashboard\CaixaController::class);
Route::resource('/vendas', App\Http\Controllers\Dashboard\VendasController::class);
Route::get('/produtos-mais-vendidos', [App\Http\Controllers\Dashboard\VendasController::class, 'produtos_mais_vendidos'])->name('produtos_mais_vendidos');
Route::resource('/produtos', App\Http\Controllers\Dashboard\ProdutosController::class);
Route::resource('/receitas', App\Http\Controllers\Dashboard\ReceitasController::class);
Route::resource('/despesas', App\Http\Controllers\Dashboard\DespesasController::class);

Route::middleware('adminVenda')->group(function () {
    Route::resource('/admin.vendedores', App\Http\Controllers\AdminVenda\VendedoresController::class);
    Route::post('/admin/vendedores/ativar/{funcionario}', [\App\Http\Controllers\AdminVenda\VendedoresController::class, 'status_vendedor'])->name('status_vendedor');
});

Route::middleware('administrador')->group(function () {
    Route::resource('/admin/empresas', App\Http\Controllers\Admin\EmpresasController::class);
    Route::resource('/empresa.usuarios', App\Http\Controllers\Admin\EmpresaUsuariosControlelr::class);
    Route::get('/admin/prodile/', [\App\Http\Controllers\Profiles\ConfiguracoesController::class, 'profile_admin'])->name('profile_admin');
});
Route::get('/admin/venda/profile', [\App\Http\Controllers\Profiles\ConfiguracoesController::class, 'profile_adminVenda'])->name('profile_adminVenda');
Route::put('/admin/profile/update', [\App\Http\Controllers\Profiles\ConfiguracoesController::class, 'update_profile'])->name('update_profile');

