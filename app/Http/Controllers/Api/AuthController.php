<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Mail\UserInvitation;
use App\Models\User;
use App\Models\Invitation;

use App\Mail\UserPinActivation;
use Mail;

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

        $pin = rand(strlen($user->email),10000);

        $pin = ($pin.$user->id); 

        $user->email_verified_pin = $pin;
        $user->save();

        Mail::to($user->email)->send(new UserPinActivation($pin));
        
        return response()->json([
            'message' => 'Registration successful. Please activate your account using pin received in email.'
        ], 200);
    }

    public function activateAccount(Request $request)
    {   
        $pin = $request->pin ?? '';

        $user = User::where(['email_verified_pin' => $pin ])->first();

        if( !$user )
        {
            return response()->json([
                'errors' => [
                    'failed' => ['Pin is invalid']
                ]
            ],422);
        }

        $user->email_verified_at = date('Y-m-d H:i:s');
        $user->save();

        Auth::login($user);
        $token = auth()->user()->auth_user_get_token();

        return response()->json([
            'user' => $user,
            'token'=>$token,
            'message' => 'Account is activated successfuly'
        ], 200);

    }
}
