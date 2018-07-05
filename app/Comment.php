<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
	protected $fillable=[
              'post_id','body','username'
   ];
     public function post()
  {
	return $this->belongsTo('App\post');
  } 
}
