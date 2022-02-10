<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\order;

class DashboardController extends Controller
{
    function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active'=>'dashboard']);
            return $next($request);
        });
    }
    function show(){
        $orders=order::latest()->paginate(10);
        $success_order=order::where('status','Thành công')->get();
        $processing_order=order::where('status','Đang xử lý')->get();
        $sales=order::select('total')->where('status','Thành công')->get();
        $total_sales=$sales->sum('total');
        return view('admin.dashboard',compact('orders','success_order','processing_order','total_sales'));
    }
}
