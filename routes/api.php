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

//users routes

Route::resource('/users', 'Api\UsersController')->except(['edit', 'create']);
Route::post('/users/{user}/old_password', 'Api\UsersController@oldPassword');
Route::put('/users/{user}/reset_password', 'Api\UsersController@resetPassword');
Route::put('/users/{user}/admin_update', 'Api\UsersController@adminUpdate');



//posts routes
Route::resource('/posts', 'Api\PostsController')->except(['edit', 'create'])->middleware('auth:api');
Route::put('/posts/{post}/update_privacy', 'Api\PostsController@updatePrivacy')->middleware('auth:api');
Route::get('/users/{user}/posts', 'Api\PostsController@userPosts')->middleware('auth:api');

//friend requests routes
Route::post('/friendship/send', 'Api\FriendRequestsController@send')->middleware('auth:api');
Route::delete('/friendship/cancel', 'Api\FriendRequestsController@cancel')->middleware('auth:api');
Route::delete('/friendship/reject', 'Api\FriendRequestsController@reject')->middleware('auth:api');
Route::put('/friendship/accept', 'Api\FriendRequestsController@accept')->middleware('auth:api');
Route::post('/friendship/block', 'Api\FriendRequestsController@block')->middleware('auth:api');
Route::delete('/friendship/remove_block', 'Api\FriendRequestsController@removeBlock')->middleware('auth:api');
Route::delete('/friendship/unfriend', 'Api\FriendRequestsController@unfriend')->middleware('auth:api');
Route::get('/friendship/friends_list', 'Api\FriendRequestsController@friendsList')->middleware('auth:api');

//post privacy routes
Route::put('/set_default_viewers', 'Api\DefaultPostViewersController@setViewers')->middleware('auth:api');
Route::get('/get_default_viewers', 'Api\DefaultPostViewersController@getViewers')->middleware('auth:api');

//likes routes
Route::post('/post/add_like', 'Api\LikesController@addLikeToPost')->middleware('auth:api');
Route::delete('/post/remove_like', 'Api\LikesController@removeLikeFromPost')->middleware('auth:api');
//comment routes
Route::resource('/comments', 'Api\CommentsController')->except(['edit', 'create', 'store', 'index'])->middleware('auth:api');
Route::post('/posts/{post}/comment', 'Api\CommentsController@comment')->middleware('auth:api');
Route::post('/photos/{photo}/comment', 'Api\CommentsController@comment')->middleware('auth:api');
Route::get('/posts/{post}/comments', 'Api\CommentsController@postComments')->middleware('auth:api');
//description routes
Route::resource('/descriptions', 'Api\DescriptionsController')->only(['store', 'show', 'update', 'destroy'])->middleware('auth:api');
//messages routes
Route::post('/messages/send', 'Api\MessagesController@send')->middleware('auth:api');
Route::put('/messages/receive', 'Api\MessagesController@receive')->middleware('auth:api');
Route::post('/messages/receive_new', 'Api\MessagesController@receiveNew')->middleware('auth:api');
//replies routes
Route::resource('/replies', 'Api\RepliesController')->except(['edit', 'create', 'index'])->middleware('auth:api');
