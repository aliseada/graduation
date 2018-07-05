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

Route::post('/addPost','PostController@addpost');
Route::post('/clients','ClientController@core');
Route::post('/getnotifications','PostController@notification');
Route::post('/likes','PostController@likes');
Route::get('/home','PostController@getpost'
);


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
