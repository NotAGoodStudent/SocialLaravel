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
    Route::get('/users/getUsers', 'UserController@getUsers')->name('getUsers');
    Route::get('/user/updateProfile','UserController@returnModifyProfileView')->name('modifyProfile');
    Route::patch('/user/updateData/{id}','UserController@updateUserData')->name('updateUserData');
    Route::post('/home/post/{answer_id}/{comesFromReplyTab}', 'PostController@makePost')->name('makePost');
    Route::get('/user/profile/{username}', "UserController@returnProfile")->name('profile');
    Route::get('/user/follow/{id2}', 'UserController@followUser')->name('follow');
    Route::get('/user/unfollow/{id2}', 'UserController@unfollowUser')->name('unfollow');
    Route::get('/post/like/{post_id}', 'LikeController@likePost')->name('like');
    Route::get('/post/dislike/{post_id}', 'LikeController@dislikePost')->name('dislike');
    Route::get('/post/retweet/{post_id}', 'RetweetController@retweetPost')->name('retweet');
    Route::get('/post/unretweet/{post_id}', 'RetweetController@unretweetPost')->name('unretweet');
    Route::get('/topics/getTopics', 'TopicController@getTopics')->name('getTopics');
    Route::get('/post/getPost/{post_id}', 'PostController@getPost')->name('getPost');
    Route::get("/post/showReplies/{post_id}", "PostController@showPostReplies")->name("showReplies");
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
