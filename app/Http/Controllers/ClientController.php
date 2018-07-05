<?php

namespace App\Http\Controllers;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Post;

class ClientController extends Controller
{
    public function core(Request $request,Post $post ){

$client = new Client([
                'timeout'  => 5.0,
            ]); 
         $response = $client->post('https://api.apiprovider.com:9900/create_task', 
                ['json' => [
                    "post_id" =>$post->id,
                    "url" => $post->url
                ]]);
        return ("success");
        // return response()->json($response,200);

}
}
