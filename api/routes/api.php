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

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');


Route::get('minesweepers/create', ['uses' => 'MinesweepersController@create', 'as' => 'api.minesweepers.create']);


Route::group(['middleware' => ['verify-tokenGame'], 'prefix' => 'game'], function () {
	Route::get('check', function(){ return ['result' => true] ; });

	Route::post('click_coordinate', ['uses' => 'GameController@click_coordinate', 'as' => 'api.game.click_coordinate']);

});