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
use Intervention\Image\ImageManagerStatic as Image;
use File;

class PostController extends Controller

{
  public function uploadImg(Request $request) {
      $img = $request['avatar'];
      $img_name=time() . '.' . $img->getClientOriginalExtension();
      Storage::put($img_name, file_get_contents($img->getRealPath()));
      // Toda
      // 1 - get link
      // 2 - return link
     

    if (!Storage::disk('local')->exists($img_name)) {
        abort(404);
    }
    else{
      $path = Storage::disk('local')->url($img_name);


    return response()->json([
      "error" => false,
      "path" => $path
    ]);


  }

  }

  public function addpost(Request $request, Post $post){


    	$post->create([
        'name' =>$request->name ,
        'age' =>$request->age,
        'birth' =>$request->birth,
        'city' =>$request->city,
        'status' =>$request->status,
        'description' =>$request->description,
        'photoUrl' => $request->photoUrl,
  	]);

     
		$id = $post->max('id'); // get record id
		$data = Post::find($id);

		// $record = json_encode($data);
		//return response()->json([
           // "error"=>false,
           // "data"=>$data,
      //  ]);
        //send http requests to deep learning algrith
        //creat a new client from gruzzle
       /*  $client = new Client([
                'timeout'  => 5.0,
            ]);
         $response = $client->post('http://1b025780.ngrok.io/api/match',
                ['json' => [
                    "post_id" =>$post->id,
                    "img_path" => $post->photoUrl
                ]]);

         return response()->json($response,200);*/

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

   
/*
    $post->create([
        'name' =>$request->name ,
        'age' =>$request->age,
        'birth' =>$request->birth,
        'city' =>$request->city,
        'status' =>$request->status,
        'description' =>$request->description,
        'photoUrl' => $request->photoUrl,
    ]);
      $img2 = $request['photoUrl'];
      $img_name2=time() . '.' . $img2->getClientOriginalExtension();
      Storage::put($img_name2, 
        base64_encode(file_get_contents($img2->getRealPath())));
    if (!Storage::disk('local')->exists($img_name2)) {
        abort(404);
    }
    else{
      $path2 = Storage::disk('local')->url($img_name2);
    return response()->json([
      "error" => false,
      "path" => $path2
    ]);*/
    
  public function addpostandroid(Request $request , Post $post){

     $post->create([
        'name' =>$request->name ,
        'age' =>$request->age,
        'birth' =>$request->birth,
        'city' =>$request->city,
        'status' =>$request->status,
        'description' =>$request->description,
        'photoUrl' => $request->photoUrl,
    ]);

   $image_data = $request->file('photoUrl');
   // $type = pathinfo($image, PATHINFO_EXTENSTION);
   $image_name= 'image_'.time().'.png';
    @list($type, $image_data) = explode(';', $image_data);
     @list(, $image_data) = explode(',', $image_data);
//photo = base64_decode($image_data);
  
    //Storage::put('',file_get_contents($image_data->getRealPath()));
      if($image_data!=""){ // storing image in storage/app/public Folder 
          Storage::disk('local')->put($file_name,base64_decode($image_data),file_get_contents($image_data->getRealPath())); 
    } 
        
      if (!Storage::disk('local')->exists($image_data)) {
       // abort(404);
    }
    else{
      $path = Storage::disk('local')->url($image_data);
      
       /*return response()->json([
      "error" => false,
      "path" => $path

    ]);*/
      
       return response()->json([
      "error" => false,
      "path" => $path

    ]);


  
}
}

  public function core(Request $request , Post $post){
   $client = new Client();
   $response=$client->post('http://45a2b937.ngrok.io/api/match', [
              'json' => [
                    "post_id" =>2,
                    "img_path" => "6.jpg"
                ]]);
  // $response=$request->send();
   //$record=json_decode($response);
    
  return $response ;
//return Redirect::to('api/Check/'.$response);

 }

 public function getpostcheck(Request $request , Post $post){
      //  $this->core();

        //$post=Post::where('post_id',$this->$record->$postId,'img_path',$this->$record->photoUrl)->get();
      //  return $post;
  return $this->$response;
 }
}


