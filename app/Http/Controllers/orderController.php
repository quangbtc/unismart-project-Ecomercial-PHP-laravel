<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\order;
use App\order_item;

class orderController extends Controller
{
    //
    //
    function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'order']);
            return $next($request);
        });
    }
    function list(Request $request)
    {
        $status = $request->input('status'); // Lấy trạng thái chọn từ url
        $list_act = [
            'delete' => 'Xoá'
        ];
        if ($status =='success') {
            $orders=order::where('status','Thành công')->orderBy('id','desc')->paginate(10);
            $list_act = [
                'un_confirm' => 'Huỷ đơn hàng',
                'delivering'=>'Đang giao hàng',   
                'delete'=>'Xoá đơn hàng'
            ];
        } elseif ($status == 'processing') {
            $orders = order::where('status', 'Chờ xử lý')->orderBy('id','desc')->paginate(10);
            $list_act = [
                'delivering'=>'Đang giao hàng',
                'delete' => 'Xoá đơn hàng'
            ];
        } elseif ($status == 'delivering') {
            $orders = order::where('status', 'Đang giao hàng')->orderBy('id', 'desc')->paginate(10);
            $list_act = [
                'confirm_success' => 'Đã giao hàng',
                'un_confirm'=>'Huỷ đơn hàng',
                'delete' => 'Xoá đơn hàng'
            ];
        }   
         else {   
           $orders=order::orderBy('id','desc')->paginate(10);          
        }

        $order_success = order::where('status','Thành công')->count();
        $order_processing = order::where('status', 'Chờ xử lý')->count();
        $order_delivering = order::where('status', 'Đang giao hàng')->count();
        $count = [$order_success, $order_processing,$order_delivering];

        return view('admin.order.list', compact('orders', 'count', 'list_act'));
    }
    function delete($id)
    {

        order::find($id)->delete();

        //Xoá user_id bên bảng role_user
        // $user->roles()->detach();
        return redirect(route('order.list'))->with('status', 'Đã xoá thành công');
    }
    function action(Request $request)
    {
        $list_check = $request->input('list_check');
        // return $list_check;
        if (!empty($list_check)) {

            if (!empty($list_check)) {
                $act = $request->input('act');
                if ($act == '') {
                    return redirect(route('order.list'))->with('status', 'Bạn chưa chọn tác vụ');
                }
                if ($act == 'delete') {
                    order::destroy($list_check);
                    return redirect(route('order.list'))->with('status', 'Đã xoá thành công');
                }
                if ($act == 'un_confirm') {
                   foreach ($list_check as $id) {
                       order::where('id',$id)
                       ->update([
                           'status'=>'Chờ xử lý'
                       ]);
                   }
                    return redirect(route('order.list'))->with('status', 'Huỷ duyệt thành công');
                }
                if ($act =='confirm_success') {
                    foreach ($list_check as $id) {
                        order::where('id', $id)
                            ->update([
                                'status' => 'Thành công'
                            ]);
                    }
                    return redirect(route('order.list'))->with('status', 'Xác nhận đơn hàng thành công');
                }
                if ($act == 'delivering') {
                    foreach ($list_check as $id) {
                        order::where('id', $id)
                            ->update([
                                'status' => 'Đang giao hàng'
                            ]);
                    }
                    return redirect(route('order.list'))->with('status', 'Xác nhận đơn hàng thành công');
                }
               
            }
        } else {
            return redirect(route('order.list'))->with('status', 'Bạn chưa đơn hàng dùng');
        }
    }
    function detail($id)
    {
        $order=order::find($id);
        return view('admin.order.detail',compact('order'));
    }
    // public function update(Request $request, $id)
    // {
    //     $request->validate(
    //         [
    //             'full_name' => ['required', 'string', 'max:40'],
    //             'email' => ['required', 'string', 'email', 'max:50'],
    //             'phone' => ['required', 'min:10', 'numeric']
    //         ],
    //         [
    //             'required' => ':attribute không được để trống',
    //             'min' => ':attribute có độ dài nhỏ nhất :min ký tự',
    //             'numeric' => ':attribute phải là số'
    //         ],
    //         [
    //             'full_name' => 'Tên khách hàng',
    //             'email' => 'Email',
    //             'phone' => 'Số điện thoại'
    //         ]
    //     );
    //     customer::where('id', $id)
    //         ->update([
    //             'full_name' => $request->input('full_name'),
    //             'email' => $request->input('email'),
    //             'phone' => $request->input('phone'),
    //             'address' => $request->input('address'),
    //             'note' => $request->input('note'),
    //         ]);
    //     return redirect(route('customer.list'))->with('status', 'Cập nhật thành công');
    // }
}
