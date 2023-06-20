<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;


class AuthController extends Controller
{
    public function create(){
        
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:App\Models\User',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'name' => request('name'),
            'email' => request('email'),
            'password' => Hash::make(request('password')),
            'role_id' => 1,
        ]);

        

        $token=$user->createToken('myAppToken');
        return response()->json([
            'user' => $user,
            'token' => $token
        ]);
        // return response()->route('login');
    }

    public function login(){
        
    }

    public function customLogin(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $credentials = [
            'email'=>request('email'),
            'password'=>request('password'),
        ];
        if(Auth::attempt($credentials)){
            
            return response(Auth::user()->createToken('myAppToken'));
        }
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request){
        Auth::user()->tokens()->delete();
        return response('yes');
    }
}
