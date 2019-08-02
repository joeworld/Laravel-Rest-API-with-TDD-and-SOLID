<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\RegisterRequest;

class AuthController extends Controller
{

	public function authenticate(LoginRequest $request){

        //Attempt validation
        $credentials = $request->only(['email','password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Incorrect credentials'], 401);
        }

        return response()->json(compact('token'));

    }

    public function register(RegisterRequest $request){

        //Create user, generate token and return
        $user =  User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);

        $token = JWTAuth::fromUser($user);
        return response()->json(compact('token'));

    }


}