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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/redirect', 'Auth\LoginController@redirectToProvider');
Route::get('/callback', 'Auth\LoginController@handleProviderCallback');

Route::get('/folders', 'FoldersController@index');
Route::get('/folders/create', 'FoldersController@create');
Route::post('/folders', 'FoldersController@store');
Route::get('/folders/{folder}', 'FoldersController@show');
Route::get('/folders/{folder}/edit', 'FoldersController@edit');
Route::get('/folders/{folder}/download', 'FoldersController@download');
Route::patch('/folders/{folder}', 'FoldersController@update');
Route::delete('/folders/{folder}', 'FoldersController@destroy');

Route::post('/files/{folder}', 'FilesController@store');
Route::delete('/files/{file}', 'FilesController@destroy');
Route::get('/files/{file}', 'FilesController@download');
