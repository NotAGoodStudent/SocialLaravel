<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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


Route::middleware('auth')->group(function(){
    Route::get('/user/updateProfile','UserController@returnModifyProfileView')->name('modifyProfile');
    Route::patch('/user/updateData/{id}','UserController@updateUserData')->name('updateUserData');
    Route::post('/home/post/{id}', 'PostController@makePost')->name('makePost');
    Route::get('/user/profile/{id}', "UserController@returnProfile")->name('profile');
    Route::get('/user/follow/{id}/{id2}', 'UserController@followUser')->name('follow');
});
