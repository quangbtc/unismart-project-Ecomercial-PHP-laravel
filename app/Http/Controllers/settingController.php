<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\setting;
class settingController extends Controller
{

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'setting']);
            return $next($request);
        });
    }
    public function list(Request $request)
    {
        $status = $request->input('status'); // Lấy trạng thái chọn từ url
        $list_act = [
            'delete' => 'Xoá cài đặt'
        ];
        $settings = setting::paginate(10);
        return view('admin.setting.list', compact('settings', 'list_act'));
    }
    public function add()
    {
        return view('admin.setting.add');
    }
    public function store(Request $request)
    {
        $request->validate(
            [
                'config' => ['required', 'string', 'max:40', 'unique:setting'],
                'value' => ['required', 'string', 'max:255', 'unique:setting'],
            ],
            [
                'config' => ':attribute không được để trống',
                'max' => ':attribute có độ dài lớn nhất :max ký tự',
               
            ],
            [
                'config' => 'Tên cài đặt',
                'value' => 'Giá trị',

            ]
        );
        setting::create([
            'config' => $request->input('config'),
            'value' => $request->input('value'),
        ]);
        return redirect(route('setting.list'))->with('status', 'Đã thêm cài đặt thành công');
    }
    function delete($id)
    {
        setting::find($id)->delete();
        return redirect(route('setting.list'))->with('status', 'Xoá thành công');
    }
    function action(Request $request)
    {
        if ($request->input('act') == null) {
            return redirect(route('setting.list'))->with('status', 'Bạn chưa chọn tác vụ');
        } elseif ($request->input('act') == 'delete') {
            if ($request->input('list_check')) {
                setting::destroy($request->input('list_check'));
                return redirect(route('setting.list'))->with('status', 'Xoá thành công');
            } else {
                return redirect(route('setting.list'))->with('status', 'Bạn chưa chọn cài đặt');
            }
        }
    }
    function edit($id){
        $setting=setting::find($id);
        return view('admin/setting/edit',compact('setting'));
    }
    function update(Request $request,$id){
        $input=$request->all();
        $request->validate(
            [
                'config' => ['required', 'string', 'max:40'],
                'value' => ['required', 'string', 'max:255'],
            ],
            [
                'config' => ':attribute không được để trống',
                'max' => ':attribute có độ dài lớn nhất :max ký tự',

            ],
            [
                'config' => 'Tên cài đặt',
                'value' => 'Giá trị',
            ]
        );
        $input = $request->all();
        setting::find($id)->update($input);
        return redirect()->route('setting.list')->with('status','Đã cập nhật thành công');
    }
}
