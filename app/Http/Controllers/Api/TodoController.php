<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CreateTodoRequest;
use App\Http\Requests\Api\UpdateTodoRequest;
use Illuminate\Support\Facades\Auth;
use App\Todo;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Auth::user()->todos;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateTodoRequest $request)
    {
        //Create Todo and attach to user
        $user = Auth::user();
        $todo = Todo::create($request->only(['title', 'summary', 'content']));
        $user->todos()->save($todo);
        //Return json of todo
        return $todo->toJson();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Todo $todo)
    {
        //Check is user is the owner of the todo
        if($todo->author_id != Auth::id()){
            abort(404);
            return;
        }
        return $todo->toJson();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTodoRequest $request, Todo $todo)
    {
        //Check is user is the owner of the todo
        if($todo->author_id != Auth::id())
        {
            abort(404);
            return;
        }
        //Update
        $todo->update($request->only('title','summary','content'));
        return $todo->toJson();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Todo $todo)
    {
        //Check is user is the owner of the todo
        if($todo->author_id != Auth::id()){
            abort(404);
            return;
        }
        $todo->delete();
    }
}