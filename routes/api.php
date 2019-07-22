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

// Players
Route::get('players', "PlayerController@index");
Route::get('players/{id}', "PlayerController@show");
Route::post('players', "PlayerController@store");
Route::put('players/{id}', "PlayerController@update");
Route::delete('players/{id}', "PlayerController@destroy");

// Games
Route::get('games', "GamesController@index");
Route::get('games/{id}', "GamesController@show");
Route::post('games', "GamesController@store");
Route::put('games/{id}', "GamesController@update");
Route::delete('games/{id}', "GamesController@destroy");
// Rounds
Route::get('rounds', "RoundController@index");
Route::get('rounds/{id}', "RoundController@show");
Route::post('rounds', "RoundController@store");
Route::put('rounds/{id}', "RoundController@update");
Route::delete('rounds/{id}', "RoundController@destroy");

// Moves
Route::get('moves', "MovesController@index");
Route::get('moves/{id}', "MovesController@show");
Route::post('moves', "MovesController@store");
Route::put('moves/{id}', "MovesController@update");
Route::delete('moves/{id}', "MovesController@destroy");
