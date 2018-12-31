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

// List articles
Route::get('articles', 'ArticleController@index');

// Show article
Route::get('articles/{id}', 'ArticleController@show');

// Create new article 
Route::post('articles', 'ArticleController@store');

// Update article
Route::put('articles/{id}', 'ArticleController@update');

// Destroy article
Route::delete('articles/{id}', 'ArticleController@destroy');
