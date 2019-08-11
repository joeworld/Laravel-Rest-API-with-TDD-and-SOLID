<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CreateTodoRequest;
use App\Http\Requests\Api\UpdateTodoRequest;
use Illuminate\Support\Facades\Auth;
use App\Todo;
use App\Factories\TodoFactory;

class TodoController extends Controller
{

    private $todo;

    public function  __construct(TodoFactory $todo)
    {
        $this->todo = $todo::createApi();
    }

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
        return $this->todo->create($request);
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
        return $this->todo->get('id', $todo->id);
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
        $this->todo->update($request, $todo);
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
        $this->todo->delete($todo);
    }
}