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


Route::middleware('auth:api')->get('/directory/path/{folder}', 'DirectoryController@getPath');
Route::middleware('auth:api')->get('/directory/check', 'DirectoryController@checkPath');
Route::middleware('auth:api')->get('/database/check', 'DirectoryController@checkDatabase');
Route::middleware('auth:api')->get('/project/{project}/ping', 'ProjectController@apiGetPing');
