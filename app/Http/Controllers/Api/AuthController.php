<?php

namespace App\Http\Controllers\Api;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\Http\Controllers\Controller;
use App\Factories\UserFactory;

class AuthController extends Controller
{

    private $user;

    public function  __construct(UserFactory $user)
    {
        $this->user = $user::createApi();
    }

	public function authenticate(LoginRequest $request){
        return $this->user->login($request);
    }

    public function register(RegisterRequest $request){
        return $this->user->create($request);
    }


}