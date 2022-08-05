<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only(['email', 'password']);        

        $userVendedor = User::where('email', $credentials['email'])->first();

        if ($userVendedor->perfil != 'vendedor' || !$userVendedor->funcionario || $userVendedor->funcionario->status == 'inativo' 
        || $userVendedor->email !== $credentials['email']) {
            return response()->json(['error' => 'Permissão negada', 'msg' => 'Usuário não é um vendedor registrado, ou não esta ativado para venda externa'], 401);
        }

        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
      
        return response()->json(['usuario' => $userVendedor->id, 'token' => $this->respondWithToken($token)]);
    }

    /**
     * Log the user out (Invalidate the token)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth('api')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

     /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
       $token = $this->respondWithToken(auth('api')->refresh());
        return response()->json(['token' => $token], 200);
    }


    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }


    public function get_user_venda_externa()
    {
        $user = auth('api')->user();
        
        return response()->json(['user' => $user, 'p' => $user->password], 200);
    }
}
