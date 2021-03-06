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

Route::get('/auth', 'Api\UsersController@authId')->middleware('auth:api');
//users routes
Route::resource('/users', 'Api\UsersController')->except(['edit', 'create']);
Route::get('users/{user}/default_privacy', 'Api\UsersController@defaultPrivacy');
Route::get('/users/{user}/profile_photo', 'Api\UsersController@getProfilePhoto');
Route::put('/users/{user}/update_profile_photo', 'Api\UsersController@updateProfilePhoto');

Route::post('/login', 'AuthController@login')->middleware('guest:api');
Route::get('/logout', 'AuthController@logout')->middleware('auth:api');
Route::post('/register', 'AuthController@register')->middleware('guest:api');

Route::post('/users/{user}/old_password', 'Api\UsersController@oldPassword');
Route::put('/users/{user}/reset_password', 'Api\UsersController@resetPassword');
Route::put('/users/{user}/admin_update', 'Api\UsersController@adminUpdate');
//posts routes
Route::resource('/posts', 'Api\PostsController')->except(['edit', 'create'])->middleware('auth:api');
Route::put('/posts/{post}/update_privacy', 'Api\PostsController@updatePrivacy')->middleware('auth:api');
Route::get('/users/{user}/posts', 'Api\PostsController@userPosts')->middleware('auth:api');


//photo
Route::get('/posts/{post}/photos', 'PhotoController@postPhotos')->middleware('auth:api');

//friend requests routes
Route::post('/friendship/send', 'Api\FriendRequestsController@send')->middleware('auth:api');
Route::delete('/friendship/cancel', 'Api\FriendRequestsController@cancel')->middleware('auth:api');
Route::delete('/friendship/reject', 'Api\FriendRequestsController@reject')->middleware('auth:api');
Route::put('/friendship/accept', 'Api\FriendRequestsController@accept')->middleware('auth:api');
Route::post('/friendship/block', 'Api\FriendRequestsController@block')->middleware('auth:api');
Route::delete('/friendship/remove_block', 'Api\FriendRequestsController@removeBlock')->middleware('auth:api');
Route::delete('/friendship/unfriend', 'Api\FriendRequestsController@unfriend')->middleware('auth:api');
Route::get('/friendship/{user_id}/friends_list', 'Api\FriendRequestsController@friendsList')->middleware('auth:api');
Route::get('/friendship/{id}/friend_status', 'Api\FriendRequestsController@friendStatus')->middleware('auth:api');
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
Route::get('/photos/{photo}/description', 'Api\DescriptionsController@photoDescription')->middleware('auth:api');

//messages routes
Route::post('/messages/send', 'Api\MessagesController@send')->middleware('auth:api');
Route::put('/messages/receive', 'Api\MessagesController@receive')->middleware('auth:api');
Route::put('/messages/receive_new', 'Api\MessagesController@receiveNew')->middleware('auth:api');
//replies routes
Route::resource('/replies', 'Api\RepliesController')->except(['edit', 'create', 'index'])->middleware('auth:api');
