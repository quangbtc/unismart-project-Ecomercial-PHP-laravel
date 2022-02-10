<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product_Cat;
use App\Helpers;
use Illuminate\Support\Str;

class AdminProductCatController extends Controller
{
    //
    function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'product_cat']);
            return $next($request);
        });
    }
    function list(Request $request)
    {
        //B1: Lấy trạng thái từ url
        //B2: Kiểm tra trạng thái là active hay trash
        //B3: Lấy dữ liệu ra từ trạng thái active-> có hàm treeview
        //B4: Lấy dữ liệu ra từ trạng thái trash-> k cần tree-view
        //B5: Lấy dữ liệu ra từ tìm kiếm-> nếu ko có dữ liệu thì hiển thị tree-view
        //-> nêu có dữ liệu thì không cần tree-view  
        //B6: Đêm số lượng active và trash cho vào mảng count xuất qua view 
        $status = $request->input('status'); // Lấy trạng thái chọn từ ur
        $list_act = [
            'delete' => 'Xoá',
            'un_confirm' => 'Huỷ duyệt'
        ];
        if ($status == 'non_active') { // Kiểm tra có phải trạng thái trash
            $list_cat = Product_Cat::where('status', 'Chờ duyệt')->get();
            $list_act = [
                'confirm' => 'Duyệt',
            ];
        } elseif ($status == 'active') {           //Kiểm tra có phải trạng thái kichs hoat hay ko
            $product_cat = Product_Cat::where('status', 'Công khai')->get();
            $list_cat = data_tree($product_cat);
        } else {
            $keyword = '';
            if ($request->input('keyword')) { // Kiểm tra có tồn tại dữ liệu tìm kiếm
                $keyword = $request->input('keyword');
                $list_cat = Product_Cat::where('cat_title', 'LIKE', "%{$keyword}%")->get();
            } else {
                $product_cat = Product_Cat::all();
                $list_cat = data_tree($product_cat);
            }
        }


        $cat_active = Product_Cat::where('status', 'Công khai')->count();
        $cat_non_active = Product_Cat::where('status', 'Chờ duyệt')->count();
        $count = [$cat_active, $cat_non_active];
        return view('admin.product_cat.list', compact('list_cat', 'count', 'list_act', 'status'));
    }
    function add()
    {
        $product_cat_tree = Product_Cat::all();
        $list_cat = data_tree($product_cat_tree, 0, 0);
        return view('admin.product_cat.add', compact('list_cat'));
    }
    function store(Request $request)
    {
        //    dd($request);

        $request->validate(
            [
                'cat_title' => ['required', 'string', 'max:40', 'unique:post_cat']
            ],
            [
                'required' => ':attribute không được để trống',
                'max' => ':attribute có độ dài lớn nhất :max ký tự',
                'min' => ':attribute có độ dài nhỏ nhất :min ký tự',
                'unique' => ':attribute đã tồn tại'
            ],
            [
                'cat_title' => 'Tên danh mục'
            ]
        );
        Product_Cat::create([
            'cat_title' => $request->input('cat_title'),
            'parent_id' => $request->input('parent_cat'),
            'status' => $request->input('status'),
            'slug' => Str::slug($request->input('cat_title'), '-')
        ]);
        return redirect(route('product_cat.add'))->with('status', 'Đã thêm danh mục thành công');
    }
    function edit($id)
    {
        //B1: Lấy dữ liệu theo id
        $product_cat = Product_Cat::find($id);
        $data_cat = Product_Cat::all();
        $list_cat = data_tree($data_cat);
        return view('admin.product_cat.edit', compact('list_cat', 'product_cat'));
    }
    function update(Request $request, $id)
    {
        $request->validate(
            [
                'cat_title' => ['required', 'string', 'max:40']
            ],
            [
                'required' => ':attribute không được để trống',
                'max' => ':attribute có độ dài lớn nhất :max ký tự',
                'min' => ':attribute có độ dài nhỏ nhất :min ký tự',

            ],
            [
                'cat_title' => 'Tên danh mục'
            ]
        );

        Product_Cat::where('id', $id)
            ->update([
                'cat_title' => $request->input('cat_title'),
                'parent_id' => $request->input('parent_cat'),
                'status' => $request->input('status'),
                'slug' => Str::slug($request->input('cat_title'), '-')
            ]);
        return redirect(route('product_cat.edit', $id))->with('status', 'Đã cập nhật thành công');
    }
    function delete($id)
    {
        Product_Cat::find($id)->delete();
        $list_cat = Product_Cat::all();
        //Tìm tất cả các danh mục con của danh mục có id=$id
        $list_child = data_tree($list_cat, $id, 0);
        foreach ($list_child as $value) {
            $list_child_cat_id[] = $value['id'];
        }
        if(!empty($list_child_cat_id)){
            Product_Cat::destroy($list_child_cat_id);
        }
        
        return redirect(route('product_cat.list'))->with('status', 'Đã xoá thành công');
    }
    function action(Request $request)
    {
        $list_check = $request->input('list_check');

        if (!empty($list_check)) {
            $act = $request->input('act');

            if ($act == '') {
                return redirect(route('product_cat.list'))->with('status', 'Bạn chưa chọn tác vụ');
            }
            if ($act == 'delete') {
                Product_Cat::destroy($list_check);
                return redirect(route('product_cat.list'))->with('status', 'Đã xoá thành công');
            }
            if ($act == 'confirm') {
                foreach ($list_check as $value) {
                    Product_Cat::where('id', $value)
                        ->update([
                            'status' => 'Công khai'
                        ]);
                }
                return redirect(route('product_cat.list'))->with('status', 'Đã duyệt thành công');
            }
            if ($act == 'un_confirm') {
                foreach ($list_check as  $value) {
                    Product_Cat::where('id', $value)
                        ->update([
                            'status' => 'Chờ duyệt'
                        ]);
                }
                return redirect(route('product_cat.list'))->with('status', 'Huỷ duyệt thành công');
            }
        } else {
            return redirect(route('product_cat.list'))->with('status', 'Bạn chưa chọn danh mục');
        }
    }
}
