<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    protected function respondWithToken($token, $role){
        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'role' => $role,
            'expires_in' => Auth::factory()->getTTL(),
        ], 200);
    }
}
