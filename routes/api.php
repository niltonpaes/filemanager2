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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/folders', 'FoldersControllerApi@index');
Route::post('/folders/update', 'FoldersControllerApi@update');
Route::post('/folders/store', 'FoldersControllerApi@store');
Route::post('/folders/destroy', 'FoldersControllerApi@destroy');

Route::post('/files', 'FilesControllerApi@index');
Route::post('/files/store', 'FilesControllerApi@store');
Route::post('/files/destroy', 'FilesControllerApi@destroy');
