<?php

namespace App\Repositories\Api;

use App\Todo;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Contracts\RepositoryInterface;
use App\Http\Requests\Api\CreateTodoRequest;
use App\Http\Requests\Api\UpdateTodoRequest;
use Illuminate\Support\Facades\Auth;

class TodoApiRepository implements RepositoryInterface
{

	public function get($type, $value)
	{
		return Todo::where($type, $value)->first()->toJson();
	}

	public function getAll($order = null, $limit = null)
	{

		if($order === null && $limit === null):
			return Todo::all()
			->toJson();
		elseif($order !== null && $limit === null):
			return Todo::all()
			->orderBy('id', $order)
			->get()
			->toJson();
		elseif($order === null && $limit !== null):
			return Todo::all()
			->take($limit)
			->get()->toJson();
		else:
			return Todo::all()
			->orderBy('id', $order)
			->take($limit)
			->get()
			->toJson();
		endif;

	}

	public function create(CreateTodoRequest $request)
	{
        //Create Todo and attach to user
        $user = Auth::user();
        $todo = Todo::create($request->only(['title', 'summary', 'content']));
        $user->todos()->save($todo);
        //Return json of todo
        return $todo->toJson();	
	}

	public function update(UpdateTodoRequest $request, Todo $todo)
	{
		//Update
        $todo->update($request->only('title','summary','content'));
        return $todo->toJson();
	}

	public function delete(Todo $todo)
	{
		$todo->delete();
	}

}