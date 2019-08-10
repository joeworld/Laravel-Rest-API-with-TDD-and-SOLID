<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthTest extends TestCase
{

    use RefreshDatabase;

    /**
     * Test User Registration
     * @test
     */

    public function testRegister()
    {
        //User's Data
        $data = [
            'email' => 'test@gmail.com',
            'name'  => 'Test User',
            'password' => 'secret123$',
            'password_confirmation' => 'secret123$'
        ];

        //Send the post request
        $response = $this->json('POST', route('api.register'), $data);
        //Assert that it was successful
        $response->assertStatus(200);
        //Assert that a token was received
        $this->assertArrayHasKey('token', $response->json());
    }

    /**
     * Test User Login
     *@test
     */

    public function testLogin()
    {
        
        //Create user
        User::create([
            'name' => 'test',
            'email'=>'test@gmail.com',
            'password' => bcrypt('secret123$')
        ]);

        $data = [
            'email' => 'test@gmail.com',
            'password' => 'secret123$',
        ];

        //attempt login
        $response = $this->json('POST', route('api.authenticate'), $data);
        //Assert it was successful and a token was received
        $response->assertStatus(200);
        $this->assertArrayHasKey('token',$response->json());
    }

}