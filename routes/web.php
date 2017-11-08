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

######################################################
# ログインユーザーのみアクセス可能
######################################################
Route::group(['middleware' => ['auth']], function () {
    Route::resource('/channels', 'ChannelsController');
    Route::get('/logout', 'Auth\LoginController@logout');
});

######################################################
# 未ログインでもアクセス可能
######################################################
Route::get('/channels/{channel}', 'ChannelsController@show');
Route::get('/errors/{page}', 'ErrorsController@index');
