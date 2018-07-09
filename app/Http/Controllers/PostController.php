<?php

namespace App\Http\Controllers;

use App\Like;
use App\Post;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;

//use File;

class PostController extends Controller
{
    protected $posts;

    public function __construct()
    {
        $this->posts = [];
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addPost(Request $request)
    {
        $this->uploadImg($request);
        $postId = Post::insertGetId([
            'name' => $request->name,
            'age' => $request->age,
            'birth' => $request->birth,
            'city' => $request->city,
            'status' => $request->status,
            'description' => $request->description,
            'photoUrl' => $request->photoUrl
        ]);

        return response()->json([
            'error' => false,
            'message' => 'Post created successfully',
            'data' => [
                'postId' => $postId,
                'photoUrl' => $request->photoUrl,
            ]
        ]);
    }

    public function uploadImg(Request $request)
    {
        $img = $request['avatar'];
        $img_name = time() . '.' . $img->getClientOriginalExtension();
        Storage::disk('public')
            ->put($this->buildFilePath($img_name, 'images'), file_get_contents($img->getRealPath()));

        $path = $photoUrl = $this->getImageUrl($img_name);

        return response()->json([
            "error" => false,
            "path" => $path
        ]);
    }

    // noti function

    protected function buildFilePath($name, $folder)
    {
        return $folder . '/' . $name;
    }

    public function getImageUrl($image)
    {
        return asset("storage/images/$image");
    }

    public function notification()
    {

    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
//    public function addpostandroid(Request $request)
//    {
//        $extension = $this->getImageExtension($request->base64);
//
//        $name = str_random(32) . '.' . $extension;
//        Storage::disk('public')->put($this->buildFilePath($name, 'images'),
//            Image::make(base64_decode($request->base64))->stream()->__toString()
//        );
//
//        $photoUrl = asset("storage/images/$name");
//
//        $postId = Post::insertGetId([
//            'name' => $request->name,
//            'age' => $request->age,
//            'birth' => $request->birth,
//            'city' => $request->city,
//            'status' => $request->status,
//            'description' => $request->description,
//            'photoUrl' => $photoUrl
//        ]);
//
//        return response()->json([
//            'error' => false,
//            'message' => 'Post created successfully',
//            'data' => [
//                'postId' => $postId,
//                'photoUrl' => $photoUrl,
//            ],
//        ]);
//    }
    public function getPost()
    {
        $post = Post::all();
        $response = ['post' => $post];
        return response()->json($response, 200);

    }

    public function likes(Request $request)
    {
        $like_s = $request->like_s;
        $post_id = $request->post_id;
        $user_id = $request->user_id;
        $like = DB::table('likes')->where('post_id', $post_id)->where('user_id', $user_id);
        if (!$like) {
            $new_like = new Like;
            $new_like->post_id = $post_id;
            $new_like->user_id = $user_id;
            $new_like->like = 1;
            $new_like->save();
            $is_like = 1;
        } elseif ($like->like == 1) {
            DB::table('likes')
                ->where('post_id', $post_id)->where('user_id', $user_id)->delete();
            $is_like = 0;
        } elseif ($like->like == 0) {
            DB::table('likes')
                ->where('post_id', $post_id)->where('user_id', $user_id)->update();
            $is_like = 1;
        }

        $response = array(
            'is_like' => $is_like
        );
        return response()->json($response, 200);

    }


    public function addPostAndroid(Request $request)
    {
        $image = $request->file('image');
//        $extension = $this->getImageExtension($image->getClientOriginalName()); //old
        $extension = pathinfo($image->getClientOriginalName(), PATHINFO_EXTENSION);//new
        $image_name = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);//new

        $name = str_replace(' ', '_', strtolower($image_name)) . str_random(5) . '.' . $extension;
//        Storage::disk('public')->put($this->buildFilePath($name, 'images'),//old
//            Image::make(base64_decode($request->base64))->stream()->__toString()
//        );
        $manager = new ImageManager(array('driver' => 'gd'));
        $image_manager = $manager->make(base64_encode(file_get_contents($image->getRealPath())));
        $image_manager->save(storage_path('app/public/images/' . $name));
        $photoUrl = $this->getImageUrl($name);//new

        $postId = Post::insertGetId([
            'name' => $request->name,
            'age' => $request->age,
            'birth' => $request->birth,
            'city' => $request->city,
            'status' => $request->status,
            'description' => $request->description,
            'photoUrl' => $photoUrl
        ]);

        return [
            'error' => false,
            'message' => 'Post created successfully',
            'data' => [
                'postId' => $postId,
                'photoUrl' => $photoUrl,
            ]];
    }

    public function core(Request $request)
    {
        $post_id = $request->get('post_id');
        $photoUrl = $request->get('photoUrl');
        $photoName = substr($photoUrl, strpos($photoUrl, "images/") + 7);
        $client = new Client();
        $response = $client->post('http://85dfb234.ngrok.io/api/match', ['json' => ["post_id" => $post_id, "img_path" => $photoName]]);
        $data = \GuzzleHttp\json_decode($response->getBody()->getContents());
        foreach ($data as $item) {
            $post = Post::where('id', $item->post_id)->first();
            if ($post != null)
                $this->posts[] = $post;
        }
        return ['response' => $data, 'posts' => $this->posts];

    }

    public function getPostCheck(Request $request, Post $post)
    {
        //  $this->core();
        //$post=Post::where('post_id',$this->$record->$postId,'img_path',$this->$record->photoUrl)->get();
        //  return $post;
        return $this->$response;
    }

    protected function getImageExtension($imageBase64)
    {
        $mime_type = substr($imageBase64, 5, strpos($imageBase64, ';') - 5);
        $mime_type = explode('/', $mime_type);
        return $mime_type[1];
    }
}

