<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Auth::routes();

Route::get('/register', ['as' => 'register', 'uses' => 'AuthenticationController@register']);
Route::post('/register', ['as' => 'register.post', 'uses' => 'AuthenticationController@doRegister']);
Route::get('/login', ['as' => 'login', 'uses' => 'AuthenticationController@login']);
Route::post('/login', ['as' => 'login.post', 'uses' => 'AuthenticationController@doLogin']);
Route::post('/logout', ['as' => 'logout', 'uses' => 'AuthenticationController@doLogout']);


Route::get('/home', 'HomeController@index')->name('home');
