<?php

namespace App\Http\Controllers;

use App\Role_User;
use Illuminate\Http\Request;
use App\User;
use App\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\TryCatch;

class AdminUserController extends Controller
{
    function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'user']);
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
            $users = User::onlyTrashed()->paginate(10);
            $list_act = [
                'restore' => 'Khôi phục',
                'forceDelete' => 'Xoá vĩnh viễn'
            ];
        } else {
            $keyword = '';
            if ($request->input('keyword')) {
                $keyword = $request->input('keyword');
            }

            $users = User::where('name', 'LIKE', "%{$keyword}%")
                ->paginate(10);
        }

        $user_active = User::count();
        $user_trashed = User::onlyTrashed()->count();
        $count = [$user_active, $user_trashed];
        $roles = Role::all();
        foreach ($roles as $role) {
            $data_role[$role->id] = $role->name;
        }

        return view('admin.user.list', compact('users', 'count', 'list_act'));
    }
    function add()
    {
        //Lấy thông tin quyền
        $roles = Role::all();

        // return $roles;
        return view('admin.user.add', compact('roles'));
    }
    function store(Request $request)
    {

        if ($request->input('btn_add')) {
            // return $request->input();
            $request->validate(
                [
                    'name' => ['required', 'string', 'max:40'],
                    'email' => ['required', 'string', 'email', 'max:50', 'unique:users'],
                    'password' => ['required', 'string', 'min:8', 'confirmed'],

                ],
                [
                    'required' => ':attribute không được để trống',
                    'max' => ':attribute có độ dài lớn nhất :max ký tự',
                    'min' => ':attribue có độ dài nhỏ nhất :min ký tự',
                    'confirmed' => 'Xác nhận mật khẩu không đúng',
                    'email' => 'Email không đúng định dạng'
                ],
                [
                    'name' => 'Tên người dùng',
                    'email' => 'Email',
                    'password' => 'Mật khẩu'
                ]
            );
            $lastId = User::create(
                [
                    'name' => $request->input('name'),
                    'email' => $request->input('email'),
                    'password' => Hash::make($request->input('password')),

                ]
            );
            $lastId->roles()->attach($request->roles); // Thêm user id và role id vào bảng role_id
            // $roles=$request->input('roles');
            // foreach ($roles as $roleId) {
            //     DB::table('role_user')->insert(
            //         ['user_id' =>$lastId->id,
            //          'role_id' =>$roleId]
            //     );
            // }

            return redirect(route('user.list'))->with('status', 'Thêm ngừoi dùng thành công');
        }
    }
    function delete($id)
    {
        if (Auth::id() != $id) {
            $user = User::find($id);
            $user->delete();
            //Xoá user_id bên bảng role_user
            // $user->roles()->detach();
            return redirect(route('user.list'))->with('status', 'Đã xoá thành công');
        } else {
            return redirect(route('user.list'))->with('status', 'Không thể xoá người dùng này');
        }
    }
    function action(Request $request)
    {
        $list_check = $request->input('list_check');
        // return $list_check;
        if (!empty($list_check)) {
            foreach ($list_check as $k => $id) {
                if (Auth::id() == $id) {
                    unset($list_check[$k]);
                }
            }
            if (!empty($list_check)) {
                $act = $request->input('act');
                if ($act == '') {
                    return redirect(route('user.list'))->with('status', 'Bạn chưa chọn tác vụ');
                }
                if ($act == 'delete') {
                    User::destroy($list_check);
                    //Xoá roles của users

                    return redirect(route('user.list'))->with('status', 'Đã xoá thành công');
                }
                if ($act == 'restore') {
                    User::withTrashed()
                        ->whereIn('id', $list_check)
                        ->restore();


                    return redirect(route('user.list'))->with('status', 'Đã khôi phục thành công');
                }
                if ($act == 'forceDelete') {
                    try {
                        DB::beginTransaction();
                        User::withTrashed()
                            ->whereIn('id', $list_check)
                            ->forceDelete();
                        DB::table('role_user')
                            ->whereIn('user_id',$list_check)
                            ->delete();
                        // dd($listRoleOfUser);
                     
                        DB::commit();
                        return redirect(route('user.list'))->with('status', 'Đã xoá vĩnh viễn thành công');
                    } catch (\Exception $exception) {
                        DB::rollBack();
                    }
                }
            } else {
                return redirect(route('user.list'))->with('status', 'Bạn không thể xoá admin');
            }
        } else {
            return redirect(route('user.list'))->with('status', 'Bạn chưa chọn người dùng');
        }
    }
    function edit($id)
    {
        $user = User::find($id);
        $roles = Role::all();
        $list_role_user = DB::table('role_user')->where('user_id', $id)->pluck('role_id'); //Lấy role_id của user
        // return $list_role_user;

        return view('admin.user.edit', compact('user', 'roles', 'list_role_user'));
    }
    public function update(Request $request, $id)
    {
        $request->validate(
            [
                'name' => ['required', 'string', 'max:40'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],

            ],
            [
                'required' => ':attribute không được để trống',
                'max' => ':attribute có độ dài lớn nhất :max ký tự',
                'min' => ':attribue có độ dài nhỏ nhất :min ký tự',
                'confirmed' => 'Xác nhận mật khẩu không đúng',
            ],
            [
                'name' => 'Tên người dùng',
                'password' => 'Mật khẩu'
            ]
        );
        try {
            DB::beginTransaction();
            User::where('id', $id)
                ->update([
                    'name' => $request->input('name'),
                    'password' => Hash::make($request->input('password'))
                ]);
            //Cập  nhật lại user_id cho bảng role_user
            DB::table('role_user')->where('user_id', $id)->delete(); // Xoá user_id cũ
            //Lấy ra user mới cập nhật
            $user = User::find($id);
            $user->roles()->attach($request->roles); //Cập nhật lại user_id
            DB::commit();
            return redirect(route('user.list'))->with('status', 'Cập nhật thành công');
        } catch (\Exception $exception) {
            DB::rollBack();
        }
    }
}
