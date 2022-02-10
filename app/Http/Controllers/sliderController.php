<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\slider;

class sliderController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'slider']);
            return $next($request);
        });
    }
    public function list(Request $request)
    {
        $status = $request->input('status'); // Lấy trạng thái chọn từ url
        $list_act = [
            'delete' => 'Xoá slider'
        ];
        $sliders = slider::paginate(10);
        return view('admin.slider.list', compact('sliders', 'list_act'));
    }
    public function add()
    {
        return view('admin.slider.add');
    }
    public function store(Request $request)
    {
        $request->validate(
            [
                'name' => ['required', 'string', 'max:40'],
                'image_name' => ['required', 'string', 'max:255', 'unique:slider'],
                'file' => ['image']
            ],
            [
                'required' => ':attribute không được để trống',
                'max' => ':attribute có độ dài lớn nhất :max ký tự',
                'image' => ':attribute không là file ảnh'
            ],
            [
                'name' => 'Tên loại slider',
                'image_name' => 'Tên hình slider',

            ]
        );
        if ($request->hasFile('file')) {
            echo "có file";
            $file = $request->file;
            //Lấy tên file
            $filename = $file->getClientOriginalName();
            //Lấy đuôi file
            $end_file = $file->getClientOriginalExtension();

            //Lấy kích thước file
            $file->getSize();
            //Chuyển file lên server
            $file->move('public/uploads/slider/', $file->getClientOriginalName());
            $thumbnail = 'public/uploads/slider/' . $filename;
        }
        slider::create([
            'name' => $request->input('name'),
            'image_name' => $request->input('image_name'),
            'image' => $thumbnail
        ]);
        return redirect(route('slider.list'))->with('status', 'Đã thêm slider thành công');
    }
    function delete($id)
    {
        slider::find($id)->delete();
        return redirect(route('slider.list'))->with('status', 'Xoá thành công');
    }
    function action(Request $request)
    {
        if ($request->input('act') == null) {
            return redirect(route('slider.list'))->with('status', 'Bạn chưa chọn tác vụ');
        } elseif ($request->input('act') == 'delete') {
            if ($request->input('list_check')) {
                slider::destroy($request->input('list_check'));
                return redirect(route('slider.list'))->with('status', 'Xoá thành công');
            } else {
                return redirect(route('slider.list'))->with('status', 'Bạn chưa chọn slider');
            }
        }
    }
}
