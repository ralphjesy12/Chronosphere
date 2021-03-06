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
Route::redirect('/home','/');

Auth::routes();

if (!env('ALLOW_REGISTRATION', false)) {
    Route::redirect('/register', '/', 301);
}

Route::resource('projects', 'ProjectController');

Route::patch('project/{project}/backup','ProjectController@backup')->name('project.backup');

Route::get('/', 'HomeController@index')->name('home');
