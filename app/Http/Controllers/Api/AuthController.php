<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Mail\UserInvitation;
use App\Models\User;
use App\Models\Invitation;

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

    public function registerViaEmail(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string','max:50'],
            'user_name' => ['required', 'string', 'max:20', 'unique:users', 'alpha_dash'],
            'email' => ['required','email','unique:users','max:255'],
            'password' => ['required']
        ]);

        $invitationToken = $request->route('token') ?? '';

        $invitation = Invitation::where(['invitation_token' => $invitationToken])->first();

        if( !$invitation )
        {
            return response()->json([
                'errors' => [
                    'failed' => ['Link Expired.']
                ]
            ],422);
        }

        if( $invitation->email != $request->email )
        {
            return response()->json([
                'errors' => [
                    'email' => ['Invitation email mismatch']
                ]
            ],422);
        }

        $user = User::create($request->all());

        Auth::login($user);
        $token = $user->auth_user_get_token();

        return response()->json([
            'user' => $user,
            'token'=>$token,
            'message' => 'Registration successful'
        ], 200);
        

    }
}
