<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\page;

class PageController extends Controller
{
    //
    function about_us($id){
        $page=page::find($id);
        return view('client.page.about_us',compact('page'));
    }
    function contact($id)
    {
        $page = page::find($id);
        return view('client.page.contact', compact('page'));
    }
    
}
