<?php

namespace App\Http\Controllers;

use App\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminPermissionController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'permission']);
            return $next($request);
        });
    }
    function list(Request $request){
        $status = $request->input('status'); // Lấy trạng thái chọn từ url
        $list_act = [
            'delete' => 'Xoá tạm thời'
        ];
        if ($status == 'trash') {
            $permissions = Permission::onlyTrashed()->paginate(10);
            $list_act = [
                'restore' => 'Khôi phục',
                'forceDelete' => 'Xoá vĩnh viễn'
            ];
        } else {
            $keyword = '';
            if ($request->input('keyword')) {
                $keyword = $request->input('keyword');
            }
            $permissions = Permission::where('name', 'LIKE', "%{$keyword}%")->paginate(10);
        }
        $permission_active = Permission::count();
        $permission_trashed = Permission::onlyTrashed()->count();
        $count = [$permission_active, $permission_trashed];
        return view('admin.permission.list', compact('permissions', 'count', 'list_act'));
    
    
    }
    function add(){

        return view('admin.permission.add');
    }
    function store(Request $request)
    {
        $request->validate(
            [
                'name' => ['required', 'string', 'max:40', 'unique:roles']
            ],
            [
                'required' => ':attribute không được để trống',
                'max' => ':attribute có độ dài lớn nhất :max ký tự',
                'min' => ':attribute có độ dài nhỏ nhất :min ký tự',
                'unique' => ':attribute đã tồn tại'
            ],
            ['name' => 'Tên quyền',]
        );
        Permission::create(
            [
                'name' => $request->input('name'),
                'display_name'=>$request->input('name_display')

            ]
        );
        return redirect(route('permission.list'))->with('status', 'Thêm quyền tác vụ thành công');
    }
    function delete($id)
    {

        $permission = Permission::find($id);
        $permission->delete();
        return redirect(route('permission.list'))->with('status', 'Đã xoá thành công');
    }
    function action(Request $request)
    {
        $list_check = $request->input('list_check');

        // return $list_check;
        if (!empty($list_check)) {
            // foreach ($list_check as $k => $id) {
            //     if (Roles::find($id)->name == 'Admin') {
            //         unset($list_check[$k]);
            //     }
            // }
            if (!empty($list_check)) {
                $act = $request->input('act');
                if ($act == '') {
                    return redirect(route('permission.list'))->with('status', 'Bạn chưa chọn tác vụ');
                }
                if ($act == 'delete') {
                    Permission::destroy($list_check);
                    return redirect(route('permission.list'))->with('status', 'Đã xoá thành công');
                }
                if ($act == 'restore') {
                    Permission::withTrashed()
                        ->whereIn('id', $list_check)
                        ->restore();
                    return redirect(route('permission.list'))->with('status', 'Đã khôi phục thành công');
                }
                if ($act == 'forceDelete') {
                    Permission::withTrashed()
                        ->whereIn('id', $list_check)
                        ->forceDelete();
                    return redirect(route('permission.list'))->with('status', 'Đã xoá vĩnh viễn thành công');
                }
            } else {
                return redirect(route('permission.list'))->with('status', 'Bạn không thể xoá quyền admin');
            }
        } else {
            return redirect(route('permission.list'))->with('status', 'Bạn chưa chọn người dùng');
        }
    }
    function edit($id)
    {  
        $permission = Permission::find($id);
        return view('admin.permission.edit', compact('permission'));
    }
    public function update(Request $request, $id)
    {
        $request->validate(
            [
                'name' => ['required', 'string', 'max:40'],
                'display_name' => ['required', 'string', 'max:40']
            ],
            [
                'required' => ':attribute không được để trống',
                'max' => ':attribute có độ dài lớn nhất :max ký tự',
                'min' => ':attribute có độ dài nhỏ nhất :min ký tự',

            ],
            ['name' => 'Tên quyền',
            'display_name'=>'Tên hiển thị'
            ]
        );
            Permission::where('id', $id)
                ->update([
                    'name' => $request->input('name'),
                    'display_name'=>$request->input('display_name')
                ]);
            return redirect(route('permission.list'))->with('status', 'Cập nhật thành công');
        
    }
    
}
