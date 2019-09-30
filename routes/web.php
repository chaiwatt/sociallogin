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


Route::group(['prefix' => 'provider'], function(){       
    Route::get('/{provider}', 'Auth\LoginController@Redirect')->name('social.provider.login');
    Route::get('callback/{provider}', 'Auth\LoginController@Callback')->name('social.provider.callback');
});




