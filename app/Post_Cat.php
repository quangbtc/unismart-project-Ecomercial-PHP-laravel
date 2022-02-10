<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Post_Cat extends Model
{
    //
  
    protected $fillable = [
        'cat_title', 'parent_id', 'slug', 'status'
    ];
    protected $table='post_cat';
    public function posts()
    {
        return $this->hasMany('App\Post','parent_cat');
    }
    public function child(){
        return $this->hasMany('App\Post_Cat','parent_id');
    }
}
