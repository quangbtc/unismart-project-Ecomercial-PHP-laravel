@extends('layouts.admin')
@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold">
                Sửa bài viết bài viết
            </div>
            <div class="card-body">
                <form action="{{ route('post.update', $post->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="title">Tiêu đề bài viết</label>
                        <input class="form-control" type="text" name="title" value="{{ $post->title }}" id="name">
                        @error('title')
                            <small class='text-danger'>{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="short_desc">Mô tả ngắn</label>
                        <textarea name="short_desc" value="" class="form-control" id="short_desc" cols="30" rows="5">
                                {{ $post->short_desc }}

                                            </textarea>
                    </div>
                    <div class="form-group">
                        <label for="content">Nội dung bài viết</label>
                        <textarea name="content" value="" class="form-control" id="content" cols="30" rows="10">
                                            {!! $post->content !!}
                                            </textarea>
                        @error('content')
                            <small class='text-danger'>{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="name">Thumb</label>
                        <input class="form-control-file" type="file" name="file" value="{{$post->thumb}}" id="name">
                    </div>


                    <div class="form-group">
                        <label for="">Danh mục</label>
                        <select class="form-control" id="" name="parent_cat">
                            <option value=''>Chọn danh mục</option>
                            @foreach ($list_cat as $cat)
                                <option value="{{ $cat->id }}" {{ $cat->id == $post->parent_cat ? 'selected' : '' }}>
                                    @php
                                        echo str_repeat('===', $cat['level']);
                                    @endphp {{ $cat->cat_title }}</option>
                            @endforeach
                        </select>
                        @error('parent_cat')
                            <small class='text-danger'>{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">Trạng thái</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="status" id="exampleRadios1" value="Chờ duyệt"
                                checked>
                            <label class="form-check-label" for="exampleRadios1">
                                Chờ duyệt
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="status" id="exampleRadios2"
                                value="Công khai">
                            <label class="form-check-label" for="exampleRadios2">
                                Công khai
                            </label>
                        </div>
                    </div>
                    <input type="submit" class="btn btn-primary" name="btn_update_post" value="Cập nhật bài viết">
                </form>
            </div>
        </div>
    </div>
@endsection
