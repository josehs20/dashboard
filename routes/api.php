<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::post('/auth/login', [\App\Http\Controllers\Api\AuthController::class, 'login']);
Route::post('/admim_venda_authenticate/{token}', [\App\Http\Controllers\Api\AuthController::class, 'admim_venda_authenticate']);

Route::prefix('v1')->middleware('apiJwtVendedor')->group( function () {

    Route::post('auth/logout', [\App\Http\Controllers\Api\AuthController::class, 'logout']);
    Route::post('auth/refresh', [\App\Http\Controllers\Api\AuthController::class, 'refresh']);
  
    Route::get('/estoques/produtos/', [\App\Http\Controllers\Api\ProdutosController::class, 'index']);
   
    Route::get('/clientes/', [\App\Http\Controllers\Api\ClientesController::class, 'index']);
    Route::get('/cidades_ibge/', [\App\Http\Controllers\Api\ClientesController::class, 'get_cidades_ibge']);
    Route::post('/create-clientes/', [\App\Http\Controllers\Api\ClientesController::class, 'create_cliente']);
    Route::put('/update-clientes/{id}', [\App\Http\Controllers\Api\ClientesController::class, 'update_cliente']);

    // http://localhost:8000/api/v1/estoque/produtos/ids?estoque_ids=1,2,3,45&atr_produto=nome,preco'
});
