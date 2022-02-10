@extends('layouts.admin')
@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold">
                Cập nhật sản phẩm
            </div>
            <div class="card-body">
                <form action="{{ route('product.update',$product->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="title">Tên sản phẩm</label>
                        <input class="form-control" type="text" name="title" value="{{ $product->title }}" id="name">
                        @error('title')
                            <small class='text-danger'>{{ $message }}</small>
                        @enderror
                    </div>


                    <div class="form-group">
                        <label for="short_desc">Mô tả ngắn</label>
                        <textarea name="short_desc" value="" class="form-control" id="short_desc" cols="30" rows="5">
                            {{ $product->short_desc }}
                        </textarea>
                    </div>
                    <div class="form-group">
                        <label for="content">Chi tiết sản phẩm</label>
                        <textarea name="detail" value="" class="form-control" cols="30" rows="10">{{ $product->detail }}
                                            </textarea>
                        @error('detail')
                            <small class='text-danger'>{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="title">Giá bán</label>
                        <input class="form-control" type="number" name="sale_price" value="{{ $product->sale_price }}" id="name">
                        @error('sale_price')
                            <small class='text-danger'>{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="title">Giá cũ</label>
                        <input class="form-control" type="number" name="old_price" value="{{ $product->old_price }}" id="name">
                        @error('old_price')
                            <small class='text-danger'>{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="title">Số lượng tồn kho</label>
                        <input class="form-control" type="number" name="inventory" value="{{ $product->inventory }}" id="name">
                        @error('inventory')
                            <small class='text-danger'>{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="name">Hình đại diện</label>
                        <input class="form-control-file" type="file" name="file" value="" id="name">
                        <img width="50px" height="50px" src="{{url($product->thumb)}}" alt="">
                    </div>
                    <div class="form-group">
                        <label>Hình ảnh mô tả</label>
                        <input type="file" name="files[]" class="form-control-file" id="upload-thumb" multiple="mutilple">
                        @foreach ($images as $image)
                            <img width="50px" height="50px" src="{{url($image->image)}}" alt="">
                        @endforeach
                        
                    </div>
                    <div class="form-group">
                        <label for="">Danh mục</label>
                        <select class="form-control" id="" name="parent_cat">
                            <option value=''>Chọn danh mục</option>
                            @foreach ($list_cat as $cat)
                                <option value="{{ $cat->id }}" {{$cat->id == $product->parent_cat?'selected':''}}> @php
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
                    <input type="submit" class="btn btn-primary" name="btn_add_post" value="Cập nhật sản phẩm sản phẩm">
                </form>
            </div>
        </div>
    </div>
@endsection
