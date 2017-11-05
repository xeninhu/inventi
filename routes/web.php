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

Route::get('/', 'HomeController@index')
    ->name('home')
    ->middleware('auth');
/**
    Rotas para login, troca de senha e criação de usuários aproveitando o register do auth
*/
Auth::routes();

Route::group(['middleware' => ['auth','admin']], function() {
    Route::get('/register', 'Auth\RegisterController@create')->name('register');//Sobrescrevendo o register do auth, utilizando para cadastro de colaborador.
    Route::post('/register', 'Auth\RegisterController@store'); //Sobrescrevendo o register do auth, utilizando para cadastro de colaborador.
    Route::get('/users/{user}/edit','Auth\UserController@edit')->name('pagedituser');
    Route::put('/users','Auth\UserController@update')->name('edituser');
    Route::get('/users', 'Auth\UserController@index')->name('indexuser');
    Route::delete('/users/{id}','Auth\UserController@destroy')->name('deleteuser');
    Route::get('users/search/{name?}','Auth\UserController@search');
    
    Route::get('itens/move','ItensController@moveItensToUserPage')->name('itens.movepage');
    Route::put('itens/move','ItensController@moveItensToUser')->name('itens.move');
    Route::get('itens/search/{patrimony_number}','ItensController@search');

    Route::resource('itens', 'ItensController');

    Route::get('/item_types/{type}','ItemTypesController@search');
    
});


