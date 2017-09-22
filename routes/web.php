<?php

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


/*Route::get('/', function () {
    return view('welcome');
});*/
Route::get('/', 'HomeController@index')->name('home');

/**
    Rotas para login, troca de senha e criação de usuários aproveitando o register do auth
*/
Auth::routes();
Route::group(['middleware' => 'auth'], function() {
    Route::get('/register', 'Auth\RegisterController@create')->name('register');//Sobrescrevendo o register do auth, utilizando para cadastro de colaborador.
    Route::post('/register', 'Auth\RegisterController@store'); //Sobrescrevendo o register do auth, utilizando para cadastro de colaborador.
    Route::get('/users/{user}/edit','Auth\UserController@edit')->name('pagedituser');
    Route::put('/users','Auth\UserController@update')->name('edituser');
    Route::get('/users', 'Auth\UserController@index');
});


