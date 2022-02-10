<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\customer;

use Illuminate\Support\Facades\DB;


class CustomerController extends Controller
{
    //
    
    function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'customer']);
            return $next($request);
        });
    }
    function list(Request $request)
    {
        $status = $request->input('status'); // Lấy trạng thái chọn từ url
        $list_act = [
            'delete' => 'Xoá tạm thời'
        ];
        if ($status == 'trash') {
            $customers = customer::onlyTrashed()->paginate(10);
            $list_act = [
                'restore' => 'Khôi phục',
                'forceDelete' => 'Xoá vĩnh viễn'
            ];
        } else {
            $keyword = '';
            if ($request->input('keyword')) {
                $keyword = $request->input('keyword');
            }

            $customers = customer::where('full_name', 'LIKE', "%{$keyword}%")
                ->orWhere('email', 'LIKE', "%{$keyword}%")
                ->orWhere('phone', 'LIKE', "%{$keyword}%")
                ->paginate(10);
        }

        $customer_active = customer::count();
        $customer_trashed = customer::onlyTrashed()->count();
        $count = [$customer_active, $customer_trashed];

        return view('admin.customer.list', compact('customers', 'count', 'list_act'));
    }
    function delete($id)
    {

        customer::find($id)->delete();

        //Xoá user_id bên bảng role_user
        // $user->roles()->detach();
        return redirect(route('customer.list'))->with('status', 'Đã xoá thành công');
    }
    function action(Request $request)
    {
        $list_check = $request->input('list_check');
        // return $list_check;
        if (!empty($list_check)) {
        
            if (!empty($list_check)) {
                $act = $request->input('act');
                if ($act == '') {
                    return redirect(route('customer.list'))->with('status', 'Bạn chưa chọn tác vụ');
                }
                if ($act == 'delete') {
                    customer::destroy($list_check);
                    //Xoá roles của users
                    return redirect(route('customer.list'))->with('status', 'Đã xoá thành công');
                }
                if ($act == 'restore') {
                    customer::withTrashed()
                        ->whereIn('id', $list_check)
                        ->restore();


                    return redirect(route('customer.list'))->with('status', 'Đã khôi phục thành công');
                }
                if ($act == 'forceDelete') {
                    try {
                        DB::beginTransaction();
                        customer::withTrashed()
                            ->whereIn('id', $list_check)
                            ->forceDelete();
                        DB::table('order')
                            ->whereIn('customer_id', $list_check)
                            ->delete();
                        // dd($listRoleOfUser);
                        DB::commit();
                        return redirect(route('customer.list'))->with('status', 'Đã xoá vĩnh viễn thành công');
                    } catch (\Exception $exception) {
                        DB::rollBack();
                    }
                }
            }
        } else {
            return redirect(route('customer.list'))->with('status', 'Bạn chưa chọn người dùng');
        }
    }
    function edit($id)
    {
        $customer = customer::find($id);
        return view('admin.customer.edit', compact('customer'));
    }
    public function update(Request $request, $id)
    {
        $request->validate(
            [
                'full_name' => ['required', 'string', 'max:40'],
                'email' => ['required', 'string', 'email', 'max:50'],
                'phone' => ['required','min:10','numeric']
            ],
            [
                'required' => ':attribute không được để trống',
                'min' => ':attribute có độ dài nhỏ nhất :min ký tự',
                'numeric'=> ':attribute phải là số'
            ],
            [
                'full_name' => 'Tên khách hàng',
                'email' => 'Email',
                'phone' => 'Số điện thoại'
            ]
        );
        customer::where('id', $id)
            ->update([
                'full_name' => $request->input('full_name'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'address' => $request->input('address'),
                'note' => $request->input('note'),
            ]);
        return redirect(route('customer.list'))->with('status', 'Cập nhật thành công');
    }
    function logout(){
        session()->forget(['user_login','phone']);
        return redirect(route('client.product.index'));
    }
   
}
