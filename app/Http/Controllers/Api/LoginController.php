<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Contracts\Providers\JWT;

class LoginController extends Controller
{
    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'username'=> 'required',
            'password' => 'required'
        ]);
        if($validator->fails()){
            return response()->json($validator->errors(),422);
        }
        $credentials = $request->only('username','password');
        if(!$token = JWTAuth::attempt($credentials)){
            return response()->json([
                'success' => false,
                'message' => 'Username atau Password salah'
            ], 200);
        }
        return response()->json([
            'success' => true,
            'datauser' => auth()->user(),
            'token' => $token,
        ], 200);
    }
}
