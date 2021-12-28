<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

$router->group(['middleware' => 'auth'], function () use ($router) {
    Route::post('register', 'App\Http\Controllers\AuthController@register');
    Route::post('logout', 'App\Http\Controllers\AuthController@logout');
    Route::post('refresh', 'App\Http\Controllers\AuthController@refresh');
    Route::get('user-profile', 'App\Http\Controllers\AuthController@userProfile');
    Route::get('members', 'App\Http\Controllers\MemberController@index');
    Route::get('me', 'App\Http\Controllers\MemberController@me');
    Route::put('update/{id}', 'App\Http\Controllers\MemberController@update');
    Route::delete('members/{id}', 'App\Http\Controllers\MemberController@delete');
});

Route::post('login', 'App\Http\Controllers\AuthController@login');
Route::post('members',  'App\Http\Controllers\MemberController@store');