<?php

namespace Tests\Feature;

use App\Todo;
use Tests\TestCase;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tymon\JWTAuth\Facades\JWTAuth;

class TodoTest extends TestCase
{

    use RefreshDatabase;

    protected $user;

    protected $title   = "Lorem ipsum dolor sit amet";

    protected $summary = "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.";

    protected $content = "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.";

    /**
     * A protected method to create a user
     *
     * @return token
     */

    protected function authenticate()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@gmail.com',
            'password' => Hash::make('secret123$')
        ]);
        $this->user = $user;
        $token = JWTAuth::fromUser($user);
        return $token;
    }


    /**
     * A protected method to create a todo
     *
     * @return response
     */

    protected function createTodo()
    {
        $todo = Todo::create([
            'title' =>   $this->title,
            'summary' => $this->summary,
            'content' => $this->content
        ]);
        $this->user->todos()->save($todo);
        return $todo;

    }

    public function testStore()
    {
        //Get token
        $token = $this->authenticate();
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])->json('POST', route('todo.store'),[
            'title' => $this->title,
            'summary' => $this->summary,
            'content' => $this->content
        ]);

        $response->assertStatus(200);

        //Get count and assert
        $count = $this->user->todos()->count();
        $this->assertEquals(1, $count);
    }

    public function testAll()
    {
        //Authenticate and attach todo to user
        $token = $this->authenticate();
        $this->createTodo();
        //call route and assert response
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])->json('GET', route('todo.index'));
        $response->assertStatus(200);
        //Assert the count is 1 and the title of the first correlates
        $this->assertEquals(1, count($response->json()));
        $this->assertEquals($this->title, $response->json()[0]['title']);
    }

    public function testUpdate()
    {
        $token = $this->authenticate();
        $todo = $this->createTodo();
        //call route and assert response
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token
        ])->json('PUT', route('todo.update', ['todo' => $todo->id]), [
            'title' => 'This is an Updated title'
        ]);
        $response->assertStatus(200);
        //Assert title is the new title
        $this->assertEquals('This is an Updated title', $this->user->todos()->first()->title);
    }

    public function testShow()
    {
        $token = $this->authenticate();
        $todo = $this->createTodo();
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token
        ])->json('GET', route('todo.show', [
            'todo' => $todo->id
        ])
        );
        $response->assertStatus(200);
        //Assert title is correct
        $this->assertEquals($this->title, $response->json()['title']);
    }

    public function testDelete()
    {
        $token = $this->authenticate();
        $todo = $this->createTodo();
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token
        ])->json('PUT', route('todo.destroy', [
            'todo' => $todo->id
        ])
        );
        $response->assertStatus(200);
        //Assert there is no todo
        $this->assertEquals(0, $this->user->todos()->count());
    }

}