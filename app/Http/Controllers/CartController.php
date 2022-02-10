<?php

namespace App\Http\Controllers;
use Carbon\Carbon;

use Illuminate\Http\Request;
use App\Product;
use App\customer;
use App\Mail\order_confirm_mail;
use App\Helpers\data;
use App\order;
use Illuminate\Support\Facades\Mail;
use Gloudemans\Shoppingcart\Facades\Cart;

class CartController extends Controller
{
    //
    function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'cart']);
            return $next($request);
        });
    }
    function show()
    {
        return view('client.cart.show');
    }
    function add($id)
    {
        // Cart::destroy();
        //Lấy sản phẩm theo id
        $product = Product::find($id);
        Cart::add([
            'id' => $product->id,
            'name' => $product->title,
            'qty' => 1,
            'price' => $product->sale_price,
            'options' => ['thumbnail' => $product->thumb] //Tuỳ chọn màu sắc sản phẩm hoặc size
        ]);
        return redirect(route('cart.show'));
    }
    function remove($rowId)
    {
        Cart::remove($rowId);
        return redirect(route('cart.show'));
    }
    function destroy()
    {
        Cart::destroy();
        return redirect(route('cart.show'));
    }
    function update(Request $request)
    {
        // $request->all();
        // dd($request->all());
        $data = $request->qty;
        foreach ($data as $key => $value) {
            Cart::update($key, $value);
        }
        return redirect(route('cart.show'));
    }
    function checkout()
    {

        Cart::content();
        $customer=customer::where('email',session('user_login'))->first();
        return view('client.cart.checkout',compact('customer'));
    }
    function restore(Request $request)
    {
        //B1: Lấy thông tin khach hàng.
        $request->validate(
            [
                'full_name' => ['required', 'string', 'max:40'],
                'email' => ['required', 'string', 'email', 'max:50'],
                'phone' => ['required', 'min:10', 'numeric']
            ],
            [
                'required' => ':attribute không được để trống',
                'min' => ':attribute có độ dài nhỏ nhất :min ký tự',
                'numeric' => ':attribute phải là số'
            ],
            [
                'full_name' => 'Tên khách hàng',
                'email' => 'Email',
                'phone' => 'Số điện thoại'
            ]
        );
        //B2 : Kiêm tra khách hàng đã tồn taị trong databas chuae

        $customer = customer::where('email', $request->input('email'))
            ->orWhere('phone', $request->input('phone'))
            ->first();
        $total = Cart::total();
        $product_id = $request->input('product_id');
        $subtotal = $request->input('sub_total');
        $qty = $request->input('qty');
        $price = $request->input('price');
        $order_id=order::latest()->first()->id;
        if ($customer) {
            //Nếu đã tồn tại khách hang ttrong database
            //Thêm dữ liệu vào đơn hàng.
            
            $order = order::create([
                'code' => '#' . str_pad($order_id + 1, 8, "0", STR_PAD_LEFT),
                'customer_id' => $customer->id,
                'total' => str_replace('.', '', $total),
                'payment' => $request->input('payment'),
                'status' => 'Chờ xử lý',
                'note' => $request->input('note')
            ]);
            session(['user_login' => $customer->email, 'phone' => $customer->phone]);
            //Thêm dữ liệu vào bảng trung gian order_item 
            for ($i = 0; $i < count($product_id); $i++) {
                $order->products()->attach(
                    $product_id[$i],
                    [
                        'qty' => $qty[$i],
                        'sub_total' => $subtotal[$i],
                        'price' => $price[$i]
                    ]
                );
            }
        } else {
            //TRường hợp chưa tồn tại khách hàng thì
            //Thêm khách hàng vào database
            
            $customer = customer::create([
                'full_name' => $request->input('full_name'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'address' => $request->input('address')
            ]);
          
            //Thêm đơn hàng
            $order = order::create([
                'code'=> '#' . str_pad($order_id + 1, 8, "0", STR_PAD_LEFT),
                'customer_id' => $customer->id,
                'total' => str_replace('.', '', $total),
                'payment' => $request->input('payment'),
                'status' => 'Chờ xử lý',
                'note' => $request->input('note')
            ]);
            session(['user_login' => $customer->email, 'phone' => $customer->phone]);
            //Thêm dữ liệu vào bảng trung gian
            for ($i = 0; $i < count($product_id); $i++) {
                $order->products()->attach(
                    $product_id[$i],
                    [
                        'qty' => $qty[$i],
                        'sub_total' => $subtotal[$i],
                        'price' => $price[$i]
                    ]
                );
            }
        }
        //Xoá giỏ hàng
        Cart::destroy();
        //Gửi email đến khách hàng.
        $data = [
            'full_name' => $customer->full_name,
            'email' =>$customer->email ,
            'address' => $customer->address,
            'phone' => $customer->phone,
            'order'=>$order,
            'fee_ship'=>'15000',
           
        ];
        Mail::to($customer->email)->send(new order_confirm_mail($data));
        return redirect(route('cart.list'))->with('status','Đặt hàng thành công');
    }
    function list()
    {
        //Lấy đơn hàng
       
        $customer=customer::where('email',session('user_login'))->first();
      
        $order=order::where('customer_id',$customer->id)->latest()->first();
   

        return view('client.cart.list',compact('order'));
    }
    function detail($id)
    {
        $order = order::find($id);
        return view('client.cart.detail', compact('order'));
    }
    function order_history($customer){
        $customer=customer::where('email',$customer)->first();
        $orders=order::where('customer_id',$customer->id)->get();
        return view('client.cart.history',compact('orders'));
    }
    
}
