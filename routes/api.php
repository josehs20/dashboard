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
Route::post('auth/login', [\App\Http\Controllers\Api\AuthController::class, 'login']);
Route::post('/admim_venda_authenticate/{token}', [\App\Http\Controllers\Api\AuthController::class, 'admim_venda_authenticate']);

Route::prefix('v1')->middleware('apiJwtVendedor')->group( function () {

    Route::post('auth/logout', [\App\Http\Controllers\Api\AuthController::class, 'logout']);
    Route::post('auth/refresh', [\App\Http\Controllers\Api\AuthController::class, 'refresh']);
    Route::get('/auth/usuario/venda-externa/', [\App\Http\Controllers\Api\AuthController::class, 'get_user_venda_externa']);
    Route::get('/produtos/estoques/', [\App\Http\Controllers\Api\ProdutosController::class, 'index']);

});
