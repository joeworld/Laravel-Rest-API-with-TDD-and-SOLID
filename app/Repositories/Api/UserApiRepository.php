<?php

namespace App\Repositories\Api;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Contracts\RepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\RegisterRequest;

class UserApiRepository implements RepositoryInterface
{

	public function get($type, $value)
	{
		return User::where($type, $value)->first()->toJson();
	}

	public function getAll($order = null, $limit = null)
	{

		if($order === null && $limit === null):
			return User::all()
			->toJson();
		elseif($order !== null && $limit === null):
			return User::all()
			->orderBy('id', $order)
			->get()
			->toJson();
		elseif($order === null && $limit !== null):
			return User::all()
			->take($limit)
			->get()->toJson();
		else:
			return User::all()
			->orderBy('id', $order)
			->take($limit)
			->get()
			->toJson();
		endif;

	}

	public function login(LoginRequest $request)
	{
        //Attempt validation
        $credentials = $request->only(['email','password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Incorrect credentials'], 401);
        }

        return response()->json(compact('token'));
	}

	public function create(RegisterRequest $request)
	{
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