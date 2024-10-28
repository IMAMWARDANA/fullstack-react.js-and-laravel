<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Contracts\Providers\JWT;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
class LogoutController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
{
    try {
        // Mengambil token dari permintaan
        $token = JWTAuth::getToken();

        // Menginvalidasi token
        if ($token) {
            JWTAuth::invalidate($token);
            return response()->json([
                'success' => true,
                'message' => 'Logout Berhasil',
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Token tidak ditemukan',
            ], 400);
        }
    } catch (JWTException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Logout gagal, coba lagi.',
        ], 500);
    }
}

}
