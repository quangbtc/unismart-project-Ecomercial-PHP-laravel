<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Role;
use App\Permission;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Eloquent\SoftDeletes;

class AdminRoleController extends Controller
{
    function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'role']);
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
            $roles = Role::onlyTrashed()->paginate(10);
            $list_act = [
                'restore' => 'Khôi phục',
                'forceDelete' => 'Xoá vĩnh viễn'
            ];
        } else {
            $keyword = '';
            if ($request->input('keyword')) {
                $keyword = $request->input('keyword');
            }
            $roles = Role::where('name', 'LIKE', "%{$keyword}%")->paginate(10);
        }
        $role_active = Role::count();
        $role_trashed = Role::onlyTrashed()->count();
        $count = [$role_active, $role_trashed];
        return view('admin.role.list', compact('roles', 'count', 'list_act'));
    }
    function add()
    {
        $permissions = Permission::all();

        return view('admin.role.add', compact('permissions'));
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
        $roleCreate = Role::create(
            [
                'name' => $request->input('name')
            ]
        );

        $roleCreate->permissions()->attach($request->permission);
        return redirect(route('role.list'))->with('status', 'Thêm quyền thành công');
    }
    function delete($id)
    {
        $roles = Role::find($id);
        $roles->delete();
        //Xoá permission_id bên bảng permission_role
        // $roles->permissions()->detach();
        // $roles->users()->detach();
        return redirect(route('role.list'))->with('status', 'Đã xoá thành công');
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
                    return redirect(route('role.list'))->with('status', 'Bạn chưa chọn tác vụ');
                }
                if ($act == 'delete') {
                    Role::destroy($list_check);
                    return redirect(route('role.list'))->with('status', 'Đã xoá thành công');
                }
                if ($act == 'restore') {
                    Role::withTrashed()
                        ->whereIn('id', $list_check)
                        ->restore();
                    return redirect(route('role.list'))->with('status', 'Đã khôi phục thành công');
                }
                if ($act == 'forceDelete') {
                    // Role::withTrashed()
                    //     ->whereIn('id', $list_check)
                    //     ->forceDelete();
                    try {
                        DB::beginTransaction();
                        Role::withTrashed()
                            ->whereIn('id', $list_check)
                            ->forceDelete();
                        DB::table('role_user')
                          ->whereIn('role_id',$list_check)
                        ->delete();
             
                        DB::table('permission_role')
                        ->whereIn('role_id',$list_check)
                        ->delete();
                        DB::commit();
                        return redirect(route('role.list'))->with('status', 'Đã xoá vĩnh viễn thành công');
                    } catch (\Exception $exception) {
                        DB::rollBack();
                    }
        
                }
            } else {
                return redirect(route('role.list'))->with('status', 'Bạn không thể xoá quyền admin');
            }
        } else {
            return redirect(route('role.list'))->with('status', 'Bạn chưa chọn người dùng');
        }
    }
    function edit($id)
    {
        $role = Role::find($id);
        $permissions = Permission::all();
        //Lấy ra id permission_id==role_id
        $list_permission = DB::table('permission_role')->where('role_id', $id)->pluck('permission_id');
        return view('admin.role.edit', compact('role', 'permissions', 'list_permission'));
    }
    public function update(Request $request, $id)
    {
        $request->validate(
            [
                'name' => ['required', 'string', 'max:40']
            ],
            [
                'required' => ':attribute không được để trống',
                'max' => ':attribute có độ dài lớn nhất :max ký tự',
                'min' => ':attribute có độ dài nhỏ nhất :min ký tự',
                
            ],
            ['name' => 'Tên quyền']
        );
        try {
            DB::beginTransaction();
            Role::where('id', $id)
                ->update([
                    'name' => $request->input('name'),
                ]);
            // Cập nhật permission id
            DB::table('permission_role')->where('role_id', $id)->delete(); // Xoá role_id cũ
            //Lấy ra role mới cập nhật
            $role = Role::find($id);
            $role->permissions()->attach($request->permission); //Cập nhật lại permission_id
            DB::commit();
            return redirect(route('role.list'))->with('status', 'Cập nhật thành công');
        } catch (\Exception $exception) {
            DB::rollBack();
        }
    }
}
