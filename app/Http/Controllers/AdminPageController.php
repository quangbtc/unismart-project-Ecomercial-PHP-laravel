<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\page;
use Illuminate\Support\Str;
class AdminPageController extends Controller
{
    function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'page']);
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
            $pages = page::where('status', 'Chờ duyệt')->paginate(10);
            $list_act = [
                'confirm' => 'Duyệt',
            ];
        } elseif ($status == 'active') {           //Kiểm tra có phải trạng thái kichs hoat hay ko
            $pages = page::where('status', 'Công khai')->paginate(10);
        } elseif ($status == 'trash') {
            $pages = page::onlyTrashed()->paginate(10);
            $list_act = [
                'permanently_delete' => 'Xoá vĩnh viễn',
                'restore' => 'Phục hồi'
            ];
        } else {
            $keyword = '';
            if ($request->input('keyword')) { // Kiểm tra có tồn tại dữ liệu tìm kiếm
                $keyword = $request->input('keyword');
                $pages = page::where('title', 'LIKE', "%{$keyword}%")->paginate(10);
            } else {
                $pages = page::paginate(10);
            }
        }


        $post_active = page::where('status', 'Công khai')->count();
        $post_non_active = page::where('status', 'Chờ duyệt')->count();
        $trash = page::onlyTrashed()->count();
        $count = [$post_active, $post_non_active, $trash];
        return view('admin.page.list', compact('pages', 'count', 'list_act', 'status'));
    }
    public function add()
    {
        return view('admin.page.add');
    }
    function store(Request $request)
    {
        $request->validate(
            [
                'title' => ['required', 'string', 'max:225', 'unique:pages'],
                'content' => ['required', 'string'],
            ],
            [
                'required' => ':attribute không được để trống',
                'max' => ':attribute có độ dài lớn nhất :max ký tự',
                'unique' => ':attribute đã tồn tại',
               
            ],
            [
                'title' => 'Tiêu đề bài viết',
                'content' => 'Nội dung bài viết',
            ]
        );
        $input = $request->all();
        $input['slug'] = Str::slug($request->input('title'));
        page::create($input);
        // return redirect('post/show');
        return redirect()->route('page.list')->with('status', 'Thêm trang thành công');
        // return $request->input();

    }
    function edit($id)
    {
        //B1: Lấy dữ liệu theo id
        $page = page::find($id);
        return view('admin.page.edit', compact('page'));
    }
    function update(Request $request, $id)
    {
        $request->validate(
            [
                'title' => ['required', 'string', 'max:225'],
                'content' => ['required', 'string'],
            ],
            [
                'required' => ':attribute không được để trống',
                'max' => ':attribute có độ dài lớn nhất :max ký tự',
                'unique' => ':attribute đã tồn tại',

            ],
            [
                'title' => 'Tiêu đề bài viết',
                'content' => 'Nội dung bài viết',
            ]
        );
      
        page::where('id', $id)
            ->update([
                'title' => $request->input('title'),
                'status' => $request->input('status'),
                'slug' => Str::slug($request->input('title'), '-'),
                'content' => $request->input('content'),
            ]);
        return redirect(route('page.list'))->with('status', 'Đã cập nhật thành công');
    }
    function delete($id)
    {
        page::find($id)->delete();
        return redirect(route('page.list'))->with('status', 'Đã xoá thành công');
    }
    function action(Request $request)
    {
        $list_check = $request->input('list_check');
        if (!empty($list_check)) {
            $act = $request->input('act');
            if ($act == '') {
                return redirect(route('page.list'))->with('status', 'Bạn chưa chọn tác vụ');
            }
            if ($act == 'delete') {
                page::destroy($list_check);
                return redirect(route('page.list'))->with('status', 'Đã xoá thành công');
            }
            if ($act == 'confirm') {
                foreach ($list_check as $value) {
                    page::where('id', $value)
                        ->update([
                            'status' => 'Công khai'
                        ]);
                }
                return redirect(route('page.list'))->with('status', 'Đã duyệt thành công');
            }
            if ($act == 'un_confirm') {
                foreach ($list_check as  $value) {
                    page::where('id', $value)
                        ->update([
                            'status' => 'Chờ duyệt'
                        ]);
                }
                return redirect(route('page.list'))->with('status', 'Huỷ duyệt thành công');
            }
            if ($act == 'permanently_delete') {
                foreach ($list_check as  $value) {
                    page::onlyTrashed()->where('id', $value)
                        ->forceDelete();
                }
                return redirect(route('page.list'))->with('status', 'Xoá vĩnh viễn thành công');
            }
            if ($act == 'restore') {
                foreach ($list_check as  $value) {
                    page::onlyTrashed()->where('id', $value)
                        ->restore();
                }
                return redirect(route('page.list'))->with('status', 'Phục hồi thành công');
            }
        } else {
            return redirect(route('page.list'))->with('status', 'Bạn chưa chọn trang');
        }
    }
}
