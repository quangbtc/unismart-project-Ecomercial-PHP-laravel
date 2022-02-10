<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product_Cat;
use App\Product;
use App\slider;
use App\page;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    function index()
    {
        $list_cat = Product_Cat::all();
        $products = Product::paginate(8);
        $parent_cat = Product_Cat::where([['parent_id', '=', 0], ['status', '=', 'Công khai']])->get();
        foreach ($parent_cat as $item) {
            $parent[$item->id] = $item->cat_title;
        }
        foreach ($parent_cat as $cat) {
            $list_child[$cat->id] = data_tree($list_cat, $cat->id, 0);
        }
        $new_products = Product::latest()->take(10)->get();
        $most_view_product = Product::latest('view', 'desc')->take(10)->get(); //Lấy sản phẩm xem nhiều nhất
        //Lấy slider
        $sliders = slider::latest()->take(10)->get();
        return view('client.home', compact('parent_cat', 'products', 'list_child', 'parent', 'new_products', 'most_view_product', 'sliders'));
    }
    function search(Request $request)
    {
        $keywords = $request->keywords; // Lấy dữ lieu tìm kiếm
        $products = Product::where('title', 'LIKE', "%$keywords%")->get();
        $list_cat = Product_Cat::all();
        $parent_cat = Product_Cat::where([['parent_id', '=', 0], ['status', '=', 'Công khai']])->get();
        foreach ($parent_cat as $item) {
            $parent[$item->id] = $item->cat_title;
        }
        foreach ($parent_cat as $cat) {
            $list_child[$cat->id] = data_tree($list_cat, $cat->id, 0);
        }
        //Lấy sản phẩm mới nhất
        $new_products = Product::latest()->take(10)->get();
        //Lấy sliders
        $sliders = slider::latest()->take(10)->get();

        return view('client.product.search', compact('parent_cat', 'products', 'list_child', 'parent', 'new_products', 'sliders'));
    }
}