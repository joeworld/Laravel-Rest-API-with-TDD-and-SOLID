<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthTest extends TestCase
{

    /**
     * Test User Registration
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
        //Delete data
        User::where('email', 'test@gmail.com')->delete();

    }

    /**
     * Test User Login
     */

    public function testLogin()
    {
        
        //Create user
        User::create([
            'name' => 'test',
            'email'=>'test@gmail.com',
            'password' => bcrypt('secret1234')
        ]);

        $data = [
            'email' => 'test@gmail.com',
            'password' => 'secret1234',
        ]

        //attempt login
        $response = $this->json('POST',route('api.authenticate'), $data);
        //Assert it was successful and a token was received
        $response->assertStatus(200);
        $this->assertArrayHasKey('token',$response->json());
        //Delete the user
        User::where('email','test@gmail.com')->delete();

    }

}