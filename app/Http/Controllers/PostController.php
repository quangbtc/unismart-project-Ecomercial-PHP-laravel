<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use App\Post_Cat;
use App\page;

class PostController extends Controller
{
    function index(){
        $posts=Post::paginate(10);
        $parent_cat=Post_Cat::where([['parent_id','=',0],['status','=','Công khai']])->get();
      
        return view('client.post.list',compact('posts','parent_cat'));

    }
    function detail($id){
        $post_detail=Post::find($id);
        $parent_cat = Post_Cat::where([['parent_id', '=', 0], ['status', '=', 'Công khai']])->get();
      
        return view('client.post.detail',compact('post_detail','parent_cat'));
    }
}
