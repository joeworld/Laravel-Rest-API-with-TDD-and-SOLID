<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

//Authentication Routes

Route::group([
	'middleware' => 'api',
	'prefix' => 'auth',
	'namespace' => 'Api'
], function () {
    Route::post('authenticate', 'AuthController@authenticate')->name('api.authenticate');
    Route::post('register', 'AuthController@register')->name('api.register');
});

//Todo Routes

Route::group([
	'namespace' => 'Api',
	'middleware' => 'api,auth'
], function(){

Route::resource('todo', 'TodoController');

});