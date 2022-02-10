<?php

namespace App\Http\Controllers;
use App\Post;
use App\Post_Cat;
use Illuminate\Http\Request;

class PostCatController extends Controller
{
    //
    function list($lug,$id){
        
        $parent_cat = Post_Cat::where([['parent_id', '=', 0], ['status', '=', 'Công khai']])->get();
        $posts=Post::where([['parent_cat','=',$id],['status','=','Công khai']])->paginate(10);

        return view('client.post.category.list',compact('parent_cat','posts'));
    }
}
