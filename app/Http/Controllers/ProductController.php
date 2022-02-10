<?php

namespace App\Http\Controllers;
use App\Product;
use App\Product_Cat;
use App\page;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    //
    function index()
    {
        $products = Product::paginate(8);
        $parent_cat = Product_Cat::where([['parent_id', '=', 0], ['status', '=', 'Công khai']])->get();
        $new_products = Product::latest()->take(10)->get();
        //Lấy id trang liên hệ
        $page = page::where('title', 'Giới thiệu')
        ->where('status', 'Công khai')
        ->first();
        return view('client.product.list',compact('parent_cat','products','new_products'));
    }
    function detail($lug,$id)
    { 
        $view=Product::select('view')->where('id',$id)->value('view');
        $total=$view+1;
        Product::find($id)->update([
            'view'=>$total
        ]);
        $product_detail = Product::find($id);
        $parent_cat = Product_Cat::where([['parent_id', '=', 0], ['status', '=', 'Công khai']])->get();
        $new_products = Product::latest()->take(10)->get();
       
        return view('client.product.detail', compact('product_detail', 'parent_cat','new_products'));
    }
  
}
