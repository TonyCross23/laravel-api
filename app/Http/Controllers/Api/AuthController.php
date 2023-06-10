<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //register 
    public function register (Request $request){
        $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|email|unique:users,email,',
            'password' => 'required|min:8|max:25'
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email  = $request->email ;
        $user->password = Hash::make($request->password);
        $user->save();

        $token = $user->createToken('blog')->accessToken;
        return ResponseHelper::success([
            'accept_token' => $token
        ]);

    }

    // login
    public function login (Request $request) {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $user = auth()->user();

            $token = $user->createToken('Token')->accessToken;

            return ResponseHelper::success([
                'accept_token' => $token,
            ]);
            
        }
    }

    // logout
    public function logout (Request $request) {

        auth()->user()->token()->revoke();

        return ResponseHelper::success([],'Successfully logout .');
    }
}