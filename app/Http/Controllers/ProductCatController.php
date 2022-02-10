<?php

namespace App\Http\Controllers;
use App\Product_Cat;
use App\Product;

use Illuminate\Http\Request;
use Illuminate\Queue\Events\Looping;

class ProductCatController extends Controller
{
    //
    function list($lug, $id)
    {
        $list_cat=Product_Cat::all();      //Lấy tất cả danh mục sản phẩm
        $productCat = Product_Cat::find($id); // Lây danh mục sản phẩm click vào
       if($productCat->products->count()==0){   // Kiểm tra danh mục có sản phẩm ko ?
        if($productCat->child->count()>0){
                $list_child = data_tree($list_cat, $id, 0); // lấy tât cả danh mục con
                foreach ($list_child as $child) {
                    $child_id[] = $child->id;
                }
                $products = Product::where('status', 'Công khai')->whereIn('parent_cat', $child_id)->paginate(12);  
        }else{
            $products=[];
        }
         
       }else{
           $products=Product::where('parent_cat',$id)->paginate(12);
       }
     
        $parent_cat = Product_Cat::where([['parent_id', '=', 0], ['status', '=', 'Công khai']])->get();
        $new_products = Product::latest()->take(10)->get();
        return view('client.product.category.list', compact('parent_cat', 'products','productCat','new_products'));
    }
}
