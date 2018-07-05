<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Like;
use App\Post;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PostController extends Controller

{
  public function uploadImg(Request $request) {
      $img = $request['avatar'];
      $img_name=time() . '.' . $img->getClientOriginalExtension();

      Storage::put($img_name, file_get_contents($img->getRealPath()));
      // Toda
      // 1 - get link
      // 2 - return link


  }

  public function addpost(Request $request, Post $post){



    	$post->create([
        'name' =>$request->name ,
        'age' =>$request->age,
        'birth' =>$request->birth,
        'city' =>$request->city,
        'status' =>$request->status,
        'description' =>$request->description,
        'url' => $request->photoUrl,
  	]);


		$id = $post->max('id'); // get record id
		$query = "select name, age, birth,city,status,description,url from post where id = $id";
		$data = DB::select($query);

		// $record = json_encode($data);
		return response()->json([
            "error"=>false,
            "data"=>$data,
        ]);
        //send http requests to deep learning algrith
        //creat a new client from gruzzle
         $client = new Client([
                'timeout'  => 5.0,
            ]);
         $response = $client->post('http//8cfe8f03.ngrok.io/api/match',
                ['json' => [
                    "post_id" =>$post->id,
                    "img_path" => $post->url
                ]]);

         return response()->json($response,200);
         $data=json_decode($response);

    }

    // noti function


    public function notification(){

    }


    public function getpost(){
    	$post=Post::all();
    	$response=[
    	'post'=>$post];
    	return response()->json($response,200);

    }
public function likes(Request $request){
        $like_s=$request->like_s;
        $post_id=$request->post_id;
        $user_id=$request->user_id;
        $like=DB::table('likes')->where('post_id',$post_id)->where('user_id',$user_id);
        if (!$like) {
           $new_like=new Like;
           $new_like->post_id=$post_id;
           $new_like->user_id=$user_id;
           $new_like->like=1;
           $new_like->save();
           $is_like=1;
        }
        elseif ($like->like==1) {
           DB::table('likes')->where('post_id',$post_id)->where('user_id',$user_id)->delete();
           $is_like=0;
        }
        elseif ($like->like==0) {
           DB::table('likes')->where('post_id',$post_id)->where('user_id',$user_id)->update( );
           $is_like=1;
        }

         $response=array('is_like'=>$is_like);
         return response()->json($response,200);

    }



    public function store(Request $request){
    	/*
      $this->validate(request(),[
       'url'=>'image|mimes:jpg,png,gif|max:2048'
        ]);
        */
    $img_name=time() . '.' . $request->url->getClientOriginalExtension();
    $post = post::find(1);

    // $post=new post;
     $post->url=$img_name;
     $post->save();
     $request->url->move(public_path('upload'),$img_name );

   return response()->json(['post'=>$post],201);
   }

}
