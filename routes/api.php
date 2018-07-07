<?php

use Illuminate\Http\Request;
use App\User;

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
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, Authorization');


Route::post('/addPost','PostController@addpost');
Route::post('/shimaa','PostController@elabd');
Route::post('/addPost/android','PostController@addpostandroid');
Route::post('/clients','ClientController@core');
Route::post('/getnotifications','PostController@notification');
Route::post('/likes','PostController@likes');
Route::get('/getposts','PostController@getpost');
Route::post('/upload','PostController@uploadImg');

// for test
Route::post('/addComment','CommentController@addComment');
Route::get('/postComments/{post_id}','CommentController@postComments');

Route::get('/addComment/{username}/{body}/{post_id}','CommentController@addComment');

// get posts
/*

$posts = url(/home);
foreach($posts as $post){
	<ititile>$post->name</title>
	<image src="httlp...../post->image">
	$comments = url('/postcommnets'.$post->id);
	foreach($comments as $comment){
		<lable>$comment->body </lable>
	}
}

*/
Route::post('login', 'API\PassportController@login');
Route::post('/register', 'API\PassportController@register');
Route::group(['middleware' => 'auth:api'], function(){
	Route::post('get-details', 'API\PassportController@getDetails');

	Route::get('/users', function () {
		$users = User::all();

		return response()->json([
			'error' => false,
			'data' => $users,
		]);
	});

});
