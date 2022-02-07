<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => [ 'required' ,'email' ],
            'password' => [ 'required' ],
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Authentication passed...
            $user = auth()->user();
            $token = auth()->user()->auth_user_get_token();
            return response()->json([
                'user' => $user,
                'token'=>$token,
                'message' => 'Login successful'
            ], 200);
        }        

        return response()->json([
            'errors' => [
                'failed' => ['Authentication Failed']
            ]
        ],422);
    }
}
