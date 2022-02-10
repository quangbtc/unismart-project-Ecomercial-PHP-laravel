<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use App\Post_Cat;
use Illuminate\Support\Str;

class AdminPostController extends Controller
{
    function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'post']);
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
            $posts = Post::where('status', 'Chờ duyệt')->paginate(10);
            $list_act = [
                'confirm' => 'Duyệt',
            ];
        } elseif ($status == 'active') {           //Kiểm tra có phải trạng thái kichs hoat hay ko
            $posts = Post::where('status', 'Công khai')->paginate(10);
        } elseif ($status == 'trash') {
            $posts = Post::onlyTrashed()->paginate(10);
            $list_act = [
                'permanently_delete' => 'Xoá vĩnh viễn',
                'restore' => 'Phục hồi'
            ];
        } else {
            $keyword = '';
            if ($request->input('keyword')) { // Kiểm tra có tồn tại dữ liệu tìm kiếm
                $keyword = $request->input('keyword');
                $posts = Post::where('title', 'LIKE', "%{$keyword}%")->paginate(10);
            } else {
                $posts = Post::paginate(10);
            }
        }


        $post_active = Post::where('status', 'Công khai')->count();
        $post_non_active = Post::where('status', 'Chờ duyệt')->count();
        $trash = Post::onlyTrashed()->count();
        $count = [$post_active, $post_non_active, $trash];
        return view('admin.post.list', compact('posts', 'count', 'list_act', 'status'));
    }
    public function add()
    {
        $data = Post_Cat::all();
        $list_cat = data_tree($data, 0, 0);
        return view('admin.post.add', compact('list_cat'));
    }
    function store(Request $request)
    {

        $request->validate(
            [
                'title' => ['required', 'string', 'max:225', 'unique:posts'],
                'content' => ['required', 'string'],
                'parent_cat' => 'required',
                'file' => 'image',
            ],
            [
                'required' => ':attribute không được để trống',
                'max' => ':attribute có độ dài lớn nhất :max ký tự',
                'min' => ':attribute có độ dài nhỏ nhất :min ký tự',
                'unique' => ':attribute đã tồn tại',
                'image' => ':attribute Không là ảnh'
            ],
            [
                'title' => 'Tiêu đề bài viết',
                'content' => 'Nội dung bài viết',
                'parent_cat' => 'Danh mục bài viết',
                'file' => 'File'
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
        $input['slug'] = Str::slug($request->input('title'));

        Post::create($input);
        // return redirect('post/show');
        return redirect()->route('post.list')->with('status', 'Thêm bài viết thành công');
        // return $request->input();

    }
    function edit($id)
    {
        //B1: Lấy dữ liệu theo id
        $post = Post::find($id);
        $list_cat=Post_Cat::all();
        return view('admin.post.edit', compact('post','list_cat'));
    }
    function update(Request $request, $id)
    {
        $request->validate(
            [
                'title' => ['required', 'string', 'max:225'],
                'content' => ['required', 'string'],
                'parent_cat' => 'required',
                'file' => 'image',
            ],
            [
                'required' => ':attribute không được để trống',
                'max' => ':attribute có độ dài lớn nhất :max ký tự',
                'min' => ':attribute có độ dài nhỏ nhất :min ký tự',
                'image' => ':attribute Không là ảnh'
            ],
            [
                'title' => 'Tiêu đề bài viết',
                'content' => 'Nội dung bài viết',
                'parent_cat' => 'Danh mục bài viết',
                'file' => 'File'
            ]
        );
        $input = $request->all();
        $post_id=Post::find($id);
        $thumb=$post_id->thumb;
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
        }else{
            $thumbnail=$thumb;
        }
       
        $input['slug'] = Str::slug($request->input('title'));
        Post::where('id', $id)
        ->update([
            'title' => $request->input('title'),
            'parent_cat' => $request->input('parent_cat'),
            'status' => $request->input('status'),
            'slug' => Str::slug($request->input('title'), '-'),
            'thumb'=>$thumbnail,
            'content'=>$request->input('content'),
            'short_desc'=>$request->input('short_desc')
        ]);
        return redirect(route('post.list'))->with('status', 'Đã cập nhật thành công');
    }
    function delete($id)
    {
        Post::find($id)->delete();
        return redirect(route('post.list'))->with('status', 'Đã xoá thành công');
    }
    function action(Request $request)
    {
        $list_check = $request->input('list_check');
        if (!empty($list_check)) {
            $act = $request->input('act');
            if ($act == '') {
                return redirect(route('post.list'))->with('status', 'Bạn chưa chọn tác vụ');
            }
            if ($act == 'delete') {
                Post::destroy($list_check);
                return redirect(route('post.list'))->with('status', 'Đã xoá thành công');
            }
            if ($act == 'confirm') {
                foreach ($list_check as $value) {
                    Post::where('id', $value)
                        ->update([
                            'status' => 'Công khai'
                        ]);
                }
                return redirect(route('post.list'))->with('status', 'Đã duyệt thành công');
            }
            if ($act == 'un_confirm') {
                foreach ($list_check as  $value) {
                    Post::where('id', $value)
                        ->update([
                            'status' => 'Chờ duyệt'
                        ]);
                }
                return redirect(route('post.list'))->with('status', 'Huỷ duyệt thành công');
            }
            if ($act == 'permanently_delete') {
                foreach ($list_check as  $value) {
                    Post::onlyTrashed()->where('id', $value)
                        ->forceDelete();
                }
                return redirect(route('post.list'))->with('status', 'Xoá vĩnh viễn thành công');
            }
            if ($act == 'restore') {
                foreach ($list_check as  $value) {
                    Post::onlyTrashed()->where('id', $value)
                        ->restore();
                }
                return redirect(route('post.list'))->with('status', 'Phục hồi thành công');
            }

        } else {
            return redirect(route('post.list'))->with('status', 'Bạn chưa chọn danh mục');
        }
    }
}
