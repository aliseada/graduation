<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
	protected $table = 'post';
     protected $fillable=[
   'name','age','birth','city','status',
    'description','photoUrl','image'
   ];

   
    public function comments()
    {

	return $this->hasMany('App\comment');
}
public function likes()
    {

	return $this->hasMany('App\like');
}

}
