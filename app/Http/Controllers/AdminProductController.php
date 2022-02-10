<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Product_Cat;
use App\product_image;
use Illuminate\Support\Str;

class AdminProductController extends Controller
{
    function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'product']);
            return $next($request);
        });
    }
    //
    public function list(Request $request)
    {

        $status = $request->input('status'); // Lấy trạng thái chọn từ ur
        $list_act = [
            'delete' => 'Xoá tạm thời',
            'un_confirm' => 'Huỷ duyệt'
        ];
        if ($status == 'non_active') { // Kiểm tra có phải trạng thái trash
            $products = Product::where('status', 'Chờ duyệt')->paginate(10);
            $list_act = [
                'confirm' => 'Duyệt',
            ];
        } elseif ($status == 'active') {           //Kiểm tra có phải trạng thái kichs hoat hay ko
            $products = Product::where('status', 'Công khai')->paginate(10);
        } elseif ($status == 'trash') {
            $products = Product::onlyTrashed()->paginate(10);
            $list_act = [
                'permanently_delete' => 'Xoá vĩnh viễn',
                'restore' => 'Phục hồi'
            ];
        } else {
            $keyword = '';
            if ($request->input('keyword')) { // Kiểm tra có tồn tại dữ liệu tìm kiếm
                $keyword = $request->input('keyword');
                $products = Product::where('title', 'LIKE', "%{$keyword}%")->paginate(10);
            } else {
                $products = Product::paginate(10);
            }
        }


        $product_active = Product::where('status', 'Công khai')->count();
        $product_non_active = Product::where('status', 'Chờ duyệt')->count();
        $trash = Product::onlyTrashed()->count();
        $count = [$product_active, $product_non_active, $trash];
        return view('admin.product.list', compact('products', 'count', 'list_act', 'status'));
    }
    public function add()
    {
        $data = Product_Cat::all();
        $list_cat = data_tree($data, 0, 0);
        return view('admin.product.add', compact('list_cat'));
    }
    function store(Request $request)
    {
        $request->validate(
            [
                'title' => ['required', 'string', 'max:225', 'unique:product'],
                'detail' => ['required', 'string'],
                'parent_cat' => 'required',
                'file' => 'image',
            ],
            [
                'required' => ':attribute không được để trống',
                'max' => ':attribute có độ dài lớn nhất :max ký tự',
                'unique' => ':attribute đã tồn tại',
                'image' => ':attribute Không là ảnh',
                'string' => ':attribute phải là chuỗi'
            ],
            [
                'title' => 'Tiêu đề bài viết',
                'detail' => 'Chi tiết sản phẩm',
                'parent_cat' => 'Danh mục sản phẩm',
                'file' => 'Hình đại diện',
            ]
        );
        $input = $request->all();
        if ($request->hasFile('file')) {
            $file = $request->file;
            //Lấy tên file
            $filename = $file->getClientOriginalName();
            //Lấy đuôi file
            $file->getClientOriginalExtension();
            //Lấy kích thước file
            $file->getSize();
            //Chuyển file lên server
            $file->move('public/uploads', $file->getClientOriginalName());
            $thumbnail = 'public/uploads/' . $filename;
            $input['thumb'] = $thumbnail;
        }
        if ($request->hasFile('files')) {
            $images = $request->file('files');

            foreach ($images as  $item) {
                $imageName = $item->getClientOriginalName();
                $item->move('public/uploads/products/', $imageName);
                $arr[] = 'public/uploads/products/' . $imageName;
            }
        }
        $input['slug'] = Str::slug($request->input('title'));
        //Thêm sản phẩm
        $id = Product::create($input)->id;
        //Thêm hình mô tả
        foreach ($arr as $image) {
            product_image::create([
                'product_id' => $id,
                'image' => $image
            ]);
        }
        // return redirect('post/show');
        return redirect()->route('product.list')->with('status', 'Thêm sản phẩm thành công');
        // return $request->input();

    }
    function edit($id)
    {
        //B1: Lấy dữ liệu theo id

        $product = Product::find($id);
        $images = $product->images;
        $list_cat = Product_Cat::all();
        return view('admin.product.edit', compact('product', 'list_cat', 'images'));
    }
    function update(Request $request, $id)
    {
        $request->validate(
            [
                'title' => ['required', 'string', 'max:225'],
                'detail' => ['required', 'string'],
                'parent_cat' => 'required',
                'file' => 'image',
            ],
            [
                'required' => ':attribute không được để trống',
                'max' => ':attribute có độ dài lớn nhất :max ký tự',
                'unique' => ':attribute đã tồn tại',
                'image' => ':attribute Không là ảnh',
                'string' => ':attribute phải là chuỗi'
            ],
            [
                'title' => 'Tiêu đề bài viết',
                'detail' => 'Chi tiết sản phẩm',
                'parent_cat' => 'Danh mục sản phẩm',
                'file' => 'Hình đại diện',
            ]
        );
        $input = $request->all();
        $product = Product::find($id);
        $thumb = $product->thumb;
        if ($request->hasFile('file')) {
            $file = $request->file;
            //Lấy tên file
            $filename = $file->getClientOriginalName();
            //Lấy đuôi file
            $file->getClientOriginalExtension();
            //Lấy kích thước file
            $file->getSize();
            //Chuyển file lên server
            $file->move('public/uploads', $file->getClientOriginalName());
            $thumbnail = 'public/uploads/' . $filename;
            $input['thumb'] = $thumbnail;
        } else {
            $thumbnail = $thumb;
        }
        //Lấy thông tin ảnh mô tả cũ
        $images = $product->images;
        if ($request->hasFile('files')) {

            $pictures = $request->file('files');

            foreach ($pictures as  $pic) {
                $imageName = $pic->getClientOriginalName();
                $pic->move('public/uploads/products/', $imageName);
                $arr[] = 'public/uploads/products/' . $imageName;
            }
        }

        $input['slug'] = Str::slug($request->input('title'));
        Product::where('id', $id)
            ->update([
                'title' => $request->input('title'),
                'parent_cat' => $request->input('parent_cat'),
                'status' => $request->input('status'),
                'slug' => Str::slug($request->input('title'), '-'),
                'thumb' => $thumbnail,
                'detail' => $request->input('detail'),
                'short_desc' => $request->input('short_desc'),
                'sale_price' => $request->input('sale_price'),
                'old_price' => $request->input('old_price'),
                'inventory' => $request->input('inventory')
            ]);
        if (!empty($arr)) {
            product_image::where('product_id', $id)->delete();
            foreach ($arr as $image) {
                product_image::create([
                    'product_id' => $id,
                    'image' => $image
                ]);
            }
        }

        return redirect(route('product.list'))->with('status', 'Đã cập nhật thành công');
    }
    function delete($id)
    {
        Product::find($id)->delete();
        return redirect(route('product.list'))->with('status', 'Đã xoá thành công');
    }
    function action(Request $request)
    {
        $list_check = $request->input('list_check');
        if (!empty($list_check)) {
            $act = $request->input('act');
            if ($act == '') {
                return redirect(route('product.list'))->with('status', 'Bạn chưa chọn tác vụ');
            }
            if ($act == 'delete') {
                Product::destroy($list_check);
                return redirect(route('product.list'))->with('status', 'Đã xoá thành công');
            }
            if ($act == 'confirm') {
                foreach ($list_check as $value) {
                    Product::where('id', $value)
                        ->update([
                            'status' => 'Công khai'
                        ]);
                }
                return redirect(route('product.list'))->with('status', 'Đã duyệt thành công');
            }
            if ($act == 'un_confirm') {
                foreach ($list_check as  $value) {
                    Product::where('id', $value)
                        ->update([
                            'status' => 'Chờ duyệt'
                        ]);
                }
                return redirect(route('product.list'))->with('status', 'Huỷ duyệt thành công');
            }
            if ($act == 'permanently_delete') {

                foreach ($list_check as $value) {

                    Product::onlyTrashed()->where('id', $value)
                        ->forceDelete();
                    if (product_image::where('product_id', $value)) {
                        product_image::where('product_id', $value)->delete();
                    }
                }


                return redirect(route('product.list'))->with('status', 'Xoá vĩnh viễn thành công');
            }
            if ($act == 'restore') {
                foreach ($list_check as  $value) {

                    Product::onlyTrashed()->where('id', $value)
                        ->restore();
                }

                return redirect(route('product.list'))->with('status', 'Phục hồi thành công');
            }
        } else {
            return redirect(route('product.list'))->with('status', 'Bạn chưa chọn danh mục');
        }
    }
}
