<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Post;
use App\Comment;
class CommentController extends Controller{

  /*  public function Waddcomment(Request $request, Post $post)
    {
    	
      		$comment = new Comment;
      		$comment->post_id = $post->id;
      		$comment->body = request('body');
      		$comment-> save();
      		
         return response()->json(['comment'=>$comment],201);
    } */
 // add comment code final
    public function addComment(Request $request,Comment $comment){
    	

		$comment->create([
		'username' =>$request->username ,
		'post_id' =>$request->post_id,
    'body' =>$request->body
		
		]);

    return response()->json(['comment'=>$comment],201);

    }

    public function postComments($post_id){

    	//inputs[ post_id]
    	// comments related to this posts
      $comments = Comment::where('post_id',$post_id)->get();
      return $comments;
    }
}
